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

/**
 * Class HelpCommand
 * @package coverallskit\command
 */
class HelpCommand extends AbstractCommand
{

    /**
     * @param ConsoleWrapperInterface $console
     */
    protected function perform(ConsoleWrapperInterface $console)
    {
        $commands = [
            'init' => InitializeCommand::class,
            'transfer' => ReportTransferCommand::class
        ];

        $console->writeMessage($this->getUsageMessage());
        $console->writeMessage('The most commonly used coveralls commands are:');

        foreach ($commands as $key => $commandName) {
            $command = new $commandName($this->context);
            $prefix = '  ' . str_pad($key, 10, ' ');
            $console->writeMessage($prefix . $command->getSummaryMessage());
        }
    }

}
