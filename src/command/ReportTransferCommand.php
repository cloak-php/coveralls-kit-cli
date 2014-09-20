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
use coverallskit\ReportTransferAwareTrait;
use coverallskit\ReportTransferAwareInterface;
use coverallskit\RequireException;
use coverallskit\FailureException;
use coverallskit\Configuration;
use coverallskit\ReportBuilder;


/**
 * Class ReportTransferCommand
 * @package coverallskit\command
 */
class ReportTransferCommand extends AbstractCommand implements ReportTransferAwareInterface
{

    use ReportTransferAwareTrait;

    /**
     * @var array
     */
    protected $rules = [
        'config|c=s' => 'Read configuration from YAML file.',
        'debug|d-s' => 'Only generate a report file.',
        'help|h-s' => 'Prints this usage information.',
    ];

    /**
     * @param ConsoleWrapperInterface $console
     */
    protected function perform(ConsoleWrapperInterface $console)
    {
        if (empty($this->options->config)) {
            throw new RequireException('config option is required.');
        }

        $configurationPath = getcwd() . DIRECTORY_SEPARATOR . $this->options->config;

        if (file_exists($configurationPath) === false) {
            throw new FailureException("File $configurationPath is not found");
        }

        $configuration = Configuration::loadFromFile($configurationPath);
        $reportBuilder = ReportBuilder::fromConfiguration($configuration);

        $report = $reportBuilder->build();
        $report->save();

        if ($this->options->getOption('debug')) {
            return;
        }

        $report->setReportTransfer($this->getReportTransfer());
        $report->upload();
    }

}
