<?php

use nullref\documents\models\config\Import as ImportConfig;
use nullref\documents\models\Document;
use rmrevin\yii\fontawesome\FA;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('documents', 'Documents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('documents', 'Upload Document'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('documents', 'Document Configs'), ['/documents/admin/import-config'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?php foreach (ImportConfig::getMap() as $id => $config): ?>
            <?= Html::a($config, ['create', 'ImportForm[configId]' => $id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php endforeach ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function (Document $model) {
                    return $model->getStatusReadable();
                }
            ],
            [
                'attribute' => 'file_path',
                'value' => function (Document $model) {
                    return $model->getShortPath();
                }
            ],
            [
                'attribute' => 'config_id',
                'value' => function (Document $model) {
                    if ($config = $model->config) {
                        return Html::a($config->name, ['/documents/admin/import-config/view', 'id' => $config->id]);
                    }
                    return null;
                },
                'format' => 'raw',
                'label' => Yii::t('documents', 'Config')
            ],
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{rerun} {bind} {view} {download} {delete}',
                'buttons' => [
                    'rerun' => function ($url, Document $model, $key) {
                        if ($model->status === Document::STATUS_ERROR) {
                            return Html::a(FA::icon(FA::_REFRESH), ['rerun', 'id' => $model->id]);
                        }
                        return '';
                    },
                    'bind' => function ($url, Document $model, $key) {
                        if ($model->status != Document::STATUS_NEW) {
                            return Html::a(FA::icon(FA::_LIST), ['/documents/admin/document-item/index', 'id' => $model->id]);
                        }
                        return '';
                    },
                    'download' => function ($url, Document $model, $key) {
                        $export = '';
                        if (isset($model->options['export_file'])) {
                            $export = Html::a(FA::i(FA::_ARROW_CIRCLE_O_DOWN), $model->options['export_file']) . ' ';
                        }
                        return $export . ($model->canBeDownloaded() ? Html::a(FA::i('download'), $url) : '');
                    },
                ]
            ],
        ],
    ]); ?>

</div>
