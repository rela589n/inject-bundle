<?php

declare(strict_types=1);

namespace Rela589n\Bundle\InjectBundle;

use Rela589n\Bundle\InjectBundle\Container\SymfonyContainerAdapter;
use Rela589n\Bundle\InjectBundle\DependencyInjection\Compiler\InjectionTemplatesCompilerPass;
use Rela589n\Injection\Container\InjectionEntryPointContainer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class Rela589nInjectBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new InjectionTemplatesCompilerPass());
    }

    public function boot(): void
    {
        $container = InjectionEntryPointContainer::getSelf();
        $container->wrapContainer(new SymfonyContainerAdapter($this->container));
    }
}
