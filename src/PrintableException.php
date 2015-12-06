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
 * Interface PrintableException
 * @package coverallskit
 */
interface PrintableException
{

    /**
     * @param Stdio $stdio
     * @return void
     */
    public function printMessage(Stdio $stdio);

}
