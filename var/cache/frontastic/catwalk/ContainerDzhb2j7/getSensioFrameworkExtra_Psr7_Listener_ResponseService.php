<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'sensio_framework_extra.psr7.listener.response' shared service.

return $this->privates['sensio_framework_extra.psr7.listener.response'] = new \Sensio\Bundle\FrameworkExtraBundle\EventListener\PsrResponseListener(new \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory());
