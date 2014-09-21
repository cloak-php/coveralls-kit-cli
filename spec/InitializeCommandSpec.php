<?php

namespace coverallskit\spec;

use coverallskit\InitializeCommand;
use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\CliFactory;
use Aura\Cli\Status;

describe('InitializeCommand', function() {
    describe('__invoke', function() {
        before(function () {
            $this->destFile = __DIR__ . '/tmp/.coveralls.yml';

            $this->factory = new CliFactory();
            $this->stdio = $this->factory->newStdio();
            $this->context = $this->factory->newContext([]);
            $this->command = new InitializeCommand($this->context, $this->stdio);
        });
        context('when specify a project directory', function() {
            before(function () {
                $this->status = $this->command('spec/tmp');
            });
            after(function () {
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
            before(function () {
                $this->destFile = getcwd() . '/.coveralls.yml';
                $this->status = $this->command();
            });
            after(function () {
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
            before(function () {
                $this->status = $this->command('tmp/tmp');
            });
            it('return Status::FAILURE', function() {
                expect($this->status)->toEqual(Status::FAILURE);
            });
        });
    });
});
