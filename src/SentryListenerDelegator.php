<?php

namespace Advise\MezzioSentryDelegator;

use Advise\MezzioSentryDelegator\Listener\SentryListener;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Psr\Container\ContainerInterface;

/**
 * Class SentryListenerDelegator
 * @package Advise\MezzioSentryDelegator
 */
class SentryListenerDelegator
{

    /**
     * @param ContainerInterface $container
     * @param                    $name
     * @param callable           $callback
     * @param array|null         $options
     * @return ErrorHandler
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null): ErrorHandler
    {
        $listener = $container->get(SentryListener::class);

        /** @var ErrorHandler $errorHandler */
        $errorHandler = $callback();

        if ($listener->isEnabled() === true) {
            $errorHandler->attachListener($listener);
        }

        return $errorHandler;
    }
}
