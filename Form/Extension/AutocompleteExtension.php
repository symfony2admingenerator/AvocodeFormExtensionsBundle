<?php

namespace Avocode\FormExtensionsBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author havvg <tuebernickel@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class AutocompleteExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // It doesn't hurt even if it will be left empty.
        if (empty($view->vars['attr'])) {
            $view->vars['attr'] = array();
        }

        if (false === $options['autocomplete']) {
            $view->vars['attr'] = array_merge($view->vars['attr'], array(
                'autocomplete'        => 'off',
                'x-autocompletetype'  => 'off',
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'autocomplete' => true,
        ));
        
        $resolver->setAllowedTypes(array(
            'autocomplete' => array('bool'),
        ));
    }

    public function getExtendedType()
    {
        return 'form';
    }
}
