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
use Ulrichsg\Getopt\Getopt;
use Ulrichsg\Getopt\Option;

/**
 * Class ReportTransferCommand
 * @package coverallskit\command
 */
class ReportTransferCommand extends AbstractCommand implements ReportTransferAwareInterface
{

    use ReportTransferAwareTrait;

    /**
     * @var string
     */
    protected $summaryMessage = 'Send to coveralls the report file.';

    /**
     * @return \Ulrichsg\Getopt\Getopt
     */
    protected function getOptions()
    {
        $configuration = new Option('c', 'config', Getopt::REQUIRED_ARGUMENT);
        $configuration->setDescription('Read configuration from YAML file.');

        $debug = new Option('d', 'debug', Getopt::OPTIONAL_ARGUMENT);
        $debug->setDescription('Only generate a report file.');

        $help = new Option('h', 'help', Getopt::OPTIONAL_ARGUMENT);
        $help->setDescription('Prints this usage information.');

        $options = new Getopt([$configuration, $debug, $help]);
        return $options;
    }

    /**
     * @param ConsoleWrapperInterface $console
     */
    protected function perform(ConsoleWrapperInterface $console)
    {
        $config = $this->options->getOption('config');

        if (empty($config)) {
            throw new RequireException('config option is required.');
        }

        $configurationPath = getcwd() . DIRECTORY_SEPARATOR . $config;

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
