<?php

use nullref\documents\helpers\Form;
use nullref\documents\helpers\Helper;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model nullref\documents\models\config\Import */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'options[importer]')->dropDownList(Helper::getImporterMap()) ?>
    <?php else: ?>

        <div class="row">
            <div class="col-md-4">
                <?= Html::label(Yii::t('documents', 'Options')) ?>
                <?= $form->field($model, 'options[importer]')->hiddenInput()->label(false) ?>
                <?php foreach (Form::getWorkerOptions($model->getImporter()->getOptions()) as $key => $config): ?>
                    <?= Form::renderWorkerOptionInput($form->field($model, 'options[' . $key . ']'), $config) ?>
                <?php endforeach ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'columns')->widget(MultipleInput::className(), [
                    'data' => $model->getColumnsFormData(),
                    'removeButtonOptions' => [
                        'class' => 'hidden',
                    ],
                    'addButtonOptions' => [
                        'class' => 'hidden',
                    ],
                    'columns' => [
                        [
                            'name' => 'label',
                            'title' => Yii::t('documents', 'Fields'),
                            'options' => [
                                'readonly' => true,
                            ],
                            'type' => MultipleInputColumn::TYPE_TEXT_INPUT,
                        ],
                        [
                            'name' => 'target',
                            'title' => Yii::t('documents', 'Target field'),
                            'options' => [
                                'readonly' => true,
                            ],
                            'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                        ],
                        [
                            'name' => 'source',
                            'title' => Yii::t('documents', 'Columns'),
                            'type' => MultipleInputColumn::TYPE_TEXT_INPUT,
                            'defaultValue' => '',
                        ],
                        [
                            'name' => 'filter',
                            'title' => Yii::t('documents', 'Filter'),
                            'type' => MultipleInputColumn::TYPE_CHECKBOX,
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    <?php endif ?>

    <div class="form-group">
        <p>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('documents', 'Create') : Yii::t('documents', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </p>
    </div>

    <?php ActiveForm::end(); ?>

</div>
