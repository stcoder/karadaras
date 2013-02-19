<?php

namespace Kaipack\Error;

class ErrorHandler
{
    private $levels = array(
        E_WARNING           => 'Warning',
        E_NOTICE            => 'Notice',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated',
    );

    private $level;

    /**
     * @param integer
     * @return The registered error handler
     */
    public static function register($level = null)
    {
        $handler = new static();
        $handler->setLevel($level);

        set_error_handler(array($handler, 'handle'));

        return $handler;
    }

    /**
     * @param integer $level
     * @return void
     */
    public function setLevel($level)
    {
        $this->level = null === $level ? error_reporting() : $level;
    }

    /**
     * @throws \ErrorException
     * @return bool
     */
    public function handle($level, $message, $file, $line, $context)
    {
        if (0 === $this->level) {
            return false;
        }

        if (error_reporting() & $level && $this->level & $level) {
            throw new \ErrorException(sprintf('%s: %s in %s line %d', isset($this->levels[$level]) ? $this->levels[$level] : $level, $message, $file, $line), 0, $level, $file, $line);
        }

        return false;
    }
}