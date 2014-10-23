<?php
/**
 * Created by PhpStorm.
 * User: wvanderjagt
 * Date: 2014-10-12
 * Time: 4:23 PM
 */

namespace Searchable\Engines;

use Illuminate\Database\Eloquent\Model;

interface SearchEngineInterface
{
    public function index(Model $model);

    public function update(Model $model);

    public function delete(Model $model);

    public function search(array $models, array $query);
} 