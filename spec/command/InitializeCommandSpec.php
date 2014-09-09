<?php

namespace coverallskit\spec\command;

use coverallskit\command\InitializeCommand;
use coverallskit\ContextInterface;
use coverallskit\HelpException;
use coverallskit\ConsoleWrapper;
use coverallskit\FailureException;
use Prophecy\Prophet;
use Prophecy\Argument;
use Zend\Console\Getopt;


describe('InitializeCommand', function() {
    describe('getUsageMessage', function() {
        before(function () {
            $this->prophet = new Prophet();

            $this->context = $this->prophet->prophesize(ContextInterface::class);
            $this->context->getScriptName()->shouldNotBeCalled();
            $this->context->getCommandName()->shouldNotBeCalled();
            $this->context->getCommandArguments()->shouldNotBeCalled();
            $this->context->getCommandOptions(Argument::type('array'))->will(function(array $args) {
                $options = new Getopt($args[0], []);
                $options->parse();
                return $options;
            });

            $this->command = new InitializeCommand($this->context->reveal());
        });
        it('return help message', function() {
            expect($this->command->getUsageMessage())->toBeA('string');
        });
        it('check mock object expectations', function() {
            $this->prophet->checkPredictions();
        });
    });

    describe('execute', function() {
        context('use --help or -h option', function() {
            before(function () {
                $this->prophet = new Prophet();

                $this->context = $this->prophet->prophesize(ContextInterface::class);
                $this->context->getScriptName()->shouldNotBeCalled();
                $this->context->getCommandName()->shouldNotBeCalled();
                $this->context->getCommandArguments()->shouldNotBeCalled();
                $this->context->getCommandOptions(Argument::type('array'))->will(function(array $args) {
                    $options = new Getopt($args[0], ['-h']);
                    $options->parse();
                    return $options;
                });

                $this->command = new InitializeCommand($this->context->reveal());
            });
            it('throw HelpException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(HelpException::class);
            });
            it('check mock object expectations', function() {
                $this->prophet->checkPredictions();
            });
        });
    });

});
