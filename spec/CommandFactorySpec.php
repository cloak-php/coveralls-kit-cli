<?php

namespace coverallskit\spec;

use coverallskit\CommandFactory;
use coverallskit\Context;
use coverallskit\CommandInterface;
use coverallskit\spec\fixture\FixtureBuildCommand;


describe('CommandFactory', function() {
    before(function () {
        $this->factory = new CommandFactory([
            'build' => FixtureBuildCommand::class
        ]);
    });
    describe('createFromContext', function() {
        before(function () {
            $context = new Context(['main', 'build']);
            $this->command = $this->factory->createFromContext($context);
        });
        it('return command', function() {
            expect($this->command)->toBeAnInstanceOf(CommandInterface::class);
        });
    });
});
