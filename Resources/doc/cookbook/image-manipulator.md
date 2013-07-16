# How to use image manipulator with this bundle?
------------------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

### 1. Overview

Currently we support two image manipulator bundles:

* [avalanche123/imagine-bundle][https://github.com/avalanche123/AvalancheImagineBundle]
* [liip/imagine-bundle][https://github.com/liip/LiipImagineBundle]

Form types that make use of image manipulation for generating a thumbnail:

* Collection Upload
* Single Upload

### 2. Configuration

First, install the selected bundle, by adding it to your `composer.json` dependencies:

```json
    "require": {
        "liip/imagine-bundle": "dev-master"
    },
```

Enable the selected bundle in your `AppKernel.php`:

```php
<?php
    // AppKernel.php
    public function registerBundles()
    {
        // ...
        new Liip\ImagineBundle\LiipImagineBundle(),
```

Lastly, configure AvocodeFormExtensionsBundle to use your image manipulator:

```yaml
avocode_form_extensions:
    image_manipulator:        liip_imagine
```

> **Note:** possible options are `avalanche_imagine` and `liip_imagine`.

### 3. Create new filters configuration

You can read how to create filters configuration in selected bundle's docs:

* [avalanche/imagine-bundle Basic Usage](https://github.com/avalanche123/AvalancheImagineBundle#basic-usage)
* [liip/imagine-bundle Basic Usage](https://github.com/liip/LiipImagineBundle#basic-usage)

### 4. Use your filters in the forms

To enable generateing thumbnails in form types simply add `previewFilter` option 
to your form with the name of the filter you want to apply. Read more about avaliable 
options here:

* [Collection Upload](https://github.com/avocode/FormExtensions/blob/master/Resources/doc/collection-upload/overview.md)
* [Single Upload](https://github.com/avocode/FormExtensions/blob/master/Resources/doc/single-upload/overview.md)