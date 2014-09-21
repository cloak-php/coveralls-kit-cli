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

use Aura\Cli\Stdio;

/**
 * Trait PrintFailedMessageTrait
 * @package coverallskit
 */
trait PrintFailedMessageTrait
{

    /**
     * @param Stdio $stdio
     */
    public function printMessage(Stdio $stdio)
    {
        $stdio->errln('Command failed:');
        $stdio->errln($this->getMessage());
    }

}
