<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Frontastic\Common\HttpClient\Factory' shared autowired service.

$a = new \Symfony\Bridge\Monolog\Logger('httpClient');
$a->pushProcessor(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Monolog\\FrontasticLogProcessor'] ?? $this->getFrontasticLogProcessorService()));
$a->pushHandler(($this->privates['monolog.handler.filter_for_errors'] ?? $this->getMonolog_Handler_FilterForErrorsService()));

return $this->services['Frontastic\\Common\\HttpClient\\Factory'] = new \Frontastic\Common\HttpClient\Factory($a, ($this->services['Frontastic\\Common\\HttpClient\\Options'] ?? ($this->services['Frontastic\\Common\\HttpClient\\Options'] = new \Frontastic\Common\HttpClient\Options(['timeout' => $this->getEnv('http_client_timeout')]))));
