<?php

namespace Ifnot\LaravelVuex\Model;

trait IsStore
{
    public function getStore()
    {
        if(isset($this->store)) {
            return new $this->store($this);
        } else {
            return new Store($this);
        }
    }
}