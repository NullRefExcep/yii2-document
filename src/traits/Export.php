<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\traits;


use nullref\documents\models\Document;
use yii\db\ActiveQueryInterface;

/**
 * Trait Export
 * @package nullref\documents\traits
 *
 * @property $type
 */
trait Export
{
    /**
     * @return ActiveQueryInterface
     */
    public static function find()
    {
        return parent::find()->andWhere(['type' => Document::TYPE_EXPORT]);
    }

    /**
     * @return Document
     */
    public static function create()
    {
        $object = new self();
        $object->type = Document::TYPE_EXPORT;
        return $object;
    }
}