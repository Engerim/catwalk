<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler\Order' shared service.

return $this->services['Frontastic\\Catwalk\\FrontendBundle\\Domain\\StreamHandler\\Order'] = new \Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler\Order(($this->services['frontastic.catwalk.cart_api'] ?? $this->load('getFrontastic_Catwalk_CartApiService.php')));