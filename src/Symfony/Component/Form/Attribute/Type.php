<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Attribute;

/**
 * Add an AsFormType class property as a FormType's field.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Type
{
    /**
     * @param class-string|null    $type    the FormType class name to use for this field
     * @param array<string, mixed> $options your form options
     * @param string|null          $name    change the form view field's name
     */
    public function __construct(
        private ?string $type = null,
        private array $options = [],
        private ?string $name = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return class-string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
