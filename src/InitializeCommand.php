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
        $destDirectory = $this->getDestDirectory($projectDirectory);

        $templateFile = realpath(__DIR__ . '/../template/.coveralls.yml');
        $destFile = $destDirectory . '.coveralls.yml';

        if (file_exists($destDirectory) === false) {
            $this->stdio->errln("$destDirectory does not exist.");
            return Status::FAILURE;
        }

        if (copy($templateFile, $destFile)) {
            return Status::SUCCESS;
        }

        $this->stdio->errln("Can not copy the files to the directory $destDirectory.");
        return Status::FAILURE;
    }

    /**
     * @param string|null $projectDirectory
     * @return string
     */
    private function getDestDirectory($projectDirectory = null)
    {
        $currentWorkDirectory = getcwd();
        $destDirectory = $currentWorkDirectory;

        if (is_null($projectDirectory)) {
            return $destDirectory;
        }

        $projectDirectory = preg_replace('/^\/(.+)/', '$1', $projectDirectory);
        $destDirectory .= DIRECTORY_SEPARATOR . $projectDirectory . DIRECTORY_SEPARATOR;

        return $destDirectory;
    }

}
