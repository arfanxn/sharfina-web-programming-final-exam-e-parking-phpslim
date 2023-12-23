<?php

namespace App\Traits;

use Slim\Container;

trait ContainerAwareTrait
{
    private Container $container;

    public function setContainer(Container $container): self
    {
        $this->container = $container;
        return $this;
    }

    protected function getContainer(): Container
    {
        return $this->container;
    }
}
