<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Extension\Metadata\Type;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Metadata\Type\MetadataType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Metadata\FieldMetadataInterface;
use Symfony\Component\Form\Metadata\FormMetadataInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetadataTypeTest extends TestCase
{
    public function testGetParent()
    {
        ($metadata = $this->createMock(FormMetadataInterface::class))
            ->expects($this->once())
            ->method('getParent')
            ->willReturn('Foo');

        $this->assertEquals('Foo', (new MetadataType($metadata))->getParent());
    }

    public function testConfigureOptions()
    {
        ($metadata = $this->createMock(FormMetadataInterface::class))
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([
                'label' => 'Foo',
            ]);

        ($resolver = $this->createMock(OptionsResolver::class))
            ->expects($this->once())
            ->method('setDefaults')
            ->with([
                'label' => 'Foo',
            ]);

        (new MetadataType($metadata))->configureOptions($resolver);
    }

    public function testBuildForm()
    {
        ($fieldMetadata = $this->createMock(FieldMetadataInterface::class))
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo');

        $fieldMetadata
            ->expects($this->once())
            ->method('getType')
            ->willReturn(null);

        $fieldMetadata
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([
                'label' => 'Foo',
            ]);

        ($metadata = $this->createMock(FormMetadataInterface::class))
            ->expects($this->once())
            ->method('getFields')
            ->willReturn([
                'foo' => $fieldMetadata,
            ]);

        ($builder = $this->createMock(FormBuilderInterface::class))
            ->expects($this->once())
            ->method('add')
            ->with('foo', null, [
                'label' => 'Foo',
            ]);

        (new MetadataType($metadata))->buildForm($builder, []);
    }

    public function testGetBlockPrefix()
    {
        ($metadata = $this->createMock(FormMetadataInterface::class))
            ->expects($this->once())
            ->method('getBlockPrefix')
            ->willReturn('Foo');

        $this->assertEquals('Foo', (new MetadataType($metadata))->getBlockPrefix());
    }
}
