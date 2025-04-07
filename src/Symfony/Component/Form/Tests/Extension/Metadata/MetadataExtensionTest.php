<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Extension\Metadata;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\MetadataException;
use Symfony\Component\Form\Extension\Metadata\MetadataExtension;
use Symfony\Component\Form\Extension\Metadata\Type\MetadataType;
use Symfony\Component\Form\Metadata\FormMetadataInterface;
use Symfony\Component\Form\Metadata\Loader\LoaderInterface;

/**
 * Class MetadataExtensionTest.
 *
 * @author Benjamin Georgeault
 */
class MetadataExtensionTest extends TestCase
{
    /**
     * @dataProvider hasTypeProvider
     */
    public function testHasType(string $class, bool $expected)
    {
        ($loader = $this->createMock(LoaderInterface::class))
            ->expects($this->once())
            ->method('support')
            ->with($class)
            ->willReturn($expected);

        $this->assertSame($expected, $this->getExtension($loader)->hasType($class));
    }

    public function hasTypeProvider(): \Generator
    {
        yield [Model::class, true];
        yield [Model::class, false];
    }

    public function testGetTypeWithException()
    {
        ($loader = $this->createMock(LoaderInterface::class))
            ->expects($this->once())
            ->method('load')
            ->willThrowException(new MetadataException());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot instantiate a "Symfony\Component\Form\FormTypeInterface" for the given class "Foo".');
        $this->getExtension($loader)->getType('Foo');
    }

    public function testGetType()
    {
        ($loader = $this->createMock(LoaderInterface::class))
            ->expects($this->once())
            ->method('load')
            ->willReturn($this->createMock(FormMetadataInterface::class));

        $extension = $this->getExtension($loader);
        $this->assertInstanceOf(MetadataType::class, $firstInstance = $extension->getType('Foo'));
        $this->assertSame($firstInstance, $extension->getType('Foo'));
    }

    private function getExtension(LoaderInterface $loader): MetadataExtension
    {
        return new MetadataExtension($loader);
    }
}
