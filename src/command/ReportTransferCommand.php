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

use coverallskit\ReportTransferAwareTrait;
use coverallskit\ReportTransferAwareInterface;
use coverallskit\Configuration;
use coverallskit\ReportBuilder;
use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;


/**
 * Class ReportTransferCommand
 * @package coverallskit\command
 */
class ReportTransferCommand implements ReportTransferAwareInterface
{

    use ReportTransferAwareTrait;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var Stdio
     */
    private $stdio;

    /**
     * @param Context $context
     * @param Stdio $stdio
     */
    public function __construct(Context $context, Stdio $stdio)
    {
        $this->context = $context;
        $this->stdio = $stdio;
    }

    /**
     * @param $configFile
     * @return int
     */
    public function __invoke($configFile)
    {
        $configurationPath = getcwd() . DIRECTORY_SEPARATOR . $configFile;

        if (file_exists($configurationPath) === false) {
            $this->stdio->errln("File $configurationPath is not found");
            return Status::FAILURE;
        }

        $configuration = Configuration::loadFromFile($configurationPath);
        $reportBuilder = ReportBuilder::fromConfiguration($configuration);

        $report = $reportBuilder->build();
        $report->save();

        $options = $this->context->getopt(['d::']);

        if ($options->get('-d')) {
            return Status::SUCCESS;
        }

        $report->setReportTransfer($this->getReportTransfer());
        $report->upload();

        return Status::SUCCESS;
    }

}
