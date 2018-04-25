<?php

use app\modules\catalog\models\Vendor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model nullref\documents\models\forms\ExportForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="export-form">

    <?php $form = ActiveForm::begin([
        'method' => $model->configId ? 'post' : 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <?php if ($model->configId): ?>
                <?= $this->render('_options', ['form' => $form, 'model' => $model]) ?>
            <?php else: ?>
                <?= $this->render('_config', ['form' => $form, 'model' => $model]) ?>
            <?php endif ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('documents', 'Export'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
