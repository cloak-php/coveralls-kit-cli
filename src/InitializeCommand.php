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


    private $destDirectoryPath;
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
        $this->prepare($projectDirectory);
        $templateFile = realpath(__DIR__ . '/../template/.coveralls.yml');

        if (file_exists((string) $this->destDirectoryPath) === false) {
            $this->stdio->errln("$this->destDirectoryPath does not exist.");
            return Status::FAILURE;
        }

        if (copy($templateFile, (string) $this->destDirectoryFilePath)) {
            return Status::SUCCESS;
        }

        $this->stdio->errln("Can not copy the files to the directory $this->destDirectoryPath.");
        return Status::FAILURE;
    }

    /**
     * @param string|null $projectDirectory
     */
    private function prepare($projectDirectory = null)
    {
        $configFilePath = RelativePath::fromString('.coveralls.yml');
        $destDirectoryPath = PathFactory::instance()->create(getcwd());

        if (is_null($projectDirectory)) {
            $this->destDirectoryPath = $destDirectoryPath;
            $this->destDirectoryFilePath = $destDirectoryPath->resolve($configFilePath);
            return;
        }

        $projectDirectoryPath = RelativePath::fromString($projectDirectory);
        $absoluteDestDirectoryPath = $destDirectoryPath->resolve($projectDirectoryPath);

        $this->destDirectoryPath = $absoluteDestDirectoryPath;
        $this->destDirectoryFilePath = $absoluteDestDirectoryPath->resolve($configFilePath);
    }

}
