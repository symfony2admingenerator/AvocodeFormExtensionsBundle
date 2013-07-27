<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Avocode\FormExtensionsBundle\Form\EventListener\CollectionUploadSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * See `Resources/doc/collection-upload/overview.md` for documentation
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class CollectionUploadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new CollectionUploadSubscriber(
            $builder->getName(), 
            $options
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['multipart']                = true;
        $view->vars['primary_key']              = $options['primary_key'];
        $view->vars['nameable']                 = $options['nameable'];
        $view->vars['nameable_field']           = $options['nameable_field'];
        $view->vars['sortable']                 = $options['sortable'];
        $view->vars['sortable_field']           = $options['sortable_field'];
        $view->vars['editable']                 = $options['editable'];
        $view->vars['maxNumberOfFiles']         = $options['maxNumberOfFiles'];
        $view->vars['maxFileSize']              = $options['maxFileSize'];
        $view->vars['minFileSize']              = $options['minFileSize'];
        $view->vars['acceptFileTypes']          = $options['acceptFileTypes'];
        $view->vars['previewSourceFileTypes']   = $options['previewSourceFileTypes'];
        $view->vars['previewSourceMaxFileSize'] = $options['previewSourceMaxFileSize'];
        $view->vars['previewMaxWidth']          = $options['previewMaxWidth'];
        $view->vars['previewMaxHeight']         = $options['previewMaxHeight'];
        $view->vars['previewAsCanvas']          = $options['previewAsCanvas'];
        $view->vars['previewFilter']            = $options['previewFilter'];
        $view->vars['prependFiles']             = $options['prependFiles'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'primary_key'               =>  'id',
            'nameable'                  =>  true,
            'nameable_field'            =>  'name',
            'sortable'                  =>  false,
            'sortable_field'            =>  'position',
            'editable'                  =>  array(),
            'maxNumberOfFiles'          =>  null,
            'maxFileSize'               =>  null,
            'minFileSize'               =>  null,
            'acceptFileTypes'           =>  '/.*$/i',
            'previewSourceFileTypes'    =>  '/^image\/(gif|jpeg|png)$/',
            'previewSourceMaxFileSize'  =>  5000000,
            'previewMaxWidth'           =>  80,
            'previewMaxHeight'          =>  80,
            'previewAsCanvas'           =>  true,
            'previewFilter'             =>  null,
            'prependFiles'              =>  false,
        ));

        $resolver->setAllowedTypes(array(
            'primary_key'               =>  array('string'),
            'nameable'                  =>  array('bool'),
            'nameable_field'            =>  array('string'),
            'sortable'                  =>  array('bool'),
            'sortable_field'            =>  array('string'),
            'editable'                  =>  array('array'),
            'maxNumberOfFiles'          =>  array('integer', 'null'),
            'maxFileSize'               =>  array('integer', 'null'),
            'minFileSize'               =>  array('integer', 'null'),
            'acceptFileTypes'           =>  array('string'),
            'previewSourceFileTypes'    =>  array('string'),
            'previewSourceMaxFileSize'  =>  array('integer'),
            'previewMaxWidth'           =>  array('integer'),
            'previewMaxHeight'          =>  array('integer'),
            'previewAsCanvas'           =>  array('bool'),
            'previewFilter'             =>  array('string', 'null'),
            'prependFiles'              =>  array('bool'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'collection_upload';
    }
}
