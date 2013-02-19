<?php

namespace Karadaras\Service;

use Karadaras\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DispatcherService implements ServiceInterface
{
    public function register(Application $app)
    {
        $app['request'] = $app->share(function($app) {
            return Request::createFromGlobals();
        });

        $app['response'] = function() {
            return new Response();
        };

        $app['response.json'] = function($app) {
            return $app['response'] = new JsonResponse();
        };
    }

    public function onResponseSend(Application\Event $e)
    {
        $app      = $e->getTarget();
        $response = $app['response'];

        if (!($response instanceof Response)) {
            $response = new Response();
        }

        $response->send();
    }

    public function boot(Application $app)
    {
        $app->getEventManager()->attach(Application\Event::APP_EVENT_RESPONSE, array($this, 'onResponseSend'), -99999);
    }
}