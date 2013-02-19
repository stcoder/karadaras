<?php

namespace Karadaras;

/**
 * Приложение
 * 
 * @author Sergey Tihonov
 */
class Application extends \Pimple
{
    protected $_name;
    protected $_applicationDir;
    protected $_eventManager;
    protected $_event;
    protected $_bootstrap;
    protected $_services;

    /**
     * Конструктор приложения.
     * 
     * @param string $name
     */
    public function __construct($name)
    {
        $app = $this;
        $this['dir']     = Engine::appDir();
        $this['charset'] = 'utf-8';
        $this['debug']   = true; // @TODO: сделать определение дебаг режима

        if (!is_readable($this['dir'])) {
            throw new \Exception(sprintf(
                'Директория приложения "%s" не существует или не доступна для чтения', $this->_applicationDir
            ));
        }

        $this->_eventManager = new \Zend\EventManager\EventManager();
        $this->_event        = new Application\Event();
        $this->_name         = $name;
        $loader              = Engine::loader();

        $this->_event->setTarget($this);

        // Регистрируем пространство имени для загрузки классов приложения.
        $loader->add(ucfirst($name), realpath($this['dir'] . '/../'));

        $this['autoloader'] = function() use ($loader) {
            return $loader;
        };

        $this['config.factory'] = function() {
            return new Config();
        };

        $this['config'] = $this->share(function($app) {
            $config = $app['config.factory'];
            return $config->process($this['dir'] . '/config/app.config.json');
        });
    }

    public function getEventManager()
    {
        return $this->_eventManager;
    }

    /**
     * Регистрируем службу.
     * 
     * @param \Karadaras\Service\ServiceInterface $service
     * @return Application
     */
    public function register(Service\ServiceInterface $service)
    {
        $this->_services[] = $service;
        $service->register($this);
        return $this;
    }

    /**
     * Загрузка всех служб.
     * 
     * @return void
     */
    public function boot()
    {
        foreach($this->_services as $service) {
            $service->boot($this);
        }
    }

    /**
     * Инициализация приложения.
     * 
     * @return void
     */
    public function init()
    {
        $app = $this;

        // Автозагрузчик приложения.
        $this['bootstrap'] = $this->share(function($app) {
            $bootstrapClass = sprintf('\\%s\\Bootstrap', ucfirst($app->_name));
            return new $bootstrapClass($app);
        });

        // Конструктор загрузчика приложения отработает до загрзки основных служб.
        // В конструкторе можно переопределить основные службы.
        $this->_eventManager->attach(Application\Event::APP_EVENT_BOOT, function() use ($app) {
            return $app['bootstrap'];
        }, 999999);

        $this->_eventManager->attach(Application\Event::APP_EVENT_BOOT, function() use ($app) {
            $bootstrap = $app['bootstrap'];
            if (method_exists($bootstrap, 'boot')) {
                return $bootstrap->boot($app);
            }
        }, -10);

        // Регистрируем основные службы.
        $this->register(new Service\CacheService());
        $this->register(new Service\DbService());
        $this->register(new Service\ViewService());
        $this->register(new Service\PluginService());
        //$this->register(new Service\ModuleService());
        //$this->register(new Service\ThemeService());
        //$this->register(new Service\WidgetService());
        //$this->register(new Service\DispatcherService());
        //$this->register(new Service\View());
    }

    public function run()
    {
        $this->boot();
        $this->_eventManager->trigger(Application\Event::APP_EVENT_BOOT, $this->_event);
        //$this->_eventManager->trigger(Application\Event::APP_EVENT_RESPONSE, $this->_event);
    }
}