<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model nullref\documents\models\DocumentItemSearch
 * @var $form yii\widgets\ActiveForm
 */
?>

<div class="document-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'document_id') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'product_variant_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('documents', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('documents', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
