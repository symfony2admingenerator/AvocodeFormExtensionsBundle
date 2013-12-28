# How to use upload manager with this bundle?
------------------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

### 1. Overview

Currently we support one upload manager bundle:

* [vich/uploader-bundle][https://github.com/dustin10/VichUploaderBundle]

Form types that make use of upload manager for handleing uploads:

* Collection Upload
* Single Upload

### 2. Configuration

First, install the selected bundle, by adding it to your `composer.json` dependencies:

```json
    "require": {
        "vich/uploader-bundle": "dev-master"
    },
```

Enable the selected bundle in your `AppKernel.php`:

```php
<?php
    // AppKernel.php
    public function registerBundles()
    {
        // ...
        new Vich\UploaderBundle\VichUploaderBundle(),
```

Lastly, configure AvocodeFormExtensionsBundle to use your upload manager:

```yaml
avocode_form_extensions:
    upload_manager:        vich_uploader
```

> **Note:** possible options are `vich_uploader`

### 3. Create new upload configuration

You can read how to create upload configuration in selected bundle's docs:

* [vich/uploader-bundle Configuration](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/index.md#configuration)

### 4. Use your upload in the forms

To use selected upload configuration you'll have to modify the entity that holds 
uploadable property. Read more about that in form type docs:

* [Collection Upload](https://github.com/avocode/FormExtensions/blob/master/Resources/doc/collection-upload/overview.md)
* [Single Upload](https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md)