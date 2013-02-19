<?php

namespace Karadaras;

class ViewManager
{
    /**
     * Имя текущей темы.
     * 
     * @var string
     */
    protected $_themeName;

    /**
     * @var string
     */
    protected $_themeClass;

    /**
     * @var \Twig_Environment
     */
    protected $_twig;

    /**
     * @var \Twig_Loader_Filesystem
     */
    protected $_loader;

    /**
     * Объект текущей темы.
     * 
     * @var \Karadaras\View\ThemeAbstract
     */
    protected $_theme;

    /**
     * @var \Karadaras\Application
     */
    protected $_application;

    /**
     * @param \Karadaras\Application $app
     */
    public function __construct(Application $app)
    {
        $this->_application = $app;
        $app['autoloader']->add('Theme', realpath($app['dir']));

        $this->_themeName  = $app['view.options']['theme'];
        $this->_themeClass = $this->getThemeClass();
    }

    /**
     * @param \Twig_Loader_Filesystem $loader
     * @return ViewManager
     */
    public function setLoader(\Twig_Loader_Filesystem $loader)
    {
        $this->_loader = $loader;
        return $this;
    }

    /**
     * @return \Twig_Loader_Filesystem
     */
    public function getLoader()
    {
        return $this->_loader;
    }

    /**
     * @param \Twig_Environment $twig
     * @return ViewManager
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->_twig = $twig;
        return $this;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->_twig;
    }

    /**
     * @return string
     */
    public function getThemeClass()
    {
        $theme = strtr($this->_themeName, array('.' => ' ', '-' => ' '));
        $theme = ucwords($theme);
        $theme = strtr($theme, array(' ' => ''));
        $class = sprintf('\\Theme\\%s\\%sTheme', $theme, $theme);
        return $class;
    }

    /**
     * @return \Karadaras\View\ThemeAbstract
     */
    public function getTheme()
    {
        if (is_null($this->_theme)) {
            $this->_theme = new $this->_themeClass($this->_application);

            if (!($this->_theme instanceof View\ThemeAbstract)) {
                throw new \RuntimeException(sprintf('Не корректный тип темы "%ы"', $this->_themeName));
            }
        }

        return $this->_theme;
    }
}