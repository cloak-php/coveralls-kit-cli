<?php

/**
 * This file is part of CoverallsKit.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace coverallskit\command;

use coverallskit\AbstractCommand;
use coverallskit\ConsoleWrapperInterface;
use coverallskit\HelpException;
use coverallskit\RequireException;
use coverallskit\FailureException;


/**
 * Class ReportTransferCommand
 * @package coverallskit\command
 */
class ReportTransferCommand extends AbstractCommand
{

    /**
     * @var array
     */
    protected $rules = [
        'config|c=s' => 'Read configuration from YAML file.',
        'debug|d-s' => 'Only generate a report file.',
        'help|h-s' => 'Prints this usage information.',
    ];

    public function execute(ConsoleWrapperInterface $console)
    {
        if ($this->options->help) {
            throw new HelpException($this->getUsageMessage());
        };

        if (empty($this->options->config)) {
            throw new RequireException('config option is required.');
        }

        $configrationPath = getcwd() . $this->options->config;

        if (file_exists($configrationPath) === false) {
            throw new FailureException("File $configrationPath is not found");
        }

    }

}
