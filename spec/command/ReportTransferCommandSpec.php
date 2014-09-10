<?php

namespace coverallskit\spec\command;

use coverallskit\command\ReportTransferCommand;
use coverallskit\ReportUpLoaderInterface;
use coverallskit\entity\ReportInterface;
use coverallskit\ContextInterface;
use coverallskit\HelpException;
use coverallskit\RequireException;
use coverallskit\ConsoleWrapper;
use coverallskit\FailureException;
use Prophecy\Prophet;
use Prophecy\Argument;
use Zend\Console\Getopt;


describe('ReportTransferCommand', function() {
    describe('getUsageMessage', function() {
        before(function () {
            $this->prophet = new Prophet();

            $this->context = $this->prophet->prophesize(ContextInterface::class);
            $this->context->getScriptName()->shouldNotBeCalled();
            $this->context->getCommandName()->shouldNotBeCalled();
            $this->context->getCommandArguments()->shouldNotBeCalled();
            $this->context->getCommandOptions(Argument::type('array'))->will(function(array $args) {
                $options = new Getopt($args[0], ['-c', 'foo.yml']);
                $options->parse();
                return $options;
            });

            $this->command = new ReportTransferCommand($this->context->reveal());
        });
        it('return help message', function() {
            expect($this->command->getUsageMessage())->toBeA('string');
        });
        it('check mock object expectations', function() {
            $this->prophet->checkPredictions();
        });
    });

    describe('execute', function() {
        before(function () {
            $this->prophet = new Prophet();

            $this->context = $this->prophet->prophesize(ContextInterface::class);
            $this->context->getScriptName()->shouldNotBeCalled();
            $this->context->getCommandName()->shouldNotBeCalled();
            $this->context->getCommandArguments()->shouldNotBeCalled();
            $this->context->getCommandOptions(Argument::type('array'))->will(function(array $args) {
                $options = new Getopt($args[0], ['-c', './coveralls.yml']);
                $options->parse();
                return $options;
            });

            $this->reportTransfer = $this->prophet->prophesize(ReportUpLoaderInterface::class);
            $this->reportTransfer->setClient()->shouldNotBeCalled();
            $this->reportTransfer->getClient()->shouldNotBeCalled();
            $this->reportTransfer->upload(Argument::type(ReportInterface::class));

            $this->command = new ReportTransferCommand($this->context->reveal());
            $this->command->setReportTransfer($this->reportTransfer->reveal());
            $this->command->execute(new ConsoleWrapper());
        });
        it('transfer report', function() {
            $this->prophet->checkPredictions();
        });

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

                $this->command = new ReportTransferCommand($this->context->reveal());
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

        context('unuse --config or -c option', function() {
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

                $this->command = new ReportTransferCommand($this->context->reveal());
            });
            it('throw RequireException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(RequireException::class);
            });
            it('check mock object expectations', function() {
                $this->prophet->checkPredictions();
            });
        });

        context('configration file not exists', function() {
            before(function () {
                $this->prophet = new Prophet();

                $this->context = $this->prophet->prophesize(ContextInterface::class);
                $this->context->getScriptName()->shouldNotBeCalled();
                $this->context->getCommandName()->shouldNotBeCalled();
                $this->context->getCommandArguments()->shouldNotBeCalled();
                $this->context->getCommandOptions(Argument::type('array'))->will(function(array $args) {
                    $options = new Getopt($args[0], ['-c', 'not_found.yml']);
                    $options->parse();
                    return $options;
                });

                $this->command = new ReportTransferCommand($this->context->reveal());
            });
            it('throw FailureException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(FailureException::class);
            });
            it('check mock object expectations', function() {
                $this->prophet->checkPredictions();
            });
        });
    });

});
