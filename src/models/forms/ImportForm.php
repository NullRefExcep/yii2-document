<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\models\forms;


use nullref\documents\helpers\Helper;
use nullref\documents\jobs\Import as ImportJob;
use nullref\documents\models\config\Import as ImportConfig;
use nullref\documents\models\document\Import;
use Yii;
use yii\base\Model;
use yii\validators\Validator;
use yii\web\UploadedFile;

class ImportForm extends Model
{
    public $configId;
    public $options;

    /** @var ImportConfig|null */
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
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'createIfNotExist' => Yii::t('documents', 'Create Product If Not Exist'),
            'configId' => Yii::t('documents', 'Config'),
            'file' => Yii::t('documents', 'File'),
            'type' => Yii::t('documents', 'Type'),
        ];
    }

    /**
     * @return bool
     */
    public function createRecord()
    {
        $result = $this->validate();

        if ($result) {

            $model = Import::create();
            $model->type = $this->getDocumentConfig()->type;
            $model->config_id = $this->getDocumentConfig()->id;
            $options = $this->options;
            $optionsConfig = $this->getDocumentConfig()->getImporter()->getDocumentOptions();
            foreach ($optionsConfig as $name => $option) {
                if ($option['type'] == 'file') {
                    $file = UploadedFile::getInstance($this, 'options[' . $name . ']');
                    if ($file) {
                        $path = Yii::getAlias('@webroot/uploads/') . time() . '_' . $file->baseName . '.' . $file->extension;
                        $result &= $file->saveAs($path);
                        if ($result) {
                            $options[$name] = $path;
                        }
                    }
                }
            }
            $model->options = $options;

            if ($result) {
                $result &= $model->save();
                self::runJob($model);
            }
        }

        return $result;
    }

    /**
     * @return ImportConfig|null
     */
    public function getDocumentConfig()
    {
        if ($this->_config === null) {
            $this->_config = ImportConfig::findOne($this->configId);
        }
        return $this->_config;
    }

    /**
     * @param $model
     */
    public static function runJob($model)
    {
        $job = new ImportJob();
        $job->id = $model->id;
        Helper::getModule()->addJob($job);
    }

    /**
     * Add validators for files if need
     *
     * @return bool
     */
    public function beforeValidate()
    {

        $config = $this->getDocumentConfig();

        if ($config) {

            //@todo
            $extensions = [];// $config->getExtensions();

            if ($extensions) {
                $this->getValidators()->append(Validator::createValidator('file', $this, 'file', [
                    'checkExtensionByMimeType' => false, 'extensions' => $extensions, 'skipOnEmpty' => false
                ]));
            }

        }
        return parent::beforeValidate();
    }
}