# Category Groups Field

#### A category groups field type for Craft CMS.

<img src="src/icon.svg" width="180">

## Usage

### Single Selection setting

The Single Selection field setting controls the behaviour of a category groups field.  When this setting is disabled, the field will resemble a typical multi-select field on an element edit page, and accessing the field in your templates will give you the category group collection.  When Single Selection is enabled, the field will resemble a dropdown field on an element edit page, and accessing the field in your templates will give you the category group model.

Field data is *not* altered immediately on changing this setting, in case the setting was changed by accident; the data will only be overwritten when actually resaving its element.  However, when Single Selection is enabled, the element edit page and templates will treat the first (in alphabetical order) selected category group as the field's only category group.

New category groups fields will default to multiple group selection.  If you'd prefer to use Single Selection as default, copy the following into `config/category-groups-field.php`:

```php
<?php

return [
    'singleSelectionDefault' => true,
];
```

#### Template example: Single Selection disabled

This example uses a category group collection's `all()` method to loop through the collection's groups.

```twig
{% if entry.categoryGroupsField %}
    <p>Multi-selection category groups field:</p>
        {% for group in entry.categoryGroupsField.all() %}
            <p>{{ group.name }}</p>
        {% endfor %}
    </p>
{% else %}
    <p>No category groups selected :(</p>
{% endif %}
```

A multi-select category groups field's data can be accessed in templates in a way that mimics the execution methods of a typical Craft element query, including the methods `all()`, `one()`, `nth()`, `count()` and `ids()`.

It can also be used to get the categories belonging to the selected category groups, using the `categories()` method to return a Craft category query:

```twig
{% if entry.categoryGroupsField %}
    {% for category in entry.categoryGroupsField.categories().all() %}
        <p>{{ category.title }}</p>
    {% endfor %}
{% endif %}
```

After using `categories()`, if you want to set any other category query parameters, be careful not to set the `groupId`, since it will override the IDs of the groups selected in the field. If you need to set additional category group IDs, you can pass a hash to `categories()` containing category query parameters, and any group IDs included will be merged with those selected in the field:

```twig
{% if entry.categoryGroupsField %}
    {# Gets the categories from category groups with IDs 1, 2 and 3, as well as the category groups selected in the field #}
    {% for category in entry.categoryGroupsField.categories({groupId: [1, 2, 3]}).all() %}
        <p>{{ category.title }}</p>
    {% endfor %}
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

Category Groups Field requires Craft CMS 4.0.0-beta.1 or later.

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
