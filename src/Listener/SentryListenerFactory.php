<?php

namespace Advise\MezzioSentryDelegator\Listener;

use Advise\MezzioSentryDelegator\Exceptions\InvalidConfigException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * Class SentryListenerFactory
 * @package Advise\MezzioSentryDelegator\Listener
 */
class SentryListenerFactory implements FactoryInterface
{

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SentryListener
    {
        $config = $container->get('config');

        return new SentryListener($config);
    }
}
