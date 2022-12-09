<?php
declare(strict_types=1);

namespace App\Kernel;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\ResolveEnvPlaceholdersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppKernel
{

    private ContainerInterface $container;

    public function __construct(string $config)
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load($config);
        $this->container->addCompilerPass(new ResolveEnvPlaceholdersPass());
        $this->container->compile();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

}