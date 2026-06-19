<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Tests\Extension\Metadata\Type;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Metadata\Type\MetadataType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Metadata\FieldMetadataInterface;
use Symfony\Component\Form\Metadata\FormMetadataInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetadataTypeTest extends TestCase
{
    private FormMetadataInterface $metadata;

    protected function setUp(): void
    {
        $this->metadata = new class implements FormMetadataInterface {
            public function getClassName(): string
            {
                return 'ClassName';
            }

            public function getParent(): string
            {
                return 'Parent';
            }

            public function getBlockPrefix(): string
            {
                return 'block_prefix';
            }

            public function getOptions(): array
            {
                return [
                    'label' => 'Foo',
                ];
            }

            public function getFields(): array
            {
                return [
                    'foo' => new class implements FieldMetadataInterface {
                        public function getName(): string
                        {
                            return 'foo';
                        }

                        public function getType(): ?string
                        {
                            return null;
                        }

                        public function getOptions(): array
                        {
                            return [
                                'label' => 'Foo',
                            ];
                        }
                    },
                ];
            }
        };
    }

    public function testGetParent()
    {
        $this->assertEquals('Parent', (new MetadataType($this->metadata))->getParent());
    }

    public function testConfigureOptions()
    {
        $resolver = new OptionsResolver();
        (new MetadataType($this->metadata))->configureOptions($resolver);

        $this->assertSame(['label' => 'Foo'], $resolver->resolve());
    }

    public function testBuildForm()
    {
        ($builder = $this->createMock(FormBuilderInterface::class))
            ->expects($this->once())
            ->method('add')
            ->with('foo', null, [
                'label' => 'Foo',
            ]);

        (new MetadataType($this->metadata))->buildForm($builder, []);
    }

    public function testGetBlockPrefix()
    {
        $this->assertEquals('block_prefix', (new MetadataType($this->metadata))->getBlockPrefix());
    }

    public function testClassName()
    {
        $this->assertEquals('ClassName', (new MetadataType($this->metadata))->getClassName());
    }
}
