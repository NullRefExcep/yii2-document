<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $model \nullref\documents\models\forms\CreateProductWhileBindingForm
 */
?>

<?php $form = ActiveForm::begin([
    'id' => 'createProductForm',
    'action' => Url::to(['/documents/admin/document-item/create-product',
        'documentItemIds' => implode(',', $model->documentItemIds)])
]) ?>
<div class="modal-body">
    <?= $form->field($model, 'sku')->textInput([
        'id' => 'createProductSkuInput'
    ]) ?>
    <?= $form->field($model, 'name')->textInput([
        'id' => 'createProductNameInput'
    ]) ?>
    <?= $form->field($model, 'color')->textInput([
        'id' => 'createProductColorInput'
    ]) ?>
</div>
<div class="modal-footer">
    <?= Html::submitButton(Yii::t('documents', 'Create Product'), [
        'id' => 'removeBtn',
        'class' => 'btn btn-primary',
    ]) ?>
    <?= Html::button(Yii::t('documents', 'Cancel'), [
        'class' => 'btn btn-default',
        'data-dismiss' => 'modal',
    ]) ?>
</div>
<?php ActiveForm::end() ?>
