# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Added
- Added `ttempleton\categorygroupsfield\web\assets\sortable\SortableAsset` (includes the jQuery UI Sortable widget)

### Changed
- Category Groups Field now requires Craft 4.5.0 or later
- Multi-select category groups fields now use the Selectize library with the Drag & Drop plugin

## 2.0.1 - 2022-11-05

### Fixed
- Fixed an error that occurred when creating new category groups fields

## 2.0.0 - 2022-05-05

### Added
- Added Craft 4 compatibility

### Removed
- Removed Craft 3 compatibility

## 1.3.0 - 2021-12-23

### Added
- Added the `categories()` method for `CategoryGroupCollection` which returns a `CategoryQuery`

## 1.2.2 - 2020-02-23

### Changed
- When displaying category groups field data in element index tables, category groups now link to their category management pages

## 1.2.1 - 2019-12-20

### Added
- Added the `inReverse()` method for `CategoryGroupCollection`

## 1.2.0 - 2019-11-20

### Added
- Accessing a non-empty multi-selection field in a template now returns the new `CategoryGroupCollection`, which is designed to mimic a Craft element query (though doesn't yet support the more advanced filtering options that element queries have) (for now, iterating directly over a `CategoryGroupCollection` in templates is still supported, so no template changes are needed at this stage, but it will be deprecated in the future)
- Added `all()`, `one()`, `nth()`, `count()` and `ids()` methods for `CategoryGroupCollection`

## 1.1.0 - 2019-09-29

### Added
- Added the `singleSelectionDefault` config setting

## 1.0.2 - 2019-09-17

### Added
- Added Allowed Groups field setting

### Changed
- Changed label of Single Selection Mode setting to Single Selection

## 1.0.1 - 2019-09-13

### Added
- Added ability to show category groups field data in element index tables

## 1.0.0 - 2019-09-11
- Initial release
