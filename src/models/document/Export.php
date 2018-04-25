<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\models\document;


use nullref\documents\models\config\Export as ExportConfig;
use nullref\documents\models\Document;
use nullref\documents\traits\Export as ExportTrait;

class Export extends Document
{
    use ExportTrait;

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
        return $this->hasOne(ExportConfig::className(), ['id' => 'config_id']);
    }
}