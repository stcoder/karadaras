<?php

namespace Karadaras\Service;

use Karadaras\Application;

interface ServiceInterface
{
    public function register(Application $app);
    public function boot(Application $app);
}