<?php

namespace Opdavies\GmailFilterBuilder\Container;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Container
{
    public function __construct()
    {
        $this->containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yml');
        $this->containerBuilder->addCompilerPass(new CommandCompilerClass());
        $this->containerBuilder->compile();
    }

    public function get($className)
    {
        return $this->containerBuilder->get($className);
    }
}
