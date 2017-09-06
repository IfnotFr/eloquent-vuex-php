<?php

namespace Ifnot\LaravelVuex\Model;

trait IsStore
{
    public function getStore()
    {
        return isset($this->store) ? new $this->store($this) : new Store($this);
    }
}
