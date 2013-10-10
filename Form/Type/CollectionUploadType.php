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
                'acceptFileTypes'           => $options['acceptFileTypes'],
                'autoStart'                 => $options['autoStart'],
                'editable'                  => $options['editable'],
                'loadImageFileTypes'        => $options['loadImageFileTypes'],
                'loadImageMaxFileSize'      => $options['loadImageMaxFileSize'],
                'maxNumberOfFiles'          => $options['maxNumberOfFiles'],
                'maxFileSize'               => $options['maxFileSize'],
                'minFileSize'               => $options['minFileSize'],
                'multipart'                 => $options['multipart'],
                'multiple'                  => $options['multiple'],
                'nameable'                  => $options['nameable'],
                'nameable_field'            => $options['nameable_field'],
                'novalidate'                => $options['novalidate'],
                'prependFiles'              => $options['prependFiles'],
                'previewFilter'             => $options['previewFilter'],
                'previewAsCanvas'           => $options['previewAsCanvas'],
                'previewMaxHeight'          => $options['previewMaxHeight'],
                'previewMaxWidth'           => $options['previewMaxWidth'],
                'primary_key'               => $options['primary_key'],
                'required'                  => $options['required'],
                'sortable'                  => $options['sortable'],
                'sortable_field'            => $options['sortable_field'],
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
            'acceptFileTypes'           => '/.*$/i',
            'autoStart'                 => true,
            'editable'                  => array(),
            'loadImageFileTypes'        => '/^image\/(gif|jpe?g|png)$/i',
            'loadImageMaxFileSize'      => 5000000,
            'maxNumberOfFiles'          => null,
            'maxFileSize'               => null,
            'minFileSize'               => null,
            'multipart'                 => true,
            'multiple'                  => true,
            'nameable'                  => true,
            'nameable_field'            => 'name',
            'novalidate'                => true,
            'prependFiles'              => false,
            'previewAsCanvas'           => true,
            'previewFilter'             => null,
            'previewMaxHeight'          => 80,
            'previewMaxWidth'           => 80,
            'primary_key'               => 'id',
            'required'                  => false,
            'sortable'                  => false,
            'sortable_field'            => 'position',
        ));

        // This seems weird... why to we accept it as option if we force
        // its value?
        $resolver->setAllowedValues(array(
            'novalidate'  => array(true),
            'multipart'   => array(true),
            'multiple'    => array(true),
            'required'    => array(false),
        ));

        $resolver->setAllowedTypes(array(
            'acceptFileTypes'           => array('string'),
            'autoStart'                 => array('bool'),
            'editable'                  => array('array'),
            'loadImageFileTypes'        => array('string'),
            'loadImageMaxFileSize'      => array('integer'),
            'maxNumberOfFiles'          => array('integer', 'null'),
            'maxFileSize'               => array('integer', 'null'),
            'minFileSize'               => array('integer', 'null'),
            'multipart'                 => array('bool'),
            'multiple'                  => array('bool'),
            'nameable'                  => array('bool'),
            'nameable_field'            => array('string', 'null'),
            'novalidate'                => array('bool'),
            'prependFiles'              => array('bool'),
            'previewAsCanvas'           => array('bool'),
            'previewFilter'             => array('string', 'null'),
            'previewMaxWidth'           => array('integer'),
            'previewMaxHeight'          => array('integer'),
            'primary_key'               => array('string'),
            'required'                  => array('bool'),
            'sortable'                  => array('bool'),
            'sortable_field'            => array('string'),
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
