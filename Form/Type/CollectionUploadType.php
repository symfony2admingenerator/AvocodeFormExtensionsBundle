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
        $view->vars = array_merge(
            $view->vars,
            array(
                'primary_key'               => $options['primary_key'],
                'nameable'                  => $options['nameable'],
                'nameable_field'            => $options['nameable_field'],
                'sortable'                  => $options['sortable'],
                'sortable_field'            => $options['sortable_field'],
                'editable'                  => $options['editable'],
                'maxNumberOfFiles'          => $options['maxNumberOfFiles'],
                'acceptFileTypes'           => $options['acceptFileTypes'],
                'maxFileSize'               => $options['maxFileSize'],
                'minFileSize'               => $options['minFileSize'],
                'loadImageFileTypes'        => $options['loadImageFileTypes'],
                'loadImageMaxFileSize'      => $options['loadImageMaxFileSize'],
                'previewMaxWidth'           => $options['previewMaxWidth'],
                'previewMaxHeight'          => $options['previewMaxHeight'],
                'previewAsCanvas'           => $options['previewAsCanvas'],
                'previewFilter'             => $options['previewFilter'],
                'prependFiles'              => $options['prependFiles'],
                'novalidate'                => $options['novalidate'],
                'multipart'                 => $options['multipart'],
                'multiple'                  => $options['multiple'],
                'required'                  => $options['required'],
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'primary_key'               => 'id',
            'nameable'                  => true,
            'nameable_field'            => 'name',
            'sortable'                  => false,
            'sortable_field'            => 'position',
            'editable'                  => array(),
            'maxNumberOfFiles'          => null,
            'maxFileSize'               => null,
            'minFileSize'               => null,
            'acceptFileTypes'           => '/.*$/i',
            'loadImageFileTypes'        => '/^image\/(gif|jpe?g|png)$/i',
            'loadImageMaxFileSize'      => 5000000,
            'previewMaxWidth'           => 80,
            'previewMaxHeight'          => 80,
            'previewAsCanvas'           => true,
            'previewFilter'             => null,
            'prependFiles'              => false,
            'novalidate'                => true,
            'multipart'                 => true,
            'multiple'                  => true,
            'required'                  => false,
        ));

        $resolver->setAllowedValues(array(
            'novalidate'  => array(true),
            'multipart'   => array(true),
            'multiple'    => array(true),
            'required'    => array(false),
        ));

        $resolver->setAllowedTypes(array(
            'primary_key'               =>  array('string'),
            'nameable'                  =>  array('bool'),
            'nameable_field'            =>  array('string', 'null'),
            'sortable'                  =>  array('bool'),
            'sortable_field'            =>  array('string'),
            'editable'                  =>  array('array'),
            'maxNumberOfFiles'          =>  array('integer', 'null'),
            'maxFileSize'               =>  array('integer', 'null'),
            'minFileSize'               =>  array('integer', 'null'),
            'acceptFileTypes'           =>  array('string'),
            'loadImageFileTypes'        =>  array('string'),
            'loadImageMaxFileSize'      =>  array('integer'),
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
