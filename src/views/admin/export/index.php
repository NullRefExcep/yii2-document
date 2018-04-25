<?php

use nullref\documents\models\Document;
use nullref\documents\models\Export;
use rmrevin\yii\fontawesome\FA;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('documents', 'Exports');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="export-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('documents', 'Add Export'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'status',
                'value' => function (Document $model) {
                    return $model->getStatusReadable();
                },
            ],
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{rerun} {download} {delete}',
                'buttons' => [
                    'rerun' => function ($url, Document $model, $key) {
                        return Html::a(FA::icon(FA::_REFRESH), ['rerun', 'id' => $model->id]);
                    },
                    'download' => function ($url, Document $model, $key) {
                        return $model->canBeDownloaded() ? Html::a(FA::icon('download'), $url) : '';
                    },
                ]
            ],
        ],
    ]); ?>

</div>
