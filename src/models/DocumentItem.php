<?php

namespace nullref\documents\models;

use app\modules\catalog\models\Stock;
use nullref\useful\behaviors\JsonBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document_item".
 *
 * @property integer $id
 * @property integer $document_id
 * @property string|array $data
 * @property integer $status
 * @property string $error
 *
 * @property Document $document
 */
class DocumentItem extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_DONE = 2;
    const STATUS_ERROR = 99;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_NEW => Yii::t('documents', 'New'),
            self::STATUS_DONE => Yii::t('documents', 'Done'),
            self::STATUS_ERROR => Yii::t('documents', 'Error'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%document_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id'], 'required'],
            [['data', 'error'], 'safe'],
            [['document_id', 'status'], 'integer'],
            ['data', 'dataValidator'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function dataValidator($attribute, $params, $validator)
    {
        return count(array_filter(array_values($this->$attribute))) > 0;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('documents', 'ID'),
            'document_id' => Yii::t('documents', 'Document ID'),
            'data' => Yii::t('documents', 'Data'),
            'status' => Yii::t('documents', 'Status'),
            'error' => Yii::t('documents', 'Error'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'json' => [
                'class' => JsonBehavior::className(),
                'fields' => ['data'],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }
}
