<?php

namespace Karadaras\Service;

use Karadaras\Application;
use Karadaras\ViewManager;

class ViewService implements ServiceInterface
{
    /**
     * Основные настройки.
     * 
     * @var array
     */
    protected $_options = array(
        'theme'          => 'kds-white',
        'templates_path' => 'templates',
        'cache_path'     => 'cache'
    );

    public function register(Application $app)
    {
        $app['view.init'] = $app->share(function($app) {
            $options = isset($app['config']->view) ? $app['config']->view->toArray() : [];
            $app['view.options'] = array_merge($this->_options, $options); 
            return new ViewManager($app);
        });

        $app['twig.loader'] = $app->share(function($app) {
            $theme = $app['view.init']->getTheme();
            $path  = $theme->getThemeDir() . '/' . $app['view.options']['templates_path'];
            return new \Twig_Loader_Filesystem($path);
        });

        $app['twig'] = $app->share(function($app) {
            $twig = new \Twig_Environment($app['twig.loader'], $app['view.options']);
            $twig->addGlobal('app', $app);
            if ($app['debug']) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }
            return $twig;
        });

        $app['view'] = $app->share(function($app) {
            $view = $app['view.init'];
            $view->setTwig($app['twig']);
            $view->setLoader($app['twig.loader']);
        });
    }

    public function boot(Application $app)
    {

    }
}