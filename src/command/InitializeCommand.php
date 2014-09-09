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


/**
 * Class InitializeCommand
 * @package coverallskit\command
 */
class InitializeCommand extends AbstractCommand
{

    /**
     * @var array
     */
    protected $rules = [
        'help|h-s' => 'Prints this usage information.',
    ];

    /**
     * @param ConsoleWrapperInterface $console
     */
    protected function perform(ConsoleWrapperInterface $console)
    {
        $currentWorkDirectory = getcwd();

        $templateFile = realpath(__DIR__ . '/../../template/.coveralls.yml');
        $destFile = $currentWorkDirectory . DIRECTORY_SEPARATOR . '.coveralls.yml';

        if (copy($templateFile, $destFile)) {
            return;
        }

        throw new FailureException("Can not copy the files to the directory $currentWorkDirectory.");
    }

}
