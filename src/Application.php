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
use coverallskit\command\HelpCommand;


/**
 * Class Application
 * @package coverallskit
 */
class Application
{

    /**
     * @var ConsoleWrapperInterface
     */
    private $console;

    /**
     * @var CommandFactoryInterface
     */
    private $commandFactory;

    /**
     * @param CommandFactoryInterface $commandFactory
     * @param ConsoleWrapperInterface $console
     */
    public function __construct(CommandFactoryInterface $commandFactory, ConsoleWrapperInterface $console)
    {
        $this->console = $console;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @param array $argv
     * @param array $env
     */
    public function run(array $argv, array $env = [])
    {
        $environment = $env ?: $_ENV;
        $context = new Context($argv, $environment);

        try {
            $command = $this->commandFactory->createFromContext($context);
        } catch (CommandNotFoundException $exception) {
            $command = new HelpCommand($context);
        }

        try {
            $command->execute($this->console);
            $this->success($context);
        } catch (HelpException $exception) {
            $this->help($exception);
            return;
        } catch (FailureException $exception) {
            $this->failure($exception);
            throw $exception;
        }
    }

    /**
     * @param FailureException $exception
     */
    protected function failure(FailureException $exception)
    {
        $message = sprintf("Failure:\n    %s", $exception->getMessage());
        $this->console->writeFailureMessage($message);
    }

    /**
     * @param ContextInterface $context
     */
    protected function success(ContextInterface $context)
    {
        $message = sprintf("Execution of '%s' command was successful.",
            $context->getCommandName());

        $this->console->writeSuccessMessage($message);
    }

    /**
     * @param HelpException $exception
     */
    protected function help(HelpException $exception)
    {
        $this->console->writeMessage($exception->getMessage());
    }

}
