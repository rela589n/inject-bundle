<?php

declare(strict_types=1);

namespace Rela589n\Bundle\InjectBundle\DependencyInjection\Compiler;

use Rela589n\Bundle\InjectBundle\InjectionTemplate;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Webmozart\Assert\Assert;

use function is_a;

final class InjectionTemplatesCompilerPass implements CompilerPassInterface
{
    public const TAG_NAME = 'injection.template';

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $serviceId => $_tags) {
            $definition = $container->getDefinition($serviceId);
            $definition->setLazy(true);
            $definition->setPublic(true);
            $definition->setAutowired(false);
            $definition->setAutoconfigured(false);
            Assert::null($definition->getFactory(), 'Factories are not supported when using container for runtime ijection');
            if (!is_a($definition->getClass(), InjectionTemplate::class, true)) {
                $definition->setClass(InjectionTemplate::class);
            }
        }
    }
}
