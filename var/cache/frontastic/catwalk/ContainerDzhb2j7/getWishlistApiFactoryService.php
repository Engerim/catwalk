<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Frontastic\Common\WishlistApiBundle\Domain\WishlistApiFactory' shared service.

return $this->services['Frontastic\\Common\\WishlistApiBundle\\Domain\\WishlistApiFactory'] = new \Frontastic\Common\WishlistApiBundle\Domain\WishlistApiFactory($this, ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProductApiFactoryDecorator'] ?? $this->load('getProductApiFactoryDecoratorService.php')), ($this->services['Frontastic\\Common\\ProductApiBundle\\Domain\\ProductApi\\Commercetools\\ClientFactory'] ?? $this->load('getClientFactory2Service.php')), new RewindableGenerator(function () {
    return new \EmptyIterator();
}, 0));
