<?php

namespace coverallskit\spec\command;

use coverallskit\command\ReportTransferCommand;
use coverallskit\ReportTransferInterface;
use coverallskit\entity\ReportInterface;
use coverallskit\HelpException;
use coverallskit\RequireException;
use coverallskit\ConsoleWrapper;
use coverallskit\FailureException;
use Prophecy\Prophet;
use Prophecy\Argument;
use coverallskit\Context;


describe('ReportTransferCommand', function() {
    describe('getSummaryMessage', function() {
        before(function () {
            $this->context = new Context([]);
            $this->command = new ReportTransferCommand($this->context);
        });
        it('return summary message', function() {
            expect($this->command->getSummaryMessage())->toEqual('Send to coveralls the report file.');
        });
    });
    describe('getUsageMessage', function() {
        before(function () {
            $this->context = new Context([]);
            $this->command = new ReportTransferCommand($this->context);
        });
        it('return help message', function() {
            expect($this->command->getUsageMessage())->toBeA('string');
        });
    });

    describe('execute', function() {
        before(function () {
            $this->rootDirectory = realpath(__DIR__ . '/../../');
            $this->tmpDirectory = $this->rootDirectory . '/spec/tmp/clover.xml';
            $this->fixtureDirectory = $this->rootDirectory . '/spec/fixtures/';

            $content = file_get_contents($this->fixtureDirectory . 'clover.xml');
            $content = sprintf($content, $this->rootDirectory, $this->rootDirectory);
            file_put_contents($this->tmpDirectory, $content);

            $this->prophet = new Prophet();

            $this->context = new Context(['bin/coverallskit', 'transfer', '-c', 'spec/fixtures/coveralls.yml']);

            $this->reportTransfer = $this->prophet->prophesize(ReportTransferInterface::class);
            $this->reportTransfer->setClient()->shouldNotBeCalled();
            $this->reportTransfer->getClient()->shouldNotBeCalled();
            $this->reportTransfer->upload(Argument::type(ReportInterface::class))->shouldBeCalled();

            $this->command = new ReportTransferCommand($this->context);
            $this->command->setReportTransfer($this->reportTransfer->reveal());
            $this->command->execute(new ConsoleWrapper());
        });
        it('transfer report file', function() {
            $this->prophet->checkPredictions();
        });
        context('when use debug option', function() {
            before(function () {
                $this->prophet = new Prophet();

                $this->reportTransfer = $this->prophet->prophesize(ReportTransferInterface::class);
                $this->reportTransfer->setClient()->shouldNotBeCalled();
                $this->reportTransfer->getClient()->shouldNotBeCalled();
                $this->reportTransfer->upload()->shouldNotBeCalled();

                $this->context = new Context(['bin/coverallskit', 'transfer', '-c', 'spec/fixtures/coveralls.yml', '-d']);

                $this->command = new ReportTransferCommand($this->context);
                $this->command->setReportTransfer($this->reportTransfer->reveal());
                $this->command->execute(new ConsoleWrapper());
            });
            it('only generate report file', function() {
                $this->prophet->checkPredictions();
            });
        });
        context('when use --help or -h option', function() {
            before(function () {
                $this->context = new Context(['bin/coverallskit', 'transfer', '-h']);
                $this->command = new ReportTransferCommand($this->context);
            });
            it('throw HelpException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(HelpException::class);
            });
        });
        context('when unuse --config or -c option', function() {
            before(function () {
                $this->context = new Context(['bin/coverallskit', 'transfer']);
                $this->command = new ReportTransferCommand($this->context);
            });
            it('throw RequireException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(RequireException::class);
            });
        });
        context('when configration file not exists', function() {
            before(function () {
                $this->context = new Context(['bin/coverallskit', 'transfer', '-c', 'not_found.yml']);
                $this->command = new ReportTransferCommand($this->context);
            });
            it('throw FailureException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(FailureException::class);
            });
        });
    });

});
