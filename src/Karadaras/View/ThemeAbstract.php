<?php

namespace Karadaras\View;

use Karadaras\Application;

abstract class ThemeAbstract
{
    /**
     * @var string
     */
    protected $_themeDir;

    /**
     * @var \Karadaras\Application
     */
    protected $_app;

    /**
     * @var \Karadaras\ViewManager
     */
    protected $_view;

    /**
     * @param \Karadaras\Application $app
     */
    public function __construct(Application $app)
    {
        $this->_app      = $app;
        $ref             = new \ReflectionClass($this);
        $this->_themeDir = dirname($ref->getFileName());
    }

    /**
     * @return string
     */
    public function getThemeDir()
    {
        return $this->_themeDir;
    }
}