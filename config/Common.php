<?php

namespace Aura\Cli_Project\_Config;

use coverallskit\command\InitializeCommand;
use coverallskit\command\ReportTransferCommand;
use Aura\Di\Config;
use Aura\Di\Container;
use Aura\Cli\Help;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


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
        $di->set('aura/project-kernel:logger', $di->newInstance(Logger::class));

        $di->params[InitializeCommand::class] = [
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
        $this->modifyLogger($di);
        $this->modifyCliDispatcher($di);
        $this->modifyCliHelpService($di);
    }

    /**
     * @param Container $di
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    protected function modifyLogger(Container $di)
    {
        $project = $di->get('project');
        $mode = $project->getMode();
        $file = $project->getPath("tmp/log/{$mode}.log");

        $streamHandler = $di->newInstance(StreamHandler::class, ['stream' => $file]);

        $logger = $di->get('aura/project-kernel:logger');
        $logger->pushHandler($streamHandler);
    }

    /**
     * @param Container $di
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    protected function modifyCliDispatcher(Container $di)
    {
        $context = $di->get('aura/cli-kernel:context');
        $stdio = $di->get('aura/cli-kernel:stdio');
        $logger = $di->get('aura/project-kernel:logger');

        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject('init', $di->lazyNew(InitializeCommand::class));
        $dispatcher->setObject('transfer', $di->lazyNew(ReportTransferCommand::class));
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

        $transferHelp = $di->newInstance(Help::class);
        $helpService->set('transfer', function () use ($transferHelp) {
            $transferHelp->setUsage(['']);
            $transferHelp->setSummary('Send to coveralls the report file.');
            return $transferHelp;
        });
    }

}
