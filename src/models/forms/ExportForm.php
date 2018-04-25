<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\models\forms;


use nullref\documents\helpers\Helper;
use nullref\documents\jobs\Export as ExportJob;
use nullref\documents\models\config\Export as ExportConfig;
use nullref\documents\models\document\Export;
use Yii;
use yii\base\Model;

class ExportForm extends Model
{
    public $file;
    public $configId;
    public $options;

    /** @var ExportConfig|null */
    protected $_config;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['configId', 'required'],
            [['options'], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function createRecord()
    {
        $model = Export::create();
        $model->type = $this->getDocumentConfig()->type;
        $model->config_id = $this->getDocumentConfig()->id;
        $model->options = $this->options;

        if ($model->save()) {
            self::runJob($model);
        } else {
            return false;
        }

        return true;
    }

    /**
     * @return ExportConfig|null
     */
    public function getDocumentConfig()
    {
        if ($this->_config === null) {
            $this->_config = ExportConfig::findOne($this->configId);
        }
        return $this->_config;
    }

    /**
     * @param $model
     */
    public static function runJob($model)
    {
        $job = new ExportJob();
        $job->id = $model->id;
        Helper::getModule()->addJob($job);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'configId' => Yii::t('documents', 'Config'),
            'file' => Yii::t('documents', 'File'),
            'options' => Yii::t('documents', 'Options'),
        ];
    }

}