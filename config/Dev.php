<?php

/**
 * This file is part of CoverallsKit.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Aura\Cli_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

/**
 * Class Dev
 * @package Aura\Cli_Project\_Config
 */
class Dev extends Config
{

    /**
     * @param Container $di
     * @return null|void
     */
    public function define(Container $di)
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', true);
    }

    /**
     * @param Container $di
     * @return null|void
     */
    public function modify(Container $di)
    {
    }

}
