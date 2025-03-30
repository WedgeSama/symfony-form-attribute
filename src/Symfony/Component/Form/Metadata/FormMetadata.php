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

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Util\StringUtil;

/**
 * Represent metadata for a FormType.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
final readonly class FormMetadata implements FormMetadataInterface
{
    private array $options;

    /**
     * @param array<string, FieldMetadataInterface> $fields
     * @param array<string, mixed>                  $options
     */
    public function __construct(
        private string $className,
        private ?string $parent = null,
        private array $fields = [],
        array $options = [],
    ) {
        $this->options = [
            ...$options,
            'data_class' => $this->className,
        ];
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getParent(): string
    {
        return $this->parent ?? FormType::class;
    }

    public function getBlockPrefix(): string
    {
        return StringUtil::fqcnToBlockPrefix($this->getClassName()) ?: '';
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array<string, FieldMetadataInterface>
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
