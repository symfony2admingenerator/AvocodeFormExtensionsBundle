# Collection Upload
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[symfony-collectiontype]: http://symfony.com/doc/current/reference/forms/types/collection.html
[symfony-filetype]: http://symfony.com/doc/current/reference/forms/types/file.html

### 1. Overview

Collection Upload adds twitter-boostrap GUI wrapper for a [collection][symfony-collectiontype]
of entities associated with files. Entities hold a [FileType][symfony-filetype] field and must
implement `Avocode\FormExtensionsBundle\Form\Model\UploadCollectionFileInterface`.

#### Features:

* Drag & drop support
* Copy & paste support
* Multiple file selection
* Display selected files in a table
* Display selected images thumbnails
* Display selected files size (browsers with HTML5 File API support only)
* Display uploaded files in a table
* Display uploaded images thumbnails
* Sort uploaded files
* Edit additional object fields (like name, description, etc)

### 2. Asset provider

To use this form type you must choose one of the following methods:

#### Useing VichUploaderBundle

Install [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle) and 
configure it's mappings:

```yaml
vich_uploader:
    db_driver:  orm
    gaufrette:  false
    storage:    vich_uploader.storage.file_system
    mappings:
        gallery_image:
            uri_prefix:           /gallery/image
            upload_destination:   %kernel.root_dir%/../web/gallery/image
            namer:                vich_uploader.namer_uniqid
            inject_on_load:       true
            delete_on_remove:     true
            delete_on_update:     true
```

Add Vich mappings to your entity, implement `UploadCollectionFileInterface`, and 
add additional fields. 

> **Note:** This example also shows how to use [Gedmo Sortable]
(https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/sortable.md) for 
sortable behaviour on Doctrine ORM. If you're useing diffrent ORM just remove 
Gedmo annotations. The sortable behaviour of this form type simply updates a hidden 
field with a `0-indexed` position key.

```php
<?php
// Acme\DemoBundle\Entity\GalleryImage.php

namespace Acme\DemoBundle\Entity;

use Avocode\FormExtensionsBundle\Form\Model\UploadCollectionFileInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @Vich\Uploadable
 */
class GalleryImage implements UploadCollectionFileInterface
{
    /**
     * @Vich\UploadableField(mapping="gallery_image", fileNameProperty="path")
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;
    
    /**
     * (Optional) nameable field
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;
    
    /**
     * (Optional) additional editable field
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;
    
    /**
     * (Optional) sortable group
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="images")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */
    protected $album;

    /**
     * (Optional) sortable position
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;
}
```

> **Note:** In this example we use Doctrine ORM, replace it's mappings with your ORM.

To use VichUploaderBundle you have to configure the `upload_manager` option. Read more in
cookbook entry [How to use upload manager with this bundle?]
(https://github.com/avocode/FormExtensions/blob/master/Resources/doc/cookbook/upload-manager.md) 

#### Without dependencies

Follow symfony's cookbook on [How to handle File Uploads with Doctrine]
(http://symfony.com/doc/current/cookbook/doctrine/file_uploads.html) and apply 
following changes to your entity:

```php
<?php
// Acme\DemoBundle\Entity\GalleryImage.php

namespace Acme\DemoBundle\Entity;

use Avocode\FormExtensionsBundle\Form\Model\UploadCollectionFileInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class GalleryImage implements UploadCollectionFileInterface
{
    protected $file;

    // other fields like path, name, description, album, position
    // stay the same as in "Useing VichUploader" example

    public function getFile()
    {
        // inject file into property (if uploaded)
        if ($this->getAbsolutePath()) {
            return new \Symfony\Component\HttpFoundation\File\File(
                $this->getAbsolutePath()
            );
        }

        return null;
    }

    // see note below
    public function getFileWebPath()
    {
        return $this->getWebPath();
    }
}
```

> **Note:** The name the second method must be `get FIELD WebPath`, in this example 
our field name is `file` so method name is `getFileWebPath`.

### 3. Configuration

Admingenerator basic configuration:

```yaml
images:
    label:            Images
    dbType:           collection
    formType:         upload
    addFormOptions:
        nameable:         name
        sortable:         position
        editable:         [ description ]
        ### you can create your own form type
        # type:             \Acme\DemoBundle\Form\MyFormType
        ### or use admin:generate-admin command and use the admingenerated form type
        # type:             \Acme\DemoBundle\Form\Type\Image\EditType
        maxNumberOfFiles:           5
        maxFileSize:                500000
        minFileSize:                1000
        acceptFileTypes:            /(\.|\/)(gif|jpe?g|png)$/i
        previewSourceFileTypes:     /^image\/(gif|jpeg|png)$/
        previewSourceMaxFileSize:   250000
        previewMaxWidth:            100
        previewMaxHeight:           100
        previewAsCanvas:            true
        prependFiles:               false
        allow_add:        true
        allow_delete:     true
        error_bubbling:   false
        options:
            data_class:     Acme\DemoBundle\Entity\GalleryImage
```

### 4. Implementing functions
  
Your object must implement `UploadCollectionFileInterface` functions:
  
*  `public function getSize()`
*  `public function setParent($parent)`
*  `public function setFile(\Symfony\Component\HttpFoundation\File\File $file)`
*  `public function getFile()`
*  `public function getPreview()`

#### Useing VichUploaderBundle
  
```php
<?php
// ...

    /**
     * Set file
     *
     * @param Symfony\Component\HttpFoundation\File\File $file
     * @return GalleryImage
     */
    public function setFile(\Symfony\Component\HttpFoundation\File\File $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return Symfony\Component\HttpFoundation\File\File 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->file->getFileInfo()->getSize();
    }

    /**
     * @inheritDoc
     */
    public function setParent($parent)
    {
        $this->setAlbum($parent);
    }

    /**
     * @inheritDoc
     */
    public function getPreview()
    {
        return (preg_match('/image\/.*/i', $this->file->getMimeType()));
    }
```  

#### Without dependencies

The only diffrence is in `getFile` method which must inject the file and 
other methods cannot direcly access the `file` property, becouse it would be `null`.
  
```php
<?php
// ...
    /**
     * Get file
     *
     * @return Symfony\Component\HttpFoundation\File\File 
     */
    public function getFile()
    {
        // inject file into property (if uploaded)
        if ($this->getAbsolutePath()) {
            return new \Symfony\Component\HttpFoundation\File\File(
                $this->getAbsolutePath()
            );
        }

        return null;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->getFile()->getFileInfo()->getSize();
    }

    /**
     * @inheritDoc
     */
    public function getPreview()
    {
        return (preg_match('/image\/.*/i', $this->getFile()->getMimeType()));
    }
```  

### 5. Options

#### nameable

**type:** `string` **default:** `null`

If specified, this field is used to store normalized filenames.

#### sortable

**type:** `string` **default:** `null`

If specified, enables sortable behavior and uses this field to store position.

#### editable

**type:** `array` **default:** `[]`

List of additional fields to be rendered. 

> **Note:** Remember to add your fields to your form type!

#### maxNumberOfFiles

**type:** `integer` **default:** `undefined`

This option limits the number of files that are allowed to be uploaded using 
this widget. By default, unlimited file uploads are allowed.

> **Note:** The **maxNumberOfFiles** setting acts like an internal counter 
and will adjust automatically when files are added or removed. That is, if 
you set it to **2** and add one file, it will be decreased to **1**. If you 
remove one file from list, it will be increased again.

#### maxFileSize

**type:** `integer` **default:** `undefined`

The maximum allowed file size in bytes, by default unlimited.

> **Note:** This option has only an effect for browsers supporting the 
[File API](https://developer.mozilla.org/en/DOM/file).

#### minFileSize

**type:** `integer` **default:** `undefined`

The minimum allowed file size, by default undefined (can be 0 bytes).

> **Note:** This option has only an effect for browsers supporting the 
[File API](https://developer.mozilla.org/en/DOM/file).

#### acceptFileTypes

**type:** `Regular Expression` **default:** `undefined`

If defined the regular expression for allowed file types is matched against 
either file type or file name as only browsers with support for the File API 
report the file type.

#### previewSourceFileTypes

**type:** `Regular Expression` **default:** `/^image\/(gif|jpeg|png)$/`

The regular expression to define for which files a preview image is shown, 
matched against the file type.

> **Note:** Preview images are only displayed for browsers supporting 
the *URL* or *webkitURL* APIs or the *readAsDataURL* method of the 
[FileReader](https://developer.mozilla.org/en/DOM/FileReader) interface.

#### previewSourceMaxFileSize

**type:** `integer` **default:** `5000000`

The maximum file size for preview images in bytes.

#### previewMaxWidth

**type:** `integer` **default:** `80`

The maximum width of the preview images.

#### previewMaxHeight

**type:** `integer` **default:** `80`

The maximum height of the preview images.

#### previewAsCanvas

**type:** `boolean` **default:** `true`

If true and supported by the browser, selected images will be previewed as 
[canvas](https://developer.mozilla.org/en/HTML/canvas) elements, otherwise 
*img* elements will be used.

#### previewFilter

**type:** `string` **default:** `null`

If set, given filter will be applied on uploaded file to generate a thumbnail. 

> **Note:** Requires instaling and configureing image manipulator. See 
[How to use image manipulator with this bundle?]
(https://github.com/avocode/FormExtensions/blob/master/Resources/doc/cookbook/image-manipulator.md) 
cookbook entry.

#### prependFiles

**type:** `boolean` **default:** `false`

By default, files are appended to the files container.
Set this option to true, to prepend files instead.

> **Important:** These options are just for User Interface and cannot be 
considered safe (javascript is client-side). Always validate form input 
data server-side!

### 6. Special thanks

Special thanks to [Sebastian Tschan](https://github.com/blueimp) for 
`jQuery-File-Upload` licensed under [MIT License]
(https://github.com/blueimp/jQuery-File-Upload#license).
