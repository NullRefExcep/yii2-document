<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\helpers;


use nullref\documents\models\Document;

class Grid
{
    /**
     * @param $document Document
     * @return array
     */
    public static function getItemColumns($document)
    {
        return array_map(function ($column) {
            $target = $column['target'];
            return [
                'label' => $column['label'],
                'value' => function ($model) use ($target) {
                    return isset($model->data[$target]) ? $model->data[$target] : '';
                }
            ];
        }, $document->config->columns);
    }
}