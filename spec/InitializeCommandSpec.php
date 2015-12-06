<?php

namespace coverallskit\spec;

use coverallskit\InitializeCommand;
use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\CliFactory;
use Aura\Cli\Status;

describe(InitializeCommand::class, function() {
    describe('__invoke', function() {
        beforeEach(function () {
            $this->destFile = __DIR__ . '/tmp/.coveralls.toml';

            $this->factory = new CliFactory();
            $this->stdio = $this->factory->newStdio();
            $this->context = $this->factory->newContext([]);
            $this->command = new InitializeCommand($this->context, $this->stdio);
        });
        context('when specify a project directory', function() {
            beforeEach(function () {
                $command = $this->command;
                $this->status = $command('spec/tmp');
            });
            afterEach(function () {
                unlink($this->destFile);
            });
            it('copy template file', function() {
                expect(file_exists($this->destFile))->toBeTrue();
            });
            it('return Status::SUCCESS', function() {
                expect($this->status)->toEqual(Status::SUCCESS);
            });
        });

        context('when not specify a project directory', function() {
            beforeEach(function () {
                $this->destFile = getcwd() . '/.coveralls.toml';

                $command = $this->command;
                $this->status = $command();
            });
            afterEach(function () {
                unlink($this->destFile);
            });
            it('copy template file', function() {
                expect(file_exists($this->destFile))->toBeTrue();
            });
            it('return Status::SUCCESS', function() {
                expect($this->status)->toEqual(Status::SUCCESS);
            });
        });

        context('when directory not found', function() {
            beforeEach(function () {
                $command = $this->command;
                $this->status = $command('tmp/tmp');
            });
            it('return Status::FAILURE', function() {
                expect($this->status)->toEqual(Status::FAILURE);
            });
        });

        context('when directory not writable', function() {
            beforeEach(function () {
                $this->destReadOnlyDirectory = __DIR__ . '/tmp/readonly';
                mkdir($this->destReadOnlyDirectory);
                chmod($this->destReadOnlyDirectory, 644);

                $command = $this->command;
                $this->status = $command('spec/tmp/readonly');

                chmod($this->destReadOnlyDirectory, 777);
                rmdir($this->destReadOnlyDirectory);
            });
            it('return Status::FAILURE', function() {
                expect($this->status)->toEqual(Status::FAILURE);
            });
        });

    });
});
