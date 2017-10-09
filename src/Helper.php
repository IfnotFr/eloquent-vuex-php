<?php

namespace Ifnot\LaravelVuex;

use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

class Helper
{
    /**
     * Return an unique identified for this model.
     */
    public static function getModelUniqueIndex(Model $model): string
    {
        return get_class($model).':'.$model->id;
    }

    /**
     * Get all related models recursively for the given model.
     */
    public static function getCascadeRelatedModels(Model $model, array $relatedModels = [], $level = 0): array
    {
        $relatedModels[self::getModelUniqueIndex($model)] = $model;

        foreach (self::getRelatedModels($model) as $relatedModel) {
            if (! isset($relatedModels[self::getModelUniqueIndex($relatedModel)])) {
                $relatedModels = self::getCascadeRelatedModels($relatedModel, $relatedModels, $level + 1);
            }
        }

        if ($level === 0) {
            unset($relatedModels[self::getModelUniqueIndex($model)]);
        }

        return $relatedModels;
    }

    /**
     * Get the related models of a given model.
     */
    protected static function getRelatedModels(Model $model): array
    {
        $relatedModels = [];

        $store = $model->store ? new $model->store($model) : new Store($model);

        foreach ($store->getCascadeRelations() as $method) {
            foreach ($model->$method()->get() as $relatedModel) {
                $relatedModels[] = $relatedModel;
            }
        }

        return $relatedModels;
    }
}