<?php

declare(strict_types=1);

namespace Rela589n\Bundle\InjectBundle\Container;

use Rela589n\Bundle\InjectBundle\InjectionTemplate;
use Rela589n\Injection\Container\InjectionContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Webmozart\Assert\Assert;

use function call_user_func_array;
use function get_class;
use function get_parent_class;

final class SymfonyContainerAdapter implements InjectionContainer
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function injectTo(object $injectable): void
    {
        Assert::methodExists($injectable, '__inject');

        $template = $this->findTemplateService($class = get_class($injectable));
        if (null === $template) {
            throw new ServiceNotFoundException($class);
        }

        call_user_func_array(
            [$injectable, '__inject'],
            $template->getArguments()
        );
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-param class-string $className
     */
    private function findTemplateService(string $className): ?InjectionTemplate
    {
        if ($this->container->has($className)) {
            /**
             * @psalm-suppress LessSpecificReturnStatement
             * @noinspection PhpIncompatibleReturnTypeInspection
             */
            return $this->container->get($className);
        }

        $parentClass = get_parent_class($className);
        if ($parentClass) {
            return $this->findTemplateService($parentClass);
        }

        return null;
    }
}
