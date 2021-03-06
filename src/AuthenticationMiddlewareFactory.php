<?php

/**
 * @see       https://github.com/mezzio/mezzio-authentication for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio-authentication/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio-authentication/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Mezzio\Authentication;

use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;
use Zend\Expressive\Authentication\AuthenticationInterface as ExpressiveAuthenticationInterface;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): AuthenticationMiddleware
    {
        $authentication = $container->has(AuthenticationInterface::class)
            ? $container->get(AuthenticationInterface::class)
            : ($container->has(ExpressiveAuthenticationInterface::class)
                ? $container->get(ExpressiveAuthenticationInterface::class)
                : null);
        Assert::nullOrIsInstanceOfAny($authentication, [
            AuthenticationInterface::class,
            ExpressiveAuthenticationInterface::class,
        ]);

        if (null === $authentication) {
            throw new Exception\InvalidConfigException(
                'AuthenticationInterface service is missing'
            );
        }

        return new AuthenticationMiddleware($authentication);
    }
}
