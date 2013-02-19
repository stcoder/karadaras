<?php

namespace Kaipack\Error;

use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler
{
    /**
     * @var bool
     */
    private $_debug;

    /**
     * @var array
     */
    private $_errors = array(
        '500' => 'Упс, похоже, что-то пошло не так.',
        '404' => 'К сожалению, страница, которую вы ищите, не может быть найдена.',
        '403' => 'К сожалениею, у вас нет доступа к запрашиваемому ресурсу.'
    );

    /**
     * @var array
     */
    private $_errorsType = array(
        '500' => 'Ошибка сервера',
        '404' => 'Страница не найдена',
        '403' => 'Доступ запрещен'
    );

    /**
     * Конструктор обработчика исключений.
     * 
     * @param bool $debug
     */
    public function __construct($debug = true)
    {
        $this->_debug = $debug;
    }

    /**
     * Регистрируем обработчик исключений.
     * 
     * @param bool $debug
     * @return ExceptionHandler
     */
    public static function register($debug = true)
    {
        $handler = new static($debug);
        set_exception_handler(array($handler, 'handle'));
        return $handler;
    }

    public function handle(\Exception $exception)
    {
        $errorCodes = array_keys($this->_errors);
        $errorCode  = 500;

        if (in_array($exception->getCode(), $errorCodes)) {
            $errorCode = $exception->getCode();
        }

        $errorMessage = $this->_errors[$errorCode];

        if ($this->_debug) {
            $errorMessage = $exception->getMessage();
        }

        $content = sprintf('<h2>%s</h2><p>%s</p>', $this->_errorsType[$errorCode], $errorMessage);
        if ($this->_debug) {
            $content .= sprintf('<pre class="trace">%s</pre>', $exception->getTraceAsString());
        }

        $response = new Response($this->_renderError($content), $errorCode);
        $response->send();
    }

    protected function _renderError($content)
    {
        $templateFile = __DIR__ . '/error-template.html';
        $template     = file_get_contents($templateFile);
        $render       = strtr($template, array('{{ content }}' => $content));
        return $render;
    }
}