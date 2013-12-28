# Bootstrap Collection family
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[symfony-collectiontype]: http://symfony.com/doc/current/reference/forms/types/collection.html

### Description

Bootstral collection provides two form types: 

* `afe_collection_table`
* `afe_collection_fieldset`

Both equal in configuration and purpose, only with diffrent GUI.

### Options

##### sortable

**type**: `bool`, **default**: `false`

If true, enables UI sortable feature, allowing user to reorder items with drag&drop.
The position will be stored in a hidden input for field defined in `sortable_field`.
The position is an incremental integer key, starting with `1` for the first item.

> **Note:** it is the developers job to that field for sortable behaviour. Though we
can recommend **Gedmo\Sortable** behaviour for Doctrine ORM. For more details see
[StofDoctrineExtensionsBundle](https://github.com/stof/StofDoctrineExtensionsBundle).

##### sortable_field

**type**: `string`, **default**: `position`

If sortable is enabled, this option is used to specify the field, which should
hold sortable position.

##### new_label

**type**: `string`, **default**: `afe_collection.new_label`

New item label.