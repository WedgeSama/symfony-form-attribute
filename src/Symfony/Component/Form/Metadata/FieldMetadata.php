<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Metadata;

/**
 * Represent metadata for a FormType's field.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
final readonly class FieldMetadata implements FieldMetadataInterface
{
    /**
     * @param class-string|null    $type
     * @param array<string, mixed> $options
     */
    public function __construct(
        private string $name,
        private ?string $type,
        private array $options,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return class-string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
