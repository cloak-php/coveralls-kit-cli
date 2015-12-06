<?php

namespace coverallskit\spec;

use coverallskit\ReportTransferCommand;
use coverallskit\ReportTransfer;
use coverallskit\entity\ReportEntity;
use Prophecy\Prophet;
use Prophecy\Argument;
use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\CliFactory;
use Aura\Cli\Status;

describe(ReportTransferCommand::class, function() {
    beforeEach(function () {
        $this->rootDirectory = realpath(__DIR__ . '/../');
        $this->tmpDirectory = $this->rootDirectory . '/spec/tmp/clover.xml';
        $this->fixtureDirectory = $this->rootDirectory . '/spec/fixtures/';

        $this->factory = new CliFactory();
        $this->stdio = $this->factory->newStdio();
    });

    describe('__invoke', function() {
        context('when default', function() {
            beforeEach(function () {
                $content = file_get_contents($this->fixtureDirectory . 'clover.xml');
                $content = sprintf($content, $this->rootDirectory, $this->rootDirectory);
                file_put_contents($this->tmpDirectory, $content);

                $this->prophet = new Prophet();

                $this->reportTransfer = $this->prophet->prophesize(ReportTransfer::class);
                $this->reportTransfer->setClient()->shouldNotBeCalled();
                $this->reportTransfer->getClient()->shouldNotBeCalled();
                $this->reportTransfer->upload(Argument::type(ReportEntity::class))->shouldBeCalled();

                $this->context = $this->factory->newContext([
                    'argv' => [ '-c', 'spec/fixtures/coveralls.toml' ]
                ]);

                $this->command = new ReportTransferCommand($this->context, $this->stdio);
                $this->command->setReportTransfer($this->reportTransfer->reveal());

                $command = $this->command;
                $this->status = $command();
            });
            it('transfer report file', function() {
                $this->prophet->checkPredictions();
            });
            it('return Status::SUCCESS', function() {
                expect($this->status)->toEqual(Status::SUCCESS);
            });
        });
        context('when use debug option', function() {
            beforeEach(function () {
                $this->prophet = new Prophet();

                $this->reportTransfer = $this->prophet->prophesize(ReportTransfer::class);
                $this->reportTransfer->setClient()->shouldNotBeCalled();
                $this->reportTransfer->getClient()->shouldNotBeCalled();
                $this->reportTransfer->upload()->shouldNotBeCalled();

                $this->context = $this->factory->newContext([
                    'argv' => [ '-d', '--config=spec/fixtures/coveralls.toml' ]
                ]);
                $this->command = new ReportTransferCommand($this->context, $this->stdio);
                $this->command->setReportTransfer($this->reportTransfer->reveal());

                $command = $this->command;
                $this->status = $command();
            });
            it('only generate report file', function() {
                $this->prophet->checkPredictions();
            });
            it('return Status::SUCCESS', function() {
                expect($this->status)->toEqual(Status::SUCCESS);
            });
        });
        context('when configration file not exists', function() {
            beforeEach(function () {
                $this->context = $this->factory->newContext([
                    'argv' => [ '--debug', '--config=not_found.toml' ]
                ]);

                $this->command = new ReportTransferCommand($this->context, $this->stdio);
                $command = $this->command;

                $this->status = $command();
            });
            it('return Status::FAILURE', function() {
                expect($this->status)->toEqual(Status::FAILURE);
            });
        });
    });

});
