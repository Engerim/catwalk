<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Frontastic\Common\HttpClient\Signing' shared service.

return $this->services['Frontastic\\Common\\HttpClient\\Signing'] = new \Frontastic\Common\HttpClient\Signing(($this->services['Frontastic\\Common\\HttpClient'] ?? $this->load('getHttpClientService.php')), $this->getEnv('secret'));
