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

use Symfony\Component\Form\Exception\MetadataException;
use Symfony\Component\Form\Metadata\FormMetadataInterface;

/**
 * Implement this interface to create a loader to create {@see FormMetadataInterface} from a class.
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
interface LoaderInterface
{
    /**
     * @param class-string $class
     *
     * @throws MetadataException if metadata cannot be loaded
     */
    public function load(string $class): FormMetadataInterface;

    /**
     * @param class-string $class
     */
    public function support(string $class): bool;
}
