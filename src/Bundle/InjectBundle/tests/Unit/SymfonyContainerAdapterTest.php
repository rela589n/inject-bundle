<?php

declare(strict_types=1);

namespace Rela589n\Bundle\InjectBundle\tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Rela589n\Bundle\InjectBundle\Container\SymfonyContainerAdapter;
use Rela589n\Bundle\InjectBundle\InjectionTemplate;
use Rela589n\Bundle\InjectBundle\tests\Unit\Mocks\ChildOfInjectableMock;
use Rela589n\Bundle\InjectBundle\tests\Unit\Mocks\InjectableMock;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Webmozart\Assert\InvalidArgumentException;

final class SymfonyContainerAdapterTest extends TestCase
{
    /** @var MockObject */
    private $container;

    /** @var SymfonyContainerAdapter */
    private $adapter;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->adapter = new SymfonyContainerAdapter($this->container);
    }

    public function testThrowsExceptionIfInjectMethodNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter->injectTo(new stdClass());
    }

    public function testThrowsExceptionIfTemplateServiceNotFound(): void
    {
        $this->expectException(ServiceNotFoundException::class);

        $this->adapter->injectTo(new InjectableMock());
    }

    public function testInjectsTemplateArguments(): void
    {
        $this->hasInContainer(
            InjectableMock::class,
            new InjectionTemplate(123, 'secret')
        );

        $this->adapter->injectTo($object = new InjectableMock());

        self::assertSame(123, $object->timeout);
        self::assertSame('secret', $object->secretKey);
    }

    public function testInjectsTemplateArgumentsForParentClass(): void
    {
        $this->hasInContainer(
            InjectableMock::class,
            new InjectionTemplate(345, 'second secret')
        );

        $this->adapter->injectTo($object = new ChildOfInjectableMock());

        self::assertSame(345, $object->timeout);
        self::assertSame('second secret', $object->secretKey);
    }

    private function hasInContainer(string $serviceId, object $service): void
    {
        $this->container
            ->method('has')
            ->willReturnMap([[$serviceId, true]]);

        $this->container
            ->method('get')
            ->willReturnMap(
                [
                    [
                        $serviceId,
                        ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
                        $service,
                    ]
                ]
            );
    }
}
