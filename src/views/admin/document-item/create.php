<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model nullref\documents\models\DocumentItem */

$this->title = Yii::t('documents', 'Create Document Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('documents', 'Document Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-item-create">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('documents', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
