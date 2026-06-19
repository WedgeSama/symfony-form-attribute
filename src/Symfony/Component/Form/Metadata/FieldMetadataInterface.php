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
 * Represent the contract of metadata for a FormType's field.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
interface FieldMetadataInterface
{
    public function getName(): string;

    /**
     * @return class-string|null
     */
    public function getType(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;
}
