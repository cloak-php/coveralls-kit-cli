<?php

use cloak\peridot\CloakPlugin;
use expect\peridot\ExpectPlugin;
use Evenement\EventEmitterInterface;

return function (EventEmitterInterface $emitter) {

    ExpectPlugin::create()->registerTo($emitter);

    if (defined('HHVM_VERSION') === false) {
        CloakPlugin::create('.cloak.toml')->registerTo($emitter);
    }

};
