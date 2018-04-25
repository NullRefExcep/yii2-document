<?php
/**
 * @var $this yii\web\View
 * @var $model nullref\documents\models\forms\ExportForm
 * @var $form yii\widgets\ActiveForm
 */

use nullref\documents\helpers\Form;

$options = $model->getDocumentConfig()->getExporter()->getDocumentOptions();
?>

<?= $form->field($model, 'configId')->hiddenInput()->label(false) ?>

<?php if ($options): ?>
    <div class="row">
        <?php foreach ($options as $key => $config): ?>
            <div class="col-md-6">
                <?= Form::renderWorkerOptionInput($form->field($model, 'options[' . $key . ']'), $config) ?>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>