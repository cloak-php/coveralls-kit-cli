<?php

namespace coverallskit\spec;

use coverallskit\CommandFactoryInterface;
use coverallskit\ConsoleWrapperInterface;
use coverallskit\ContextInterface;
use coverallskit\CommandInterface;
use coverallskit\HelpException;
use coverallskit\Application;
use Prophecy\Prophet;
use Prophecy\Argument;


describe('Application', function() {
    describe('run', function() {
        context('have help option', function() {
            before(function () {
                $this->prophet = new Prophet();

                $this->helpMessage = 'command help message!!';
                $this->helpException = new HelpException($this->helpMessage);

                $this->consoleWrapper = $this->prophet->prophesize(ConsoleWrapperInterface::class);
                $this->consoleWrapper->writeMessage(Argument::exact($this->helpMessage))->shouldBeCalled();
                $this->consoleWrapper->writeSuccessMessage()->shouldNotBeCalled();
                $this->consoleWrapper->writeFailureMessage()->shouldNotBeCalled();

                $this->command = $this->prophet->prophesize(CommandInterface::class);
                $this->command->execute($this->consoleWrapper->reveal())
                    ->willThrow($this->helpException);

                $this->commandFactory = $this->prophet->prophesize(CommandFactoryInterface::class);
                $this->commandFactory->createFromContext(Argument::type(ContextInterface::class))
                    ->willReturn($this->command->reveal());

                $this->application = new Application(
                    $this->commandFactory->reveal(),
                    $this->consoleWrapper->reveal()
                );

                $this->application->run(['build', '-h']);
            });
            it('display help message', function() {
                $this->prophet->checkPredictions();
            });
        });

    });
});
