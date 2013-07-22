<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ColorType working with jQuery MiniColors 2.0 :
 *     http://labs.abeautifulsite.net/jquery-miniColors/
 *
 * @author Escandell Stéphane <stephane.escandell@gmail.com>
 */
class ColorPickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs']  = $options['configs'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // TODO: expose configuration?
        $resolver->setDefaults(array(
            'configs' => array(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'color_picker';
    }
}
