<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'qafoo_labs_noframework.view_listener' shared service.

return $this->privates['qafoo_labs_noframework.view_listener'] = new \QafooLabs\Bundle\NoFrameworkBundle\EventListener\ViewListener(($this->services['templating'] ?? $this->load('getTemplatingService.php')), ($this->services['Frontastic\\Catwalk\\FrontendBundle\\Twig\\FrontasticNodeViewFallbackTemplateGuesser'] ?? $this->load('getFrontasticNodeViewFallbackTemplateGuesserService.php')), 'twig');
