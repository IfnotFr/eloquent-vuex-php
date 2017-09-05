<?php

namespace Ifnot\LaravelVuex;

use Illuminate\Database\Eloquent\Model;

class Helper
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $relatedModels
     * @return array
     */
    public static function getCascadeRelatedModels(Model $model, $relatedModels = [])
    {
        $relatedModels[self::getModelUniqueIndex($model)] = $model;

        foreach (self::getModelRelated($model) as $relatedModel) {
            if (! isset($relatedModels[self::getModelUniqueIndex($relatedModel)])) {
                $relatedModels = self::getCascadeRelatedModels($relatedModel, $relatedModels);
            }
        }

        return $relatedModels;
    }

    /**
     * Get the model name based on the name
     *
     * @param $model
     * @return string
     */
    public static function getModelUniqueIndex(Model $model)
    {
        return get_class($model).':'.$model->id;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    protected static function getModelRelated(Model $model)
    {
        $relatedModels = [];

        if(method_exists($model, 'getStore')) {
            foreach ($model->getStore()->getCascadeRelations() as $method) {
                foreach ($model->$method()->get() as $relatedModel) {
                    $relatedModels[] = $relatedModel;
                }
            }
        }

        return $relatedModels;
    }
}