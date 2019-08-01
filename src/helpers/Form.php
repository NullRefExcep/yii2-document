<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\helpers;


use nullref\documents\components\Worker;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

class Form
{
    /**
     * @param $field ActiveField
     * @param $config
     * @return string
     * @throws InvalidConfigException
     */
    public static function renderWorkerOptionInput($field, $config)
    {
        $type = ArrayHelper::remove($config, 'type');
        $label = ArrayHelper::remove($config, 'label');
        switch ($type) {
            case Worker::OPTION_TYPE_TEXT:
                $field->textInput($config);
                break;
            case Worker::OPTION_TYPE_WIDGET:
                $field->widget($config['widgetClass'], $config['widgetConfig']);
                break;
            case Worker::OPTION_TYPE_CHECKBOX:
                $field->checkbox(ArrayHelper::merge($config, [
                    'label' => false,
                ]));
                break;
            case Worker::OPTION_TYPE_DROPDOWN:
                $field->dropDownList(ArrayHelper::remove($config, 'items', []), $config);
                break;
            case Worker::OPTION_TYPE_FILE:
                $field->fileInput($config);
                break;
            default:
                throw new InvalidConfigException('Importer option should have type');
        }

        if ($label) {
            $field->label($label);
        }

        return $field;
    }

    /**
     * @param $options
     * @return array
     */
    public static function getWorkerOptions($options)
    {
        $result = [];
        foreach ($options as $key => $option) {
            if (is_string($option)) {
                $result[$key] = [
                    'type' => 'text',
                    'label' => $option,
                ];
            } else {
                $result[$key] = $option;
            }
        }

        return $result;
    }
}
