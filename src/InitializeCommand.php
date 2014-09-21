<?php

/**
 * This file is part of CoverallsKit.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace coverallskit;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;
use Eloquent\Pathogen\Factory\PathFactory;
use Eloquent\Pathogen\RelativePath;
use Eloquent\Pathogen\AbstractPath;
use Exception;

/**
 * Class InitializeCommand
 * @package coverallskit
 */
class InitializeCommand
{

    /**
     * @var Context
     */
    private $context;

    /**
     * @var Stdio
     */
    private $stdio;

    /**
     * @var \Eloquent\Pathogen\AbsolutePath
     */
    private $destDirectoryPath;

    /**
     * @var \Eloquent\Pathogen\AbsolutePath
     */
    private $destDirectoryFilePath;

    /**
     * @param Context $context
     * @param Stdio $stdio
     */
    public function __construct(Context $context, Stdio $stdio)
    {
        $this->context = $context;
        $this->stdio = $stdio;
    }

    /**
     * @param string|null $projectDirectory
     * @return int
     */
    public function __invoke($projectDirectory = null)
    {
        try {
            $this->prepare($projectDirectory);
        } catch (DirectoryNotFoundException $exception) {
            return $this->failed($exception);
        }

        return $this->performAction();
    }

    /**
     * @param string|null $projectDirectory
     */
    private function prepare($projectDirectory = null)
    {
        $projectRelativeDirectory = $projectDirectory;
        $configFilePath = RelativePath::fromString('.coveralls.yml');
        $destDirectoryPath = PathFactory::instance()->create(getcwd());

        if (is_null($projectRelativeDirectory)) {
            $projectRelativeDirectory = AbstractPath::SELF_ATOM;
        }

        $projectDirectoryPath = RelativePath::fromString($projectRelativeDirectory);
        $absoluteDestDirectoryPath = $destDirectoryPath->resolve($projectDirectoryPath);

        $this->destDirectoryPath = $absoluteDestDirectoryPath;
        $this->destDirectoryFilePath = $absoluteDestDirectoryPath->resolve($configFilePath);

        if (file_exists((string) $this->destDirectoryPath)) {
            return;
        }

        throw new DirectoryNotFoundException("'$this->destDirectoryPath' does not exist.");
    }

    /**
     * @param PrintableExceptionInterface $exception
     * @return int
     */
    private function failed(PrintableExceptionInterface $exception)
    {
        $exception->printMessage($this->stdio);
        return Status::FAILURE;
    }

    /**
     * @return int
     */
    private function performAction()
    {
        try {
            $this->copyTemplateFile();
        } catch (TemplateCopyFailedException $exception) {
            return $this->failed($exception);
        }

        return Status::SUCCESS;
    }

    /**
     * @return int
     */
    private function copyTemplateFile()
    {
        $templateFile = realpath(__DIR__ . '/../template/.coveralls.yml');

        if (copy($templateFile, (string) $this->destDirectoryFilePath)) {
            return;
        }

        throw new TemplateCopyFailedException("Can not copy the files to the directory $this->destDirectoryPath.");
    }

}
