<?php

namespace Avocode\FormExtensionsBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class SingleUploadSubscriber implements EventSubscriberInterface
{
    /**
     * @var string Single upload field configs
     */
    protected $configs = array();

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => array('preSubmit', 0),
            FormEvents::SUBMIT => array('onSubmit', 0),
            FormEvents::POST_SUBMIT => array('postSubmit', 0),
        );
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $post = $event->getData();
        
        foreach ($form->all() as $child) {
            if ($child->getConfig()->getType()->getName() === 'single_upload') {
                $childPost = $post[$child->getName()];
                $options = $child->getConfig()->getOptions();
                
                $this->configs[$child->getName()] = array(
                    'nameable'   => $options['nameable'],
                    'deleteable' => $options['deleteable'],
                );

                if ($options['nameable'] && array_key_exists('name', $childPost)) {
                    // capture name and store it for onSubmit event
                    $this->configs[$child->getName()]['captured_name'] = $childPost['name'];
                }

                if ($options['deleteable'] && array_key_exists('delete', $childPost)) {
                    // capture name and store it for onSubmit event
                    $this->configs[$child->getName()]['delete'] = $childPost['delete'];
                }
                
                // remove additional data to prevent errors
                // by overwriting them with posted file
                $post[$child->getName()] = $childPost['file'];
            }
        
            $event->setData($post);
        }
    }

    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $form->getData();

        if ($data != null && count($this->configs) > 0) {
            foreach ($this->configs as $field => $config) {
                if ($config['nameable'] && array_key_exists('captured_name', $config)) {
                    $getterName = 'get'.ucfirst($config['nameable']);
                    $setterName = 'set'.ucfirst($config['nameable']);

                    // save original name for postSubmit event
                    $config['original_name'] = $data->$getterName();

                    // set captured name
                    $data->$setterName($config['captured_name']);
                }
                
                if ($config['deleteable'] && array_key_exists('delete', $config)) {
                    $getterPath = 'get'.ucfirst($config['deleteable']);
                    $setterPath = 'set'.ucfirst($config['deleteable']);

                    // save original file for postSubmit event
                    $config['original_path'] = $data->$getterPath();
                    
                    // remove file
                    $data->$setterPath(null);
                }
            }
            
            $event->setData($data);
        }
    }

    public function postSubmit(FormEvent $event)
    {
        if (count($this->configs) > 0) {
            $form = $event->getForm();
            $data = $event->getData();

            if (!$form->isValid()) {
                foreach ($this->configs as $field => $config) {
                    if ($config['nameable'] && array_key_exists('original_name', $config)) {
                        // revert to original name
                        $setterName = 'set'.ucfirst($config['nameable']);
                        $data->$setterName($config['original_name']);
                    }
                
                    if ($config['deleteable'] && array_key_exists('original_path', $config)) {
                        $setterPath = 'set'.ucfirst($config['deleteable']);
                        // revert to original name
                        $data->$setterPath($config['original_path']);
                    }
                }

                $event->setData($data);
            }
        }
    }
}
