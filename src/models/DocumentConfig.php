<?php

namespace nullref\documents\models;

use nullref\useful\behaviors\JsonBehavior;
use nullref\useful\traits\Mappable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document_config".
 *
 * @property integer $id
 * @property string $name
 * @property array $columns
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property string|array $options
 *
 * @property Document[] $documents
 */
class DocumentConfig extends ActiveRecord
{
    use Mappable;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%document_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['columns', 'name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['columns', 'options'], 'safe'],
            [['type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('documents', 'ID'),
            'name' => Yii::t('documents', 'Name'),
            'options' => Yii::t('documents', 'Options'),
            'columns' => Yii::t('documents', 'Columns config'),
            'created_at' => Yii::t('documents', 'Created At'),
            'updated_at' => Yii::t('documents', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'columns' =>
                Yii::t('documents', 'Columns are being set up by alphabetic names("A","B", etc.) or by numbers') .
                Yii::t('documents', 'To glue several columns into one value write their coords separated by commas'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
            'json' => [
                'class' => JsonBehavior::className(),
                'fields' => ['columns', 'options'],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['config_id' => 'id']);
    }
}
