<?php

namespace Aura\Cli_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

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
        $di->set('aura/project-kernel:logger', $di->newInstance('Monolog\Logger'));
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

        $logger = $di->get('aura/project-kernel:logger');
        $logger->pushHandler($di->newInstance(
            'Monolog\Handler\StreamHandler',
            array(
                'stream' => $file,
            )
        ));
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
        $dispatcher->setObject(
            'hello',
            function ($name = 'World') use ($context, $stdio, $logger) {
                $stdio->outln("Hello {$name}!");
                $logger->debug("Said hello to '{$name}'");
            }
        );
    }

    /**
     * @param Container $di
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    protected function modifyCliHelpService(Container $di)
    {
        $helpService = $di->get('aura/cli-kernel:help_service');

        $help = $di->newInstance('Aura\Cli\Help');
        $helpService->set('hello', function () use ($help) {
            $help->setUsage(array('', '<noun>'));
            $help->setSummary("A demonstration 'hello world' command.");
            return $help;
        });
    }

}
