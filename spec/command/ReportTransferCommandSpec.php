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
//use coverallskit\Context;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\CliFactory;
use Aura\Cli\Status;


describe('ReportTransferCommand', function() {
    before(function () {
        $this->rootDirectory = realpath(__DIR__ . '/../../');
        $this->tmpDirectory = $this->rootDirectory . '/spec/tmp/clover.xml';
        $this->fixtureDirectory = $this->rootDirectory . '/spec/fixtures/';

        $this->factory = new CliFactory();
        $this->stdio = $this->factory->newStdio();
    });

    describe('__invoke', function() {
        before(function () {
            $content = file_get_contents($this->fixtureDirectory . 'clover.xml');
            $content = sprintf($content, $this->rootDirectory, $this->rootDirectory);
            file_put_contents($this->tmpDirectory, $content);

            $this->prophet = new Prophet();

            $this->reportTransfer = $this->prophet->prophesize(ReportTransferInterface::class);
            $this->reportTransfer->setClient()->shouldNotBeCalled();
            $this->reportTransfer->getClient()->shouldNotBeCalled();
            $this->reportTransfer->upload(Argument::type(ReportInterface::class))->shouldBeCalled();

            $this->context = $this->factory->newContext();

            $this->command = new ReportTransferCommand($this->context, $this->stdio);
            $this->command->setReportTransfer($this->reportTransfer->reveal());
            $this->status = $this->command('spec/fixtures/coveralls.yml');
        });
        it('transfer report file', function() {
            $this->prophet->checkPredictions();
        });
        it('return Status::SUCCESS', function() {
            expect($this->status)->toEqual(Status::SUCCESS);
        });

        context('when use debug option', function() {
            before(function () {
                $this->prophet = new Prophet();

                $this->reportTransfer = $this->prophet->prophesize(ReportTransferInterface::class);
                $this->reportTransfer->setClient()->shouldNotBeCalled();
                $this->reportTransfer->getClient()->shouldNotBeCalled();
                $this->reportTransfer->upload()->shouldNotBeCalled();

                $this->context = $this->factory->newContext([
                    'argv' => ['-d']
                ]);

                $this->command = new ReportTransferCommand($this->context);
                $this->command->setReportTransfer($this->reportTransfer->reveal());
                $this->status = $this->command('spec/fixtures/coveralls.yml');
            });
            it('only generate report file', function() {
                $this->prophet->checkPredictions();
            });
            it('return Status::SUCCESS', function() {
                expect($this->status)->toEqual(Status::SUCCESS);
            });
        });

        context('when configration file not exists', function() {
            before(function () {
                $this->context = $this->factory->newContext();

                $this->command = new ReportTransferCommand($this->context, $this->stdio);
                $this->status = $this->command('not_found.yml');
            });
            it('return Status::FAILURE', function() {
                expect($this->status)->toEqual(Status::FAILURE);
            });
        });
    });

});
