<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\models\document;


use nullref\documents\models\config\Import as ImportConfig;
use nullref\documents\models\Document;
use nullref\documents\traits\Import as ImportTrait;

class Import extends Document
{
    use ImportTrait;

    public function afterDelete()
    {
        if (file_exists($this->file_path)) {

        }
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasOne(ImportConfig::className(), ['id' => 'config_id']);
    }

}