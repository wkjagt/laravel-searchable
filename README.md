laravel-searchable
==================

Make Laravel models instantly indexed and searchable in Elastic Search.

Just use the SearchableTrait in your model. This will enable a model observer that acts on the `created`, `updated` and `deleted` events to keep your indexes up to date.

The trait adds a `search` method to your model that makes it easy to search Elastic Search for hits.

The provided facade allows searching in multiple document types (which relates to multiple models) at once.
