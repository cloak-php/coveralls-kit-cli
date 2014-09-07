<?php

namespace coverallskit\spec;

use coverallskit\CommandFactory;
use coverallskit\Context;
use coverallskit\CommandInterface;
use coverallskit\spec\fixture\FixtureBuildCommand;
use coverallskit\command\CommandNotFoundException;

describe('CommandFactory', function() {
    before(function () {
        $this->factory = new CommandFactory([
            'build' => FixtureBuildCommand::class
        ]);
    });
    describe('createFromContext', function() {
        context('command exists', function() {
            before(function () {
                $context = new Context(['main', 'build']);
                $this->command = $this->factory->createFromContext($context);
            });
            it('return command', function() {
                expect($this->command)->toBeAnInstanceOf(CommandInterface::class);
            });
        });
        context('command not exists', function() {
            it('return command', function() {
                expect(function() {
                    $context = new Context(['main', 'debug']);
                    $this->factory->createFromContext($context);
                })->toThrow(CommandNotFoundException::class);
            });
        });
    });
});
