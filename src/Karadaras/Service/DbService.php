<?php

namespace Karadaras\Service;

use Karadaras\Application;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class DbService implements ServiceInterface
{
    /**
     * Основные настройки базы данных.
     * 
     * @var array
     */
    protected $_options = array(
        'driver'   => 'pdo_mysql',
        'username' => '',
        'password' => '',
        'database' => '',
        'hostname' => 'localhost',
        'port'     => 3306,
        'charset'  => 'utf-8'
    );

    public function register(Application $app)
    {
        $app['db'] = $app->share(function($app) {
            $config = $app['config']->database;

            if (is_null($config) || !isset($config->options) && empty($config->options)) {
                throw new \Exception('Не указаны параметры для базы данных');
            }

            $options = array_merge($this->_options, $config->options->toArray());
            $adapter = new Adapter($options);
            GlobalAdapterFeature::setStaticAdapter($adapter);
            return $adapter;
        });
    }

    public function boot(Application $app)
    {}
}