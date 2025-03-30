<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Metadata\FormMetadata;

class FormMetadataTest extends TestCase
{
    public function testDefaultParent()
    {
        $this->assertSame(FormType::class, (new FormMetadata('Foo'))->getParent());
    }

    public function testDataClass()
    {
        $metadata = new FormMetadata('Foo');
        $this->assertSame('Foo', $metadata->getOptions()['data_class']);

        $metadata = new FormMetadata('Foo', null, [], [
            'data_class' => 'Bar',
        ]);
        $this->assertSame('Foo', $metadata->getOptions()['data_class']);
    }
}
