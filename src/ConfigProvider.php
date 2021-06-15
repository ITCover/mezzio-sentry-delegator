<?php

namespace Advise\MezzioSentryDelegator;

use Advise\MezzioSentryDelegator\Listener\SentryListener;
use Advise\MezzioSentryDelegator\Listener\SentryListenerFactory;
use Laminas\Stratigility\Middleware\ErrorHandler;

/**
 * Class ConfigProvider
 * @package Advise\MezzioSentryDelegator
 */
class ConfigProvider
{

    /**
     * @return array|array[]
     */
    public function __invoke(): array
    {
        return ['dependencies' => $this->getDependencies()];
    }

    /**
     * @return array
     */
    private function getDependencies(): array
    {
        return [
            'factories'  => [
                SentryListener::class => SentryListenerFactory::class
            ],
            'delegators' => [
                ErrorHandler::class => [
                    SentryListenerDelegator::class
                ]
            ],
        ];
    }
}
