<?php

namespace Avocode\FormExtensionsBundle\Form\EventListener;

use Avocode\FormExtensionsBundle\Form\Model\UploadCollectionFileInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Avocode\FormExtensionsBundle\Storage\FileStorageInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 * @author Escandell Stéphane <stephane.escandell@gmail.com>
 */
class CollectionUploadSubscriber implements EventSubscriberInterface
{
    /**
     * @var string Name of property holding collection
     */
    protected $propertyName;

    /**
     * @var string Collection data class
     */
    protected $dataClass;

    /**
     * @var boolean Primary Key field
     */
    protected $primary_key;

    /**
     * @var boolean Is file nameable
     */
    protected $nameable;

    /**
     * @var string Nameable field name
     */
    protected $nameable_field;

    /**
     * Used to revert changes if form is not valid.
     * @var Doctrine\Common\Collections\Collection Original collection
     */
    protected $originalFiles;

    /**
     * @var array Captured upload
     */
    protected $uploads = array();

    /**
     * @var array
     */
    protected $additionalContentUploads = array();

    /**
     * @var boolean
     */
    protected $editable = array();

   /**
    * @var array Submitted primary keys
    */
    protected $submitted_pk = array();

    /**
     * @var boolean
     */
    protected $allow_add;

    /**
     * @var boolean
     */
    protected $allow_delete;

    /**
     * @var FileStorageInterface
     */
    protected $storage;

    /**
     * Default constructor
     *
     * @param string $propertyName
     * @param array $options
     */
    public function __construct($propertyName, array $options, FileStorageInterface $storage = null)
    {
        $this->propertyName     = $propertyName;
        $this->dataClass        = $options['options']['data_class'];
        $this->primary_key      = $options['primary_key'];
        $this->nameable         = $options['nameable'];
        $this->nameable_field   = $options['nameable_field'];
        $this->editable         = $options['editable'];
        $this->allow_add        = $options['allow_add'];
        $this->allow_delete     = $options['allow_delete'];
        $this->storage          = $storage;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => array('preSubmit', 127),
            FormEvents::SUBMIT => array('onSubmit', 0),
            FormEvents::POST_SUBMIT => array('postSubmit', 0),
        );
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $data = $data ?: array();
        if (array_key_exists('uploads', $data)) {
            // capture uploads and store them for onSubmit event
            $this->uploads = $data['uploads'];
            // unset additional form data to prevent errors
            unset($data['uploads']);

            if ($this->nameable || $this->editable) {
                // Handle additional uploaded data on AsyncUpload
                foreach ($this->uploads as $uploadKey) {
                    if (array_key_exists($uploadKey, $data)) {
                        $this->additionalContentUploads[$uploadKey] = $data[$uploadKey];
                        unset($data[$uploadKey]);
                    }
                }
            }
        }

        if (array_key_exists('delete_uploads', $data)) {
            foreach ($data['delete_uploads'] as $fileId) {
                $file = $this->storage->getFile($fileId);

                if (!is_null($file)) {
                    unlink($file);
                }
            }

            unset($data['delete_uploads']);
        }

        // save submitted primary keys for onSubmit event
        foreach ($data as $file) {
            $this->submitted_pk[] = $file[$this->primary_key];
        }

        $event->setData($data);
    }

    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $form->getParent()->getData();

        // save original files collection for postSubmit event
        // TODO: use PropertyPath
        $getter = 'get'.ucfirst($this->propertyName);
        $this->originalFiles = $data->$getter();

        if ($this->allow_delete) {
            // remove files not present in submitted pk
            $pkGetter = 'get'.ucfirst($this->primary_key);
            $keysToRemove = array();
            $dataFiles = $data->$getter();
            foreach ($dataFiles as $key => $file) {
                if (!in_array($file->$pkGetter(), $this->submitted_pk)) {
                    $keysToRemove[] = $key;
                }
            }

            foreach($keysToRemove as $key) {
                $dataFiles->remove($key);
            }
        }

        if ($this->allow_add) {
            // create file entites for each file
            foreach ($this->uploads as $upload) {
                $loadAdditionalContentUid = null;
                if (!is_object($upload) && !is_null($this->storage)) {
                    $loadAdditionalContentUid = $upload;
                    $upload = $this->storage->getFile($upload);
                }

                if ($upload === null) {
                    continue;
                }

                $fileOwner = new $this->dataClass();
                if (!$fileOwner instanceof UploadCollectionFileInterface) {
                    throw new UnexpectedTypeException($fileOwner, '\Avocode\FormExtensionsBundle\Form\Model\UploadCollectionFileInterface');
                }

                $fileOwner->setFile($upload);
                $fileOwner->setParent($data);

                // if nameable field specified - set normalized name
                if ($this->nameable && $this->nameable_field) {
                    // TODO: use PropertyPath
                    $setNameable = 'set'.ucfirst($this->nameable_field);

                    // this value is unsafe
                    $name = $upload->getClientOriginalName();
                    // normalize string
                    $safeName = $this->normalizeUtf8String($name);

                    $fileOwner->$setNameable($safeName);
                }

                $this->loadAdditionalContent($loadAdditionalContentUid, $fileOwner);

                $data->$getter()->add($fileOwner);
            }
        }

        $event->setData($data->$getter());
    }

    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $form->getParent()->getData();

        $getter = 'get'.ucfirst($this->propertyName);
        if (!$form->isValid() && $data->$getter() instanceof ArrayCollection) {
            // remove files absent in the original collection
            $data->$getter()->clear();

            foreach ($this->originalFiles as $file) {
                $data->$getter()->add($file);
            }

            $event->setData($data->$getter());
        }
    }

    /**
     * @param string $fileUid
     * @param $fileOwner
     */
    private function loadAdditionalContent($fileUid, $fileOwner)
    {
        if (is_null($fileUid)) {
            return;
        }

        if (!$this->editable) {
            return;
        }

        if (!array_key_exists($fileUid, $this->additionalContentUploads)) {
            return;
        }

        foreach ($this->editable as $editable) {
            if (array_key_exists($editable, $this->additionalContentUploads[$fileUid])) {
                // TODO: use PropertyPath
                $setter = 'set'.ucfirst($editable);
                $fileOwner->$setter($this->additionalContentUploads[$fileUid][$editable]);
            }
        }
    }

    private function normalizeUtf8String($s)
    {
        // save original string
        $original_string = $s;

        // Normalizer-class missing!
        if (!class_exists('\Normalizer')) {
            // remove all non-whitelisted characters
            return  preg_replace( '@[^a-zA-Z0-9._\-\s ]@u' , "", $original_string );
        }

        $normalizer = new \Normalizer();

        // maps German (umlauts) and other European characters onto two characters before just removing diacritics
        $s = preg_replace('@\x{00c4}@u', "AE", $s);    // umlaut Ä => AE
        $s = preg_replace('@\x{00d6}@u', "OE", $s);    // umlaut Ö => OE
        $s = preg_replace('@\x{00dc}@u', "UE", $s);    // umlaut Ü => UE
        $s = preg_replace('@\x{00e4}@u', "ae", $s);    // umlaut ä => ae
        $s = preg_replace('@\x{00f6}@u', "oe", $s);    // umlaut ö => oe
        $s = preg_replace('@\x{00fc}@u', "ue", $s);    // umlaut ü => ue
        $s = preg_replace('@\x{00f1}@u', "ny", $s);    // ñ => ny
        $s = preg_replace('@\x{00ff}@u', "yu", $s);    // ÿ => yu

        // maps special characters (characters with diacritics) on their base-character followed by the diacritical mark
        // exmaple:  Ú => U´,  á => a`
        $s = $normalizer->normalize($s, $normalizer::FORM_D);

        $s = preg_replace('@\pM@u', "", $s);    // removes diacritics

        $s = preg_replace('@\x{00df}@u', "ss", $s);    // maps German ß onto ss
        $s = preg_replace('@\x{00c6}@u', "AE", $s);    // Æ => AE
        $s = preg_replace('@\x{00e6}@u', "ae", $s);    // æ => ae
        $s = preg_replace('@\x{0132}@u', "IJ", $s);    // ? => IJ
        $s = preg_replace('@\x{0133}@u', "ij", $s);    // ? => ij
        $s = preg_replace('@\x{0152}@u', "OE", $s);    // Œ => OE
        $s = preg_replace('@\x{0153}@u', "oe", $s);    // œ => oe

        $s = preg_replace('@\x{00d0}@u', "D", $s);    // Ð => D
        $s = preg_replace('@\x{0110}@u', "D", $s);    // Ð => D
        $s = preg_replace('@\x{00f0}@u', "d", $s);    // ð => d
        $s = preg_replace('@\x{0111}@u', "d", $s);    // d => d
        $s = preg_replace('@\x{0126}@u', "H", $s);    // H => H
        $s = preg_replace('@\x{0127}@u', "h", $s);    // h => h
        $s = preg_replace('@\x{0131}@u', "i", $s);    // i => i
        $s = preg_replace('@\x{0138}@u', "k", $s);    // ? => k
        $s = preg_replace('@\x{013f}@u', "L", $s);    // ? => L
        $s = preg_replace('@\x{0141}@u', "L", $s);    // L => L
        $s = preg_replace('@\x{0140}@u', "l", $s);    // ? => l
        $s = preg_replace('@\x{0142}@u', "l", $s);    // l => l
        $s = preg_replace('@\x{014a}@u', "N", $s);    // ? => N
        $s = preg_replace('@\x{0149}@u', "n", $s);    // ? => n
        $s = preg_replace('@\x{014b}@u', "n", $s);    // ? => n
        $s = preg_replace('@\x{00d8}@u', "O", $s);    // Ø => O
        $s = preg_replace('@\x{00f8}@u', "o", $s);    // ø => o
        $s = preg_replace('@\x{017f}@u', "s", $s);    // ? => s
        $s = preg_replace('@\x{00de}@u', "T", $s);    // Þ => T
        $s = preg_replace('@\x{0166}@u', "T", $s);    // T => T
        $s = preg_replace('@\x{00fe}@u', "t", $s);    // þ => t
        $s = preg_replace('@\x{0167}@u', "t", $s);    // t => t

        // remove all non-ASCii characters
        $s = preg_replace('@[^\0-\x80]@u', "", $s);

        // possible errors in UTF8-regular-expressions
        if (empty($s)) {
            // remove all non-whitelisted characters
            return  preg_replace('@[^a-zA-Z0-9._\-\s ]@u' , "", $original_string);
        }

        // return normalized string
        return $s;
    }
}
