<?php

namespace coverallskit\spec\command;

use coverallskit\command\ReportTransferCommand;
use coverallskit\ContextInterface;
use Prophecy\Prophet;
use Prophecy\Argument;
use Zend\Console\Getopt;

describe('ReportTransferCommand', function() {
    describe('getUsageMessage', function() {
        before(function () {
            $this->prophet = new Prophet();

            $this->context = $this->prophet->prophesize(ContextInterface::class);
            $this->context->getScriptName()->shouldNotBeCalled();
            $this->context->getCommandName()->shouldNotBeCalled();
            $this->context->getCommandArguments()->shouldNotBeCalled();
            $this->context->getCommandOptions(Argument::type('array'))->will(function(array $args) {
                $options = new Getopt($args[0], ['-c', 'foo.yml']);
                $options->parse();
                return $options;
            });

            $this->command = new ReportTransferCommand($this->context->reveal());
        });
        it('return help message', function() {
            expect($this->command->getUsageMessage())->toBeA('string');
        });
        it('check mock object expectations', function() {
            $this->prophet->checkPredictions();
        });
    });
});
