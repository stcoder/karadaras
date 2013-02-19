<?php

namespace Karadaras;

class PluginManager extends \Pimple
{
    /**
     * Загруженные плагины.
     * 
     * @var array
     */
    protected $_plugins = [];

    /**
     * <code>
     *  array(
     *      'allow' => array(<plugin_name>, <method_name>),
     *      'deny'  => [array(<plugin_name>, <method_name>), array(1,2,3,4)]
     *  )
     * </code>
     * 
     * @var array
     */
    protected $_alias = [];

    /**
     * @var Application
     */
    protected $_app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
        $app['autoloader']->add('Plugin', realpath($app['dir']));
    }

    public function register($name)
}