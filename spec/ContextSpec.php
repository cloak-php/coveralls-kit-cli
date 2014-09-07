<?php

namespace coverallskit\spec;

use coverallskit\Context;
use Zend\Stdlib\Parameters;
use Zend\Console\Getopt;

describe('Context', function() {
    before(function () {
        $this->context = new Context(['main', 'create', '-c', 'foo.yml']);
    });
    describe('getScriptName', function() {
        it('return script name', function() {
            expect($this->context->getScriptName())->toEqual('main');
        });
    });
    describe('getCommandName', function() {
        it('return command name', function() {
            expect($this->context->getCommandName())->toEqual('create');
        });
    });
    describe('getCommandArguments', function() {
        before(function () {
            $this->arguments = new Parameters(['-c', 'foo.yml']);
            $this->actualArguments = $this->context->getCommandArguments();
        });
        it('return command arguments', function() {
            expect($this->actualArguments->toArray())->toEqual($this->arguments->toArray());
        });
    });
    describe('getCommandOptions', function() {
        before(function () {
            $this->options = $this->context->getCommandOptions([
                'config|c=s' => 'help message'
            ]);
        });
        it('return command options', function() {
            expect($this->options)->toBeAnInstanceOf(Getopt::class);
        });
    });
});
