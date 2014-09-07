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

/**
 * Interface CommandFactoryInterface
 * @package coverallskit
 */
interface CommandFactoryInterface
{

    /**
     * @param ContextInterface $context
     * @return \coverallskit\CommandInterface
     */
    public function createFromContext(ContextInterface $context);

}
