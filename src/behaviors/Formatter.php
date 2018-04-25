<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\behaviors;


use nullref\documents\models\DocumentItem;
use Yii;
use yii\base\Behavior;

/**
 * Class Formatter
 * @package nullref\documents\behaviors
 */
class Formatter extends Behavior
{
    /**
     * @param $status
     * @return string
     */
    public function asDocumentItemStatus($status)
    {
        $statuses = DocumentItem::getStatuses();
        if (isset($statuses[$status])) {
            return $statuses[$status];
        }
        return Yii::t('documents', 'N\A');
    }

}