<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Frontastic\Common\ShopifyBundle\Domain\ShopifyClientFactory' shared service.

return $this->services['Frontastic\\Common\\ShopifyBundle\\Domain\\ShopifyClientFactory'] = new \Frontastic\Common\ShopifyBundle\Domain\ShopifyClientFactory(($this->services['Frontastic\\Common\\HttpClient'] ?? $this->load('getHttpClientService.php')), ($this->privates['cache.app.simple'] ?? $this->load('getCache_App_SimpleService.php')), ($this->services['Frontastic\\Common\\CoreBundle\\Domain\\RequestProvider'] ?? $this->load('getRequestProviderService.php')));