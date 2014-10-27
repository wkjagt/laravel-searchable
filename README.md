# laravel-searchable

*Make Laravel models instantly searchable, and automatically indexed using Elastic Search.*

**Work in progress**

Adding search functionality to a laravel model only requires using a trait. Using the `SearchableTrait` in your modle will enable a model observer that acts on the `created`, `updated` and `deleted` events to keep your indexes up to date.

The trait adds a `search` method to your model that makes it easy to search Elastic Search for hits.

The provided facade allows similar functionality, and the added ability to search in multiple document types (i.e. models) at once.

## Installation

### Composer

Include the package in your composer file, and run composer update.

I'm still working on this, but `dev-master` will change to a stable tag as soon as I have one ready.
```json
{
  "require" : {
     ...
    "wkjagt/searchable": "dev-master"
  }
}
```

### Service provider

Add the ServiceProvider to your `app.php`:

```php
'providers' => [
    ...
    'Searchable\SearchableServiceProvider'
]
```

### Searchable Facade

If you want to use the Searchable facade, add it to your aliases in `app.php`:

```php
'aliases' => [
    ...
    'Searchable'      => 'Searchable\SearchableFacade'
]
```

### Searchable Trait

Add the `SearchableTrait` to the models you want indexed. For example:

```php
class Thing extends Model {

    use SearchableTrait;
    
    ...
}
```

That's all you need to get started. Newly created `Thing` models will be automatically indexed. By default, all models will be added to a `default` index, and the fully qualified class name will be used as the document type (where backslashes are replaced by dots). This is fine for normal usage, but can be modified by adding static properties to your model:

```php
class Thing extends Model {

    use SearchableTrait;
    
    public static $searchDocumentType = 'thing';
    
    public static searchIndexName = 'my_index';
}
```

### Searching

Searching can be done in two ways. `SearchableTrait` adds a `search` method to your model, which accepts one argument: an Elastic Search query. For example:

```php
$query = [ 'filtered' => [
    'query' => [ 'match' => [ 'name' => 'some_name' ]],
    'filter' => [ 'term' => [ 'user_id' => 14 ]],
]];

$hits = Thing::search($query);
```

You can also search multiple models at once using the Searchable facade, which uses the native Elastic Search way of searching in multiple indexes/document types. The search method on `Searchable` takes an array of fully qualified class names of models, and needs to be chained to the `withSearch` method. An example:

```php
$query = [ 'filtered' => [
    'query' => [ 'match' => [ 'name' => 'some_name' ]],
    'filter' => [ 'term' => [ 'user_id' => 14 ]],
]];

$hits = Searchable::search(['Thing', 'OtherThing'])->withQuery($query);
```

The results are returned as an array of `Searchable\SearchResult` instances, which are simple value objects, with two public properties:

- `hit`: a hydrated Laravel Model
- `score` : the search score

### Indexing existing models

When adding Searchable to an existing project, you probably want to index your existing models. The added `searchable:indexall` artisan command adds this functionality to each model that uses `SearchableTrait`. To index all resources for a given model, run the command, passing the fully qualified class name as argument, replacing any backslashes with forward slashes:

```
./artisan searchable:indexall Namespace/Of/Your/Model
```
