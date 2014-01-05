# Upgrade notes
----------------------------------------------------

This file lists B/C breaking PRs in reverse chronological order. Each PR contains 
description explaining nature of changes and upgrade notes to help you upgrade your 
project.

## Commit [#be706a6][cobe706a6] Remove annotations autoloading

[cobe706a6]: https://github.com/avocode/FormExtensions/commit/be706a6

#### Description:

This changed data type returned by `afe_daterange_picker`. Before this commit, it returned a string if you don't use
the model DateRange. Now, if you still don't use the DateRange model, it returns an associative array:

```php
    array('from' => from_value_string, 'to' => to_value_string)
```

#### BC Break:

If you previously use `afe_daterange_picker` and set a default value through a string, you have to change
it by an array.
