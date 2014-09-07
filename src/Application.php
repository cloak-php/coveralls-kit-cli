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

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface as Color;


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
     * @var CommandFactory
     */
    private $commandFactory;


    /**
     * @param CommandFactory $factory
     */
    public function __construct(CommandFactory $commandFactory, ConsoleWrapperInterface $console)
    {
        $this->commandFactory = $commandFactory;
        $this->console = $console;
    }

    /**
     * @param array $argv
     * @param array $env
     */
    public function run(array $argv, array $env = [])
    {
        $environment = $env ?: $_ENV;
        $context = new Context($argv, $environment);

        $command = $this->commandFactory->createFromContext($context);

        try {
            $command->execute($this->console);
            $this->success();
        } catch (HelpException $exception) {
            $this->help($exception);
            return;
        } catch (FailureException $exception) {
            $this->failure($exception);
            throw $exception;
        }
    }

    protected function failure(FailureException $exception)
    {
        $message = sprintf("Failure:\n    %s", $exception->getMessage());
        $this->console->writeFailureMessage($message);
    }

    protected function success()
    {
        $this->console->writeSuccessMessage('was successful.');
    }

    protected function help(HelpException $exception)
    {
        $this->console->writeMessage($exception->getMessage());
    }

}
