<?php

namespace coverallskit\spec\command;

use coverallskit\command\InitializeCommand;
//use coverallskit\Context;
//use coverallskit\HelpException;
//use coverallskit\ConsoleWrapper;/
//use coverallskit\FailureException;
use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\CliFactory;
use Aura\Cli\Status;

describe('InitializeCommand', function() {
    describe('__invoke', function() {
        before(function () {
            $this->destFile = __DIR__ . '/../tmp/.coveralls.yml';

            $this->factory = new CliFactory();
            $this->stdio = $this->factory->newStdio();
            $this->context = $this->factory->newContext();
            $this->command = new InitializeCommand($this->context, $this->stdio);
        });
        context('when default', function() {
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
        context('when directory not found', function() {
            before(function () {
                $this->status = $this->command('spec/tmp/tmp');
            });
            it('return Status::FAILURE', function() {
                expect($this->status)->toEqual(Status::FAILURE);
            });
        });
    });
});
