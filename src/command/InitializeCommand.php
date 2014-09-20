<?php

/**
 * This file is part of CoverallsKit.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace coverallskit\command;

use coverallskit\AbstractCommand;
use coverallskit\ConsoleWrapperInterface;
use coverallskit\FailureException;
use Ulrichsg\Getopt\Getopt;
use Ulrichsg\Getopt\Option;


/**
 * Class InitializeCommand
 * @package coverallskit\command
 */
class InitializeCommand extends AbstractCommand
{

    /**
     * @var string
     */
    protected $summaryMessage = 'Create a coveralls.yml file.';

    /**
     * @return \Ulrichsg\Getopt\Getopt;
     */
    protected function getOptions()
    {
        $projectDirectory = new Option('p', 'project-directory', Getopt::OPTIONAL_ARGUMENT);
        $projectDirectory->setDescription('Initializes the directory.');

        $help = new Option('h', 'help', Getopt::OPTIONAL_ARGUMENT);
        $help->setDescription('Prints this usage information.');

        $options = new Getopt([$projectDirectory, $help]);
        return $options;
    }

    /**
     * @param ConsoleWrapperInterface $console
     */
    protected function perform(ConsoleWrapperInterface $console)
    {
        $destDirectory = $this->getDestDirectory();

        $templateFile = realpath(__DIR__ . '/../../template/.coveralls.yml');
        $destFile = $destDirectory . '.coveralls.yml';

        if (file_exists($destDirectory) === false) {
            throw new FailureException("$destDirectory does not exist.");
        }

        if (copy($templateFile, $destFile)) {
            return;
        }

        throw new FailureException("Can not copy the files to the directory $destDirectory.");
    }

    /**
     * @return string
     */
    private function getDestDirectory()
    {
        $currentWorkDirectory = getcwd();
        $destDirectory = $currentWorkDirectory;

        $projectDirectory = $this->options->getOption('project-directory');

        if (is_null($projectDirectory)) {
            return $destDirectory;
        }

        $projectDirectory = preg_replace('/^\/(.+)/', '$1', $projectDirectory);
        $destDirectory .= DIRECTORY_SEPARATOR . $projectDirectory . DIRECTORY_SEPARATOR;

        return $destDirectory;
    }

}
