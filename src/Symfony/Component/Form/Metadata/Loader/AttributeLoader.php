<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Metadata\Loader;

use Symfony\Component\Form\Attribute\AsFormType;
use Symfony\Component\Form\Attribute\Type;
use Symfony\Component\Form\Exception\MetadataException;
use Symfony\Component\Form\Metadata\FieldMetadata;
use Symfony\Component\Form\Metadata\FormMetadata;
use Symfony\Component\Form\Metadata\FormMetadataInterface;

/**
 * Load a {@see FormMetadata} from a class where an attribute {@see AsFormType} is applied.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
final readonly class AttributeLoader implements LoaderInterface
{
    public function load(string $class): FormMetadataInterface
    {
        if (!class_exists($class)) {
            throw new MetadataException(\sprintf('Class "%s" does not exist.', $class));
        }

        $reflectionClass = new \ReflectionClass($class);
        if (null === $asFormType = $this->getOneAttributeInstance(AsFormType::class, $reflectionClass)) {
            throw new MetadataException(\sprintf('The loader "%s" cannot load metadata for class "%s". There is no "%s" attribute on it.', self::class, $class, AsFormType::class));
        }

        $fields = [];
        foreach ($this->getFields($reflectionClass) as $name => $typeAttribute) {
            if (null !== $typeAttribute->getType() && !class_exists($typeAttribute->getType())) {
                throw new MetadataException(\sprintf('The given form type "%s" does not exist for field "%s" of class "%s".', $typeAttribute->getType(), $name, $class));
            }

            $fields[$name] = new FieldMetadata($name, $typeAttribute->getType(), $typeAttribute->getOptions());
        }

        return new FormMetadata(
            $class,
            $this->closestAsFormTypeParent($reflectionClass),
            $fields,
            $asFormType->getOptions(),
        );
    }

    public function support(string $class): bool
    {
        return class_exists($class) && $this->isAsFormType(new \ReflectionClass($class));
    }

    /**
     * @return iterable<string, Type>
     */
    private function getFields(\ReflectionClass $reflectionClass): iterable
    {
        foreach ($reflectionClass->getProperties() as $propRef) {
            if (
                $reflectionClass->getName() !== $propRef->getDeclaringClass()->getName()
                || null === $typeAttribute = $this->getOneAttributeInstance(Type::class, $propRef)
            ) {
                continue;
            }

            yield $propRef->getName() => $typeAttribute;
        }
    }

    /**
     * @template T as object
     *
     * @param class-string<T> $attributeClass
     *
     * @return T
     */
    private function getOneAttributeInstance(string $attributeClass, \ReflectionProperty|\ReflectionClass $ref): ?object
    {
        foreach ($this->getAttributeInstances($attributeClass, $ref) as $attrInstance) {
            return $attrInstance;
        }

        return null;
    }

    /**
     * @template T as object
     *
     * @param class-string<T> $attributeClass
     *
     * @return iterable<T>
     */
    private function getAttributeInstances(string $attributeClass, \ReflectionProperty|\ReflectionClass $ref): iterable
    {
        foreach ($ref->getAttributes() as $attrRef) {
            if (is_a($attrRef->getName(), $attributeClass, true)) {
                yield $attrRef->newInstance();
            }
        }
    }

    private function closestAsFormTypeParent(\ReflectionClass $reflectionClass): ?string
    {
        while ($parent = $reflectionClass->getParentClass()) {
            if ($this->isAsFormType($parent)) {
                return $parent->getName();
            }

            $reflectionClass = $parent;
        }

        return null;
    }

    private function isAsFormType(\ReflectionClass $reflectionClass): bool
    {
        foreach ($reflectionClass->getAttributes() as $attrRef) {
            if (is_a($attrRef->getName(), AsFormType::class, true)) {
                return true;
            }
        }

        return false;
    }
}
