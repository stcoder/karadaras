<?php

namespace Karadaras;

/**
 * Движок системы
 * 
 * @author Serget Tihonov
 */
class Engine
{
    /**
     * Версия движка.
     * 
     * @var string
     */
    const VERSION = '1.2-DEV';

    /**
     * @var string
     */
    protected static $_appDir;

    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected static $_loader;

    /**
     * @var \Karadaras\Application
     */
    protected static $_application;

    /**
     * Установить или получить директорию приложения.
     * 
     * @param null|string $dir
     * @return string
     */
    public static function appDir($dir = null)
    {
        if (is_null($dir)) {
            return static::$_appDir;
        }

        $dir = (string) $dir;
        return static::$_appDir = $dir;
    }

    /**
     * Установить или получить объект загрузчика классов.
     * 
     * @param null|\Composer\Autoload\ClassLoader
     * @return \Composer\Autoload\ClassLoader
     */
    public static function loader(\Composer\Autoload\ClassLoader $loader = null)
    {
        if (is_null($loader)) {
            return static::$_loader;
        }

        return static::$_loader = $loader;
    }

    /**
     * Инициализировать приложение.
     * 
     * @return \Karadaras\Application
     */
    public static function useApplication($appName)
    {
        // @todo: Определить тип запуска, консоль или веб.
        // Инициализируем приложение.
        // Указываем рабочию директорию приложения.
        static::$_application = $app = new Application($appName);
        $app->init();
        return $app;
    }
}