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

use ReflectionClass;

/**
 * Class CommandFactory
 * @package coverallskit
 */
class CommandFactory
{

    /**
     * @var array
     */
    private $commands;


    public function __construct()
    {
        $this->commands = [
            'transfer' => 'cli\ReportTransferCommand'
        ];
    }

    /**
     * @param Context $context
     * @return object
     */
    public function createFromContext(Context $context)
    {
        $commandName = $context->getCommandName();

        if (isset($this->commands[$commandName]) === false) {
        }

        $className = $this->commands[$commandName];
        $classReflection = new ReflectionClass($className);

        $command = $classReflection->newInstanceArgs([ $context ]);

        return $command;
    }

}
