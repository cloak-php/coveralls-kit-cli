<?php

namespace coverallskit\spec\command;

use coverallskit\command\InitializeCommand;
use coverallskit\Context;
use coverallskit\HelpException;
use coverallskit\ConsoleWrapper;
use coverallskit\FailureException;


describe('InitializeCommand', function() {
    describe('getSummaryMessage', function() {
        before(function () {
            $this->context = new Context([]);
            $this->command = new InitializeCommand($this->context);
        });
        it('return summary message', function() {
            expect($this->command->getSummaryMessage())->toEqual('Create a coveralls.yml file.');
        });
    });
    describe('getUsageMessage', function() {
        before(function () {
            $this->context = new Context([]);
            $this->command = new InitializeCommand($this->context);
        });
        it('return help message', function() {
            expect($this->command->getUsageMessage())->toBeA('string');
        });
    });
    describe('execute', function() {
        context('use --help or -h option', function() {
            before(function () {
                $this->context = new Context(['bin/coverallskit', 'init', '--help']);
                $this->command = new InitializeCommand($this->context);
            });
            it('throw HelpException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(HelpException::class);
            });
        });
        context('use --project-directory or -p option', function() {
            before(function () {
                $this->destFile = __DIR__ . '/../tmp/.coveralls.yml';
                $this->context = new Context(['bin/coverallskit', 'init', '-p', 'spec/tmp']);
                $this->command = new InitializeCommand($this->context);
                $this->command->execute(new ConsoleWrapper());
            });
            after(function () {
                unlink($this->destFile);
            });
            it('copy template file', function() {
                expect(file_exists($this->destFile))->toBeTrue();
            });
        });
        context('when directory not found', function() {
            before(function () {
                $this->destFile = __DIR__ . '/../tmp/.coveralls.yml';
                $this->context = new Context(['bin/coverallskit', 'init', '-p', 'spec/tmp/tmp']);
                $this->command = new InitializeCommand($this->context);
            });
            it('throw FailureException', function() {
                expect(function() {
                    $this->command->execute(new ConsoleWrapper());
                })->toThrow(FailureException::class);
            });
        });
    });
});
