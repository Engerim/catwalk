<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'frontastic.catwalk.product_api' shared service.

return $this->services['frontastic.catwalk.product_api'] = ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\ProductApiFactoryDecorator'] ?? $this->load('getProductApiFactoryDecoratorService.php'))->factor(($this->services['Frontastic\\Common\\ReplicatorBundle\\Domain\\Project'] ?? $this->getProjectService()));
