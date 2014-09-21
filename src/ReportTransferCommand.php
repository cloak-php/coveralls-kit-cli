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
        $configFilePath = $this->resolveConfigFilePath($relativeConfigFilePath);

        if (file_exists($configFilePath) === false) {
            return $this->configurationFileNotFound($configFilePath);
        }

        $options = $this->context->getopt(['d::']);

        if ($options->get('-d')) {
            return $this->makeReport($configFilePath);
        } else {
            return $this->sendReport($configFilePath);
        }
    }

    /**
     * @param $configurationPath
     * @return int
     */
    private function configurationFileNotFound($configurationPath)
    {
        $this->stdio->errln("File $configurationPath is not found");
        return Status::FAILURE;
    }

    /**
     * @param $configurationPath
     * @return ReportInterface
     */
    private function createReport($configurationPath)
    {
        $configuration = Configuration::loadFromFile($configurationPath);
        $reportBuilder = ReportBuilder::fromConfiguration($configuration);

        $report = $reportBuilder->build();

        return $report;
    }

    /**
     * @param $configFilePath
     * @return int
     */
    private function makeReport($configFilePath)
    {
        $report = $this->createReport($configFilePath);
        $report->save();

        return Status::SUCCESS;
    }

    /**
     * @param ReportInterface $report
     * @return int
     */
    private function sendReport($configFilePath)
    {
        $report = $this->createReport($configFilePath);
        $report->setReportTransfer($this->getReportTransfer());
        $report->save();
        $report->upload();

        return Status::SUCCESS;
    }

    /**
     * @param $configFile
     * @return string
     * @throws \Eloquent\Pathogen\Exception\NonRelativePathException
     */
    private function resolveConfigFilePath($configFile)
    {
        $workDirectory = PathFactory::instance()->create(getcwd());

        $relativeConfigFilePath = RelativePath::fromString($configFile);
        $configFilePath = $workDirectory->resolve($relativeConfigFilePath);

        return $configFilePath->normalize()->string();
    }

}
