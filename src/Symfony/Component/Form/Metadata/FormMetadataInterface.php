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
 * Represent the contract of metadata for a FormType.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
interface FormMetadataInterface
{
    /**
     * @return class-string
     */
    public function getClassName(): string;

    public function getParent(): string;

    public function getBlockPrefix(): string;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * @return array<string, FieldMetadataInterface>
     */
    public function getFields(): array;
}
