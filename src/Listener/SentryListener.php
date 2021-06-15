<?php

namespace Advise\MezzioSentryDelegator\Listener;

use Advise\MezzioSentryDelegator\Exceptions\InvalidConfigException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\InvalidArgumentException;
use Sentry\ClientBuilder;
use Sentry\SentrySdk;
use Sentry\State\Scope;
use Throwable;
use function Sentry\captureException;

/**
 * Class SentryListener
 * @package Advise\MezzioSentryDelegator\Listener
 */
class SentryListener
{

    /** @var bool $enabled */
    private bool $enabled;

    /**
     * Listener constructor.
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config)
    {
        if (!isset($config['sentry'])) {
            throw new InvalidConfigException('Missing error handler configuration');
        }

        $this->enabled = $config['sentry']['enabled'] ?? false;

        $client = ClientBuilder::create($config['sentry'])->getClient();

        SentrySdk::init()->bindClient($client);

        $options = [
            'php_version' => PHP_VERSION
        ];

        SentrySdk::getCurrentHub()->withScope(
            function (Scope $scope) use ($options) {
                if (isset($options['php_version'])) {
                    $scope->setTag('php_version', $options['php_version']);
                }
            }
        );
    }

    /**
     * @param Throwable              $error
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     */
    public function __invoke(
        Throwable $error,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): void {
        captureException($error);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
