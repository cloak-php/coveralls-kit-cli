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
use coverallskit\entity\ReportEntity;
use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;
use Eloquent\Pathogen\Factory\PathFactory;
use Eloquent\Pathogen\RelativePath;


/**
 * Class ReportTransferCommand
 * @package coverallskit
 */
class ReportTransferCommand implements ReportTransferAware
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
    private $optionRules = [
        'c::',
        'd::',
        'config::',
        'debug::'
    ];


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
     * @return int
     */
    public function __invoke()
    {
        try {
            $this->prepare();
        } catch (ConfigFileNotFoundException $exception) {
            return $this->failed($exception);
        }

        return $this->performAction();
    }

    /**
     * @throws \Eloquent\Pathogen\Exception\NonRelativePathException
     */
    private function prepare()
    {
        $configFilePath = $this->configurationFile();
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

        if ($options->get('-d') || $options->get('--debug')) {
            return $this->makeReport();
        } else {
            return $this->sendReport();
        }
    }

    /**
     * @param PrintableException $exception
     * @return int
     */
    private function failed(PrintableException $exception)
    {
        $exception->printMessage($this->stdio);
        return Status::FAILURE;
    }

    private function configurationFile()
    {
        $path = null;
        $names = [ '-c', '--config' ];
        $options = $this->context->getopt($this->optionRules);

        foreach ($names as $name) {
            $path = $options->get($name);

            if ($path === null) {
                continue;
            }
            break;
        }

        return ($path !== null) ? $path : '.coveralls.toml';
    }

    /**
     * @return ReportEntity
     */
    private function createReport()
    {
        $configFilePath = (string) $this->configFilePath;
        $configuration = BuilderConfiguration::loadFromFile($configFilePath);
        $reportBuilder = CoverallsReportBuilder::fromConfiguration($configuration);

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
