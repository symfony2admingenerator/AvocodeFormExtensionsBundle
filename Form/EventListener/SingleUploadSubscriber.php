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
              
                $options = $child->getConfig()->getOptions();
                
                $this->configs[$child->getName()] = array(
                    'nameable'        =>  $options['nameable'],
                    'deleteable'      =>  $options['deleteable'],
                );

                if ($options['nameable'] && array_key_exists($child->getName().'_name', $post)) {
                    // capture name and store it for onSubmit event
                    $this->configs[$child->getName()]['captured_name'] = $post[$child->getName().'_name'];

                    // unset additional form data to prevent errors
                    unset($post[$child->getName().'_nameable']);
                }

                if ($options['deleteable'] && array_key_exists($child->getName().'_delete', $post)) {
                    // capture name and store it for onSubmit event
                    $this->configs[$child->getName()]['delete'] = $post[$child->getName().'_delete'];

                    // unset additional form data to prevent errors
                    unset($post[$child->getName().'_deleteable']);
                }
            }
        
            $event->setData($post);
        }
    }

    public function onSubmit(FormEvent $event)
    {
        if (count($this->configs) > 0) {
            $form = $event->getForm();
            $data = $form->getData();
            
            foreach ($this->configs as $field => $config) {
                if ($config['nameable']) {
                    $getterName = 'get'.ucfirst($config['nameable']);
                    $setterName = 'set'.ucfirst($config['nameable']);

                    // save original name for postSubmit event
                    $config['original_name'] = $data->$getterName();

                    // set captured name
                    $data->$setterName($config['captured_name']);
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
                    if ($config['nameable']) {
                        // revert to original name
                        $setter = 'set'.ucfirst($config['nameable']);
                        $data->$setter($config['original_name']);
                    }
                }

                $event->setData($data);
            }
        }
    }
}
