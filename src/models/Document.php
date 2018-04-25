<?php

namespace nullref\documents\models;

use nullref\useful\behaviors\JsonBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property integer $status
 * @property string $file_path
 * @property string $job_id
 * @property integer $config_id
 * @property integer $type
 * @property string|array $options
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property DocumentConfig $config
 * @property DocumentItem[] $documentItems
 */
class Document extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_LOADING = 1;
    const STATUS_DONE = 2;
    const STATUS_ERROR = 99;

    /**
     * Types of document
     */
    const TYPE_IMPORT = 1;
    const TYPE_EXPORT = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%document}}';
    }

    /**
     * @inheritdoc
     * @return DocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DocumentQuery(get_called_class());
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
                'fields' => ['options'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['options', 'job_id'], 'safe'],
            [['config_id'], 'required'],
            [['status', 'config_id', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['file_path'], 'string', 'max' => 255],
            [['config_id'], 'exist', 'skipOnError' => true,
                'targetClass' => DocumentConfig::className(),
                'targetAttribute' => ['config_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('documents', 'ID'),
            'status' => Yii::t('documents', 'Status'),
            'file_path' => Yii::t('documents', 'File'),
            'config_id' => Yii::t('documents', 'Config ID'),
            'created_at' => Yii::t('documents', 'Created At'),
            'updated_at' => Yii::t('documents', 'Updated At'),
            'options' => Yii::t('documents', 'Options'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasOne(DocumentConfig::className(), ['id' => 'config_id']);
    }

    /**
     * Returns status in a human readable format
     * @return mixed
     */
    public function getStatusReadable()
    {
        return Document::getStatuses()[$this->status];
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_NEW => Yii::t('documents.status', 'New'),
            self::STATUS_LOADING => Yii::t('documents.status', 'Loading'),
            self::STATUS_DONE => Yii::t('documents.status', 'Done'),
            self::STATUS_ERROR => Yii::t('documents.status', 'Error'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentItems()
    {
        return $this->hasMany(DocumentItem::class, ['document_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getShortPath();
    }

    /**
     * Returns short presentation of file_path
     * @return string
     */
    public function getShortPath()
    {
        return basename($this->file_path);
    }

    /**
     * @return bool
     */
    public function canBeDownloaded()
    {
        return file_exists($this->file_path);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getOptionValue($key)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        if (array_key_exists($key, $this->config->options)) {
            return $this->config->options[$key];
        }
        return null;
    }
}
