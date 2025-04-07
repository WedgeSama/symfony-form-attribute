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
 * Register a model class (e.g. DTO, entity, model, etc...) as a FormType.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class AsFormType
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private array $options = [],
    ) {
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
