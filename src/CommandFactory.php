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
use coverallskit\command\CommandNotFoundException;

/**
 * Class CommandFactory
 * @package coverallskit
 */
class CommandFactory implements CommandFactoryInterface
{

    /**
     * @var array
     */
    private $commands;

    /**
     * @param array $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param ContextInterface $context
     * @return \coverallskit\CommandInterface
     */
    public function createFromContext(ContextInterface $context)
    {
        $commandName = $context->getCommandName();

        if (isset($this->commands[$commandName]) === false) {
            throw new CommandNotFoundException("'$commandName' command does not exist");
        }

        $className = $this->commands[$commandName];
        $classReflection = new ReflectionClass($className);

        $command = $classReflection->newInstanceArgs([ $context ]);

        return $command;
    }

}
