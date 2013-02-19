<?php

namespace Dev;

class Bootstrap
{
    public function __construct(\Karadaras\Application $app)
    {
        $app['theme'] = 'KdsWhite';
    }

    public function boot()
    {
        
    }
}