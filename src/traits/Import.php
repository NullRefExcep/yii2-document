<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\traits;


use nullref\documents\models\Document;
use yii\db\ActiveQueryInterface;

/**
 * Trait Import
 * @package nullref\documents\traits
 *
 * @property $type
 */
trait Import
{
    /**
     * @return ActiveQueryInterface
     */
    public static function find()
    {
        return parent::find()->andWhere(['type' => Document::TYPE_IMPORT]);
    }

    /**
     * @return Document|\nullref\documents\models\document\Import|Import
     */
    public static function create()
    {
        $object = new self();
        $object->type = Document::TYPE_IMPORT;
        return $object;
    }
}