<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'frontastic.catwalk.api_core_bundle.domain.app_data_target' shared service.

return $this->services['frontastic.catwalk.api_core_bundle.domain.app_data_target'] = new \Frontastic\Catwalk\ApiCoreBundle\Domain\EnvironmentReplicationFilter(new \Frontastic\Catwalk\ApiCoreBundle\Domain\AppDataTarget(($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\AppService'] ?? $this->load('getAppServiceService.php')), ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\AppRepositoryService'] ?? $this->load('getAppRepositoryServiceService.php')), ($this->services['Frontastic\\Catwalk\\ApiCoreBundle\\Gateway\\AppRepositoryGateway'] ?? $this->load('getAppRepositoryGatewayService.php')), ($this->services['Frontastic\\Common\\ReplicatorBundle\\Domain\\Project'] ?? $this->getProjectService())), 'production');
