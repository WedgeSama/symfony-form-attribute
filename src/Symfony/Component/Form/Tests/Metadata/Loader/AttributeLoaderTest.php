<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Tests\Metadata\Loader;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Attribute\AsFormType;
use Symfony\Component\Form\Attribute\Type;
use Symfony\Component\Form\Exception\MetadataException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Metadata\Loader\AttributeLoader;

/**
 * Class AttributeLoaderTest.
 *
 * @author Benjamin Georgeault
 */
class AttributeLoaderTest extends TestCase
{
    private AttributeLoader $loader;

    protected function setUp(): void
    {
        $this->loader = new AttributeLoader();
    }

    /**
     * @dataProvider supportProvider
     */
    public function testSupport(string $class, bool $expected)
    {
        $this->assertSame($expected, $this->loader->support($class));
    }

    public function supportProvider(): \Generator
    {
        yield 'Class with AsFormType attribute' => [Model::class, true];
        yield 'Class without AsFormType attribute' => [ModelNoForm::class, false];
    }

    /**
     * @dataProvider loadWithExceptionProvider
     */
    public function testLoadWithException(string $class, string $exceptionMessage)
    {
        $this->expectException(MetadataException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->loader->load($class);
    }

    public function loadWithExceptionProvider(): \Generator
    {
        yield 'Non existing class' => [
            ClassThatDoNotExistAtAll::class,
            'Class "Symfony\Component\Form\Tests\Metadata\Loader\ClassThatDoNotExistAtAll" does not exist.',
        ];

        yield 'Class missing AsFormType attribute' => [
            ModelNoForm::class,
            'The loader "Symfony\Component\Form\Metadata\Loader\AttributeLoader" cannot load metadata for class "Symfony\Component\Form\Tests\Metadata\Loader\ModelNoForm". There is no "Symfony\Component\Form\Attribute\AsFormType" attribute on it.',
        ];

        yield 'Non existing field type' => [
            ModelErrorField::class,
            'The given form type "Symfony\Component\Form\Tests\Metadata\Loader\ClassThatDoNotExistAtAll" does not exist for field "name" of class "Symfony\Component\Form\Tests\Metadata\Loader\ModelErrorField".',
        ];
    }

    public function testLoad()
    {
        $metadata = $this->loader->load(Model::class);

        $this->assertSame(Model::class, $metadata->getClassName());
        $this->assertSame(FormType::class, $metadata->getParent());
        $this->assertSame([
            'data_class' => Model::class,
        ], $metadata->getOptions());

        $fields = $metadata->getFields();
        $this->assertCount(4, $fields);

        $nameField = $fields['name'];
        $this->assertSame('name', $nameField->getName());
        $this->assertNull($nameField->getType());
        $this->assertEmpty($nameField->getOptions());

        $withOptions = $fields['withOptions'];
        $this->assertSame('withOptions', $withOptions->getName());
        $this->assertNull($withOptions->getType());
        $this->assertSame([
            'label' => 'value',
        ], $withOptions->getOptions());

        $description = $fields['description'];
        $this->assertSame('description', $description->getName());
        $this->assertSame(TextareaType::class, $description->getType());
        $this->assertEmpty($description->getOptions());

        $withTypeAndOptions = $fields['withTypeAndOptions'];
        $this->assertSame('withTypeAndOptions', $withTypeAndOptions->getName());
        $this->assertSame(TextareaType::class, $withTypeAndOptions->getType());
        $this->assertSame([
            'label' => 'value',
        ], $withTypeAndOptions->getOptions());
    }

    /**
     * @dataProvider inheritanceProvider
     */
    public function testInheritance(string $class, string $expectedParent, array $directFields)
    {
        $metadata = $this->loader->load($class);

        $this->assertSame($expectedParent, $metadata->getParent());
        $this->assertSame($directFields, array_keys($metadata->getFields()));
    }

    public function inheritanceProvider(): \Generator
    {
        yield 'No extends' => [Model::class, FormType::class, ['name', 'withOptions', 'description', 'withTypeAndOptions']];
        yield 'With extends' => [ModelChild::class, Model::class, ['anotherField']];
    }
}

#[AsFormType]
class Model
{
    #[Type]
    public string $name;

    #[Type(options: ['label' => 'value'])]
    public string $withOptions;

    #[Type(TextareaType::class)]
    public string $description;

    #[Type(TextareaType::class, ['label' => 'value'])]
    public string $withTypeAndOptions;
}

class ModelNoForm
{
}

#[AsFormType]
class ModelErrorField
{
    #[Type(ClassThatDoNotExistAtAll::class)]
    public string $name;
}

#[AsFormType]
class ModelChild extends Model
{
    #[Type]
    public string $anotherField;
}
