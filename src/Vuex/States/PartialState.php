<?php

namespace Ifnot\LaravelVuex\Vuex\States;

use Illuminate\Database\Eloquent\Model;

class PartialState extends State
{
    public function create(Model $model, array $meta = [])
    {
        // Do nothing
    }

    public function update(Model $model, array $meta = [])
    {
        $this->emit($model, 'update', $this->channel, $meta);
    }

    public function delete(Model $model, array $meta = [])
    {
        // Do nothing
    }
}