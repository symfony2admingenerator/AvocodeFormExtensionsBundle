# Single Upload
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[symfony-filetype]: http://symfony.com/doc/current/reference/forms/types/file.html

### Form Type

 `afe_single_upload`

### 1. Overview

Single Upload adds twitter-boostrap GUI wrapper for [FileType][symfony-filetype].

#### Features:

* Select new file for upload
* Replace/Delete uploaded file
* Name uploaded/selected for upload file
* Uploaded image thumbnail preview / icon for other file types
* Selected image thumbnail preview / icon for other file types
* Uploaded file size
* Selected file size (browsers with HTML5 File API support only)

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
        user_avatar:
            uri_prefix:           /user/avatar
            upload_destination:   %kernel.root_dir%/../web/user/avatar
            namer:                vich_uploader.namer_uniqid
            inject_on_load:       true
            delete_on_remove:     true
            delete_on_update:     true
```

Add Vich mappings to your entity:

```php
<?php
// Acme\DemoBundle\Entity\User.php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
class User
{
    /**
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatar_path")
     */
    protected $avatar;
    
    /**
     * (Optional) holds file name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatar_name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatar_path;
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
// Acme\DemoBundle\Entity\User.php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class User
{
    protected $avatar;

    public function getAvatar()
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
    public function getAvatarWebPath()
    {
        return $this->getWebPath();
    }
}
```

> **Note:** The name the second method must be `get FIELD WebPath`, in this example 
our field name is `avatar` so method name is `getAvatarWebPath`.

### 3. Configuration

Admingenerator basic configuration:

```yaml
cover:
    label:            Avatar
    formType:         afe_single_upload
    addFormOptions:
        nameable:       avatar_name
        deleteable:     avatar_path
        data_class:     Symfony\Component\HttpFoundation\File\File
```

### 3. Options

#### nameable

**type:** `string|boolean` **default:** `false`

If specified, uses this property to store file name.

#### deleteable

**type:** `string|boolean` **default:** `false`

If specified, will set this property to `null` on delete action. 

> **Note:** The file will not be physically removed, unless you customize the 
setter. Hovever if you're useing VichUploaderBundle with `vich_uploader.namer_uniqid` 
namer then the file will be overwriten upon uploading another one. 

#### maxWidth

**type:** `integer` **default:** `320`

Maximum preview image width. If image is bigger it will be scaled down to fit.

#### maxHeight

**type:** `integer` **default:** `180`

Maximum preview image height. If image is bigger it will be scaled down to fit.

#### minWidth

**type:** `integer` **default:** `16`

Minimum preview image width. If image is smaller it will be scaled up to fit.

#### minHeight

**type:** `integer` **default:** `16

Minimum preview image height. If image is smaller it will be scaled up to fit.

#### previewImages

**type:** `boolean` **default:** `true`

If false, preview for images will not be displayed.

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

### 4. Special thanks

Special thanks to [FileSquare](http://www.filesq.com) for `Filetypeicons.com` 
licensed under [Creative Commons Attribution-ShareAlike 3.0 Hong Kong License]
(http://creativecommons.org/licenses/by-sa/3.0/hk/). 
This bundle uses icons created by [loostro](https://github.com/loostro) and 
based upon `Filetypeicons.com`. They are released under the same license as the 
original work (you **must** credit FileSquare, you **don't have** to credit 
[loostro](https://github.com/loostro)).
