<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Form\Extension\Metadata\MetadataExtension;
use Symfony\Component\Form\Metadata\Loader\AttributeLoader;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('form.metadata_extension', MetadataExtension::class)
            ->args([service('form.metadata.default_loader')])

        ->set('form.metadata.attribute_loader', AttributeLoader::class)

        ->alias('form.metadata.default_loader', 'form.metadata.attribute_loader')
    ;
};
