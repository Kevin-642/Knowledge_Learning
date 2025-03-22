<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
function configureRoutes(RoutingConfigurator $routes): void
{
    $routes->import('../config/routes.yaml');
    // Autres imports si nécessaire
}