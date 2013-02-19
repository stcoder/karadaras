<?php

namespace Karadaras\Service;

use Karadaras\Application;

class CacheService implements ServiceInterface
{
    /**
     * Доступные адаптеры кэша.
     * 
     * @var array
     */
    protected $_adapters = ['apc', 'memcached', 'xcache', 'memory', 'filesystem'];

    /**
     * Основные настройки кэша.
     * 
     * @var array
     */
    protected $_options = array(
        'namespace'  => 'karadaras',
        'keyPattern' => '',
        'readable'   => true,
        'writable'   => true,
        'ttl'        => 7200
    );

    public function register(Application $app)
    {
        $app['cache'] = $app->share(function($app) {
            $adapter = 'memory';
            $options = [];
            $config  = $app['config']->cache;

            if (!is_null($config)) {
                if (isset($config->adapter) && in_array($config->adapter, $this->_adapters)) {
                    $adapter = $config->adapter;
                }

                $options = isset($config->options) ? $config->options->toArray() : array();
                $options = array_merge($this->_options, $options);

                if ('filesystem' === $adapter) {
                    $options['cacheDir'] = sprintf('%s/%s', $app->_applicationDir, trim($options['cacheDir'], '/'));
                }
            }

            $adapterClass = sprintf('\\Zend\\Cache\\Storage\\Adapter\\%s', ucfirst($adapter));
            return new $adapterClass($options);
        });
    }

    public function boot(Application $app)
    {}
}