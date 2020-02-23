# Changelog

## Unreleased
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
