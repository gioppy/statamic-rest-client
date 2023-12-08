This package provide a simple client for Statamic REST API.

It was created primarily for use on SSG php systems, such as Jigsaw, but can be used in any context.

## Installation
> composer require gioppy/statamic-rest-client

The package needs at least php 8.1, and depend on `guzzlehttp/guzzle` and `illuminate/collections`.

## How to use
After installed, you can create new client with a classic `new` operator
> new StatamicRestClient(...)

or with a static method
> StatamicRestClient::make(...)

You can pass two values: the host of your Statamic installation (e.g: `https://www.my-statmic.com`) and optionally the API endpoint path (default is `api` but you can customize on [Statamic API config](https://statamic.dev/rest-api#customizing-the-api-url)).

### Filters
You can filter the api using `->where()` method in two possible ways. You can filter single value
```php
StatamicRestClient::make(...)
  ->where('featured', true)
```
or using a condition
```php
StatamicRestClient::make(...)
  ->where('title', 'awesome', 'contains')
```
### Sorting
You can sort the response using `->sort()` method, passing an array of fields:
```php
StatamicRestClient::make(...)
  ->sort(['one', '-two', 'three'])
```
### Selecting fields
You can specify what fields should be included on response using `->fields()` method, passing an array of fields:
```php
StatamicRestClient::make(...)
  ->fields(['id', 'title', 'content'])
```
### Pagination
You can paginate the response using `->paginate()` method, passing the number of items you want and, optionally, the number of page:
```php
StatamicRestClient::make(...)
  ->paginate(5, 2)
```
### Entries / Entry
Get all entries of collection:
```php
StatamicRestClient::make(...)
  ->entries('collection')
```
Get an entry from a collection:
```php
StatamicRestClient::make(...)
  ->entry('collection', 'id')
```
### Collection Tree
TODO
### Navigation Tree
```php
StatamicRestClient::make(...)
  ->navigation('navigation_name')
```
### Taxonomy Terms / Taxonomy Term
Get all terms of taxonomy:
```php
StatamicRestClient::make(...)
  ->terms('taxonomy_name')
```
Get a single term from taxonomy:
```php
StatamicRestClient::make(...)
  ->term('taxonomy_name', 'taxonomy_slug')
```
### Globals / Global
Get all globals:
```php
StatamicRestClient::make(...)
  ->globals()
```
Get single global:
```php
StatamicRestClient::make(...)
  ->global('handle')
```
### Assets / Asset
Get all assets of one container:
```php
StatamicRestClient::make(...)
  ->assets('container')
```
Get single asset:
```php
StatamicRestClient::make(...)
  ->asset('container', 'path')
```
Get single asset by its id:
```php
StatamicRestClient::make(...)
  ->assetById('id')
```
The `id` of an asset is formatted by Statamic as `container::path`.
### Getting response
You can get all response as array, including links and other nodes with `->all()` method
```php
StatamicRestClient::make(...)
  ->entries('collection')
  ->all()
```
or you can get only data node with `->data()` method
```php
StatamicRestClient::make(...)
  ->entries('collection')
  ->data()
```
If you need to have `data` as collection you can us `->toCollection()` method
```php
StatamicRestClient::make(...)
  ->entries('collection')
  ->toCollection()
```
### Integration with Statamic Glide Rest
[Statami Glide Rest](https://github.com/gioppy/statamic-glide-rest) is a small addon for Statamic that expose glide manipulation presets on a REST API endpoint. You can get an asset response with a glide presets with the class `StatamicGlideRest`:
```php
StatamicGlideRest::make($host)
  ->presets(['small', 'medium'])
  ->glide('container', 'path')
  ->data()
```

## TODO
- [x] Entries
- [x] Entry
- [ ] Collection Tree
- [x] Navigation Tree
- [x] Taxonomy Terms
- [x] Taxonomy Term
- [x] Assets
- [x] Asset
- [x] Globals
- [x] Global
- [ ] Test
- [x] Integration with [Statamic Glide Rest](https://github.com/gioppy/statamic-glide-rest)
- [ ] Laravel integration