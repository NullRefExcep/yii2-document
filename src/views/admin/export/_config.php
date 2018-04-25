<?php
/**
 * @var $this yii\web\View
 * @var $model nullref\documents\models\forms\ExportForm
 * @var $form yii\widgets\ActiveForm
 */

use nullref\documents\models\config\Export;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to([
    '/documents/admin/import-config/update',
    'id' => 'ID_PLACEHOLDER',
    'redirect' => '/documents/admin/import/create'
]);

$this->registerJs(<<<JS
var configDropDown = jQuery('#configDropDown');
var editConfigBtn = jQuery('#editConfigBtn');
updateEditBtn();
configDropDown.on('change', function () {
    updateEditBtn();
});

function updateEditBtn() {
    var configId = configDropDown.val();
    if (configId > 0) {
        editConfigBtn.attr('href', "$url".replace('ID_PLACEHOLDER', configId));
        editConfigBtn.show();
    } else {
        editConfigBtn.hide();
    }
}

JS
);
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('documents', 'Configuration') ?>
    </div>
    <div class="panel-body">
        <?php $configs = Export::getMap() ?>
        <?php if (!empty($configs)): ?>
            <?= $form->field($model, 'configId')->dropDownList($configs, ['id' => 'configDropDown']) ?>
        <?php endif ?>

        <?= Html::a(Yii::t('documents', 'New Configuration'), [
            '/documents/admin/export-config/create',
            'redirect' => '/documents/admin/export/create'
        ], ['class' => 'btn btn-success']) ?>

        <?= Html::a(Yii::t('documents', 'Edit'), '#', [
            'id' => 'editConfigBtn',
            'class' => 'btn btn-primary ',
        ]) ?>

    </div>
</div>

