<?php

namespace coverallskit\spec;

use coverallskit\ConsoleWrapper;
use Zend\Console\Console;
use Zend\Console\ColorInterface as Color;


describe('ConsoleWrapper', function() {
    before(function () {
        $this->console = Console::getInstance();
        $this->consoleWrapper = new ConsoleWrapper();
    });
    describe('writeMessage', function() {
        it('output message', function() {
            expect(function() {
                $this->consoleWrapper->writeMessage('hello');
            })->toPrint("hello\n");
        });
    });
    describe('writeFailureMessage', function() {
        before(function () {
            $this->message = $this->console->colorize("hello", Color::RED) . "\n";
        });
        it('output a message in red', function() {
            expect(function() {
                $this->consoleWrapper->writeFailureMessage('hello');
            })->toPrint($this->message);
        });
    });
    describe('writeSuccessMessage', function() {
        before(function () {
            $this->message = $this->console->colorize("hello", Color::GREEN) . "\n";
        });
        it('output a message in green', function() {
            expect(function() {
                $this->consoleWrapper->writeSuccessMessage('hello');
            })->toPrint($this->message);
        });
    });
});
