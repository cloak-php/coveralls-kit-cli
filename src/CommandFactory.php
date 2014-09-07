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
use PhpCollection\Map;

/**
 * Class CommandFactory
 * @package coverallskit
 */
class CommandFactory implements CommandFactoryInterface
{

    /**
     * @var \PhpCollection\Map
     */
    private $commands;

    /**
     * @param array $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = new Map($commands);
    }

    /**
     * @param ContextInterface $context
     * @return \coverallskit\CommandInterface
     */
    public function createFromContext(ContextInterface $context)
    {
        $commandName = $context->getCommandName();

        if ($this->commands->containsKey($commandName) === false) {
            throw new CommandNotFoundException("'$commandName' command does not exist");
        }

        $className = $this->commands->get($commandName);
        $classReflection = new ReflectionClass($className->get());

        $command = $classReflection->newInstanceArgs([ $context ]);

        return $command;
    }

}
