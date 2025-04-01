<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Metadata\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Metadata\FormMetadataInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @internal
 *
 * @author Benjamin Georgeault <git@wedgesama.fr>
 */
final readonly class MetadataType implements FormTypeInterface
{
    public function __construct(
        private FormMetadataInterface $metadata,
    ) {
    }

    public function getParent(): string
    {
        return $this->metadata->getParent();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults($this->metadata->getOptions());
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->metadata->getFields() as $fieldMetadata) {
            $builder->add($fieldMetadata->getName(), $fieldMetadata->getType(), $fieldMetadata->getOptions());
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['form_metadata'] = $this->metadata;
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
    }

    public function getBlockPrefix(): string
    {
        return $this->metadata->getBlockPrefix();
    }

    public function getClassName(): string
    {
        return $this->metadata->getClassName();
    }
}
