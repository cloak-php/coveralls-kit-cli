<?php

namespace Aura\Cli_Project\_Config;

use coverallskit\InitializeCommand;
use coverallskit\ReportTransferCommand;
use Aura\Di\Config;
use Aura\Di\Container;
use Aura\Cli\Help;
use Psr\Log\NullLogger;


/**
 * Class Common
 * @package Aura\Cli_Project\_Config
 */
class Common extends Config
{

    /**
     * @param Container $di
     * @return null|void
     * @throws \Aura\Di\Exception\ContainerLocked
     * @throws \Aura\Di\Exception\ServiceNotObject
     */
    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->newInstance(NullLogger::class));

        $di->params[InitializeCommand::class] = [
            'context' => $di->lazyGet('aura/cli-kernel:context'),
            'stdio' => $di->lazyGet('aura/cli-kernel:stdio')
        ];
        $di->params[ReportTransferCommand::class] = [
            'context' => $di->lazyGet('aura/cli-kernel:context'),
            'stdio' => $di->lazyGet('aura/cli-kernel:stdio')
        ];
    }

    /**
     * @param Container $di
     * @return null|void
     */
    public function modify(Container $di)
    {
        $this->modifyCliDispatcher($di);
        $this->modifyCliHelpService($di);
    }

    /**
     * @param Container $di
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    protected function modifyCliDispatcher(Container $di)
    {
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject('init', $di->lazyNew(InitializeCommand::class));
        $dispatcher->setObject('send', $di->lazyNew(ReportTransferCommand::class));
    }

    /**
     * @param Container $di
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    protected function modifyCliHelpService(Container $di)
    {
        $helpService = $di->get('aura/cli-kernel:help_service');

        $initHelp = $di->newInstance(Help::class);
        $helpService->set('init', function () use ($initHelp) {
            $initHelp->setUsage(['', '<project-directory>']);
            $initHelp->setSummary('Create a coveralls.yml file.');

            return $initHelp;
        });

        $sendHelp = $di->newInstance(Help::class);
        $helpService->set('send', function () use ($sendHelp) {
            $sendHelp->setUsage([
                '<config-file> [<options>]'
            ]);
            $sendHelp->setSummary('Send to coveralls the report file.');
            $sendHelp->setOptions([
                'd::' => 'Will generate a report, but does not send the file.'
            ]);

            return $sendHelp;
        });
    }

}
