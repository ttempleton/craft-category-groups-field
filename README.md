# Category Groups Field

#### A category groups field type for Craft CMS.

<img src="src/icon.svg" width="180">

## Usage

### Single Selection setting

The Single Selection field setting controls the behaviour of a category groups field.  When this setting is disabled, the field will resemble a typical multi-select field on an element edit page, and accessing the field in your templates will give you an array of the selected category groups.  When Single Selection is enabled, the field will resemble a dropdown field on an element edit page, and accessing the field in your templates will give you the category group model.

Field data is *not* altered immediately on changing this setting, in case the setting was changed by accident; the data will only be overwritten when actually resaving its element.  However, when Single Selection is enabled, the element edit page and templates will treat the first (in alphabetical order) selected category group as the field's only category group.

New category groups fields will default to multiple group selection.  If you'd prefer to use Single Selection as default, copy the following into `config/category-groups-field.php`:

```php
<?php

return [
    'singleSelectionDefault' => true,
];
```

#### Template example: Single Selection disabled

```twig
{% if entry.categoryGroupsField %}
    <p>Multi-selection category groups field:</p>
        {% for group in entry.categoryGroupsField %}
            <p>{{ group.name }}</p>
        {% endfor %}
    </p>
{% else %}
    <p>No category groups selected :(</p>
{% endif %}
```

#### Template example: Single Selection enabled

```twig
{% if entry.categoryGroupField %}
    <p>Single selection category group field: {{ entry.categoryGroupField.name }}</p>
{% else %}
    <p>No category group selected :(</p>
{% endif %}
```

### Allowed Groups setting

Choose which category groups your field can select from, or let it select from all groups.

## Requirements

Category Groups Field requires Craft CMS 3.1.0 or later.

## Installation

Category Groups Field can be installed from the [Craft Plugin Store](https://plugins.craftcms.com/) or with [Composer](https://packagist.org/).

### Craft Plugin Store
Open your project's control panel, navigate to the Plugin Store, search for Category Groups Field and click Install.

### Composer
Open your terminal, navigate to your project's root directory and run the following command:
```
composer require ttempleton/craft-category-groups-field
```
Then open your project's control panel, navigate to Settings &rarr; Plugins, find Category Groups Field and click Install.

## Support

If you find a problem with Category Groups Field, please let me know by [opening an issue on GitHub](https://github.com/ttempleton/craft-category-groups-field/issues/new).
