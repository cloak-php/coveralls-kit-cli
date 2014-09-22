<?php

/**
 * This file is part of CoverallsKit.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace coverallskit;

use coverallskit\Configuration;
use coverallskit\entity\ReportInterface;
use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;
use Eloquent\Pathogen\Factory\PathFactory;
use Eloquent\Pathogen\RelativePath;
use Exception;


/**
 * Class ReportTransferCommand
 * @package coverallskit
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
     * @var \Eloquent\Pathogen\AbsolutePath
     */
    private $configFilePath;

    /**
     * @var array
     */
    private $optionRules = ['d::'];


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
     * @param string $relativeConfigFilePath
     * @return int
     */
    public function __invoke($relativeConfigFilePath)
    {
        try {
            $this->prepare($relativeConfigFilePath);
        } catch (ConfigFileNotFoundException $exception) {
            return $this->failed($exception);
        }

        return $this->performAction();
    }

    /**
     * @param string $configFilePath
     * @throws \Eloquent\Pathogen\Exception\NonRelativePathException
     */
    private function prepare($configFilePath)
    {
        $workDirectory = PathFactory::instance()->create(getcwd());

        $relativeConfigFilePath = RelativePath::fromString($configFilePath);
        $absoluteConfigFilePath = $workDirectory->resolve($relativeConfigFilePath);

        $this->configFilePath = $absoluteConfigFilePath;

        if (file_exists((string) $this->configFilePath)) {
            return;
        }

        throw new ConfigFileNotFoundException("File $this->configFilePath is not found");
    }

    /**
     * @return int
     */
    private function performAction()
    {
        $options = $this->context->getopt($this->optionRules);

        if ($options->get('-d')) {
            return $this->makeReport();
        } else {
            return $this->sendReport();
        }
    }

    /**
     * @param PrintableExceptionInterface $exception
     * @return int
     */
    private function failed(PrintableExceptionInterface $exception)
    {
        $exception->printMessage($this->stdio);
        return Status::FAILURE;
    }

    /**
     * @return ReportInterface
     */
    private function createReport()
    {
        $configFilePath = (string) $this->configFilePath;
        $configuration = Configuration::loadFromFile($configFilePath);
        $reportBuilder = ReportBuilder::fromConfiguration($configuration);

        $report = $reportBuilder->build();

        return $report;
    }

    /**
     * @return int
     */
    private function makeReport()
    {
        $report = $this->createReport();
        $report->save();

        return Status::SUCCESS;
    }

    /**
     * @return int
     */
    private function sendReport()
    {
        $report = $this->createReport();
        $report->setReportTransfer($this->getReportTransfer());
        $report->save();
        $report->upload();

        return Status::SUCCESS;
    }

}
