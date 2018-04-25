<?php

use app\modules\catalog\models\Stock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model nullref\documents\models\forms\ImportForm
 * @var $form yii\widgets\ActiveForm
 */


?>

<div class="document-form">

    <?php $form = ActiveForm::begin([
        'method' => $model->configId ? 'post' : 'get',
    ]); ?>
    <div class="row">
        <div class="col-md-12">
            <?php if ($model->configId): ?>
                <?= $this->render('_options', ['form' => $form, 'model' => $model]) ?>
                <?= $form->field($model, 'file')->fileInput() ?>
            <?php else: ?>
                <?= $this->render('_config', ['form' => $form, 'model' => $model]) ?>
            <?php endif ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('documents', 'Create'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
