<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model nullref\documents\models\DocumentConfig */

$this->title = Yii::t('documents', 'Update Document Config') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('documents', 'Document Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('documents', 'Update');
?>
<div class="document-config-update">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
