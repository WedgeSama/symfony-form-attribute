<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form;

/**
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
interface MetadataFormTypeInterface extends FormTypeInterface
{
    /**
     * Returns the FQCN of the class representing the FormType.
     *
     * @return class-string
     */
    public function getClassName(): string;
}
