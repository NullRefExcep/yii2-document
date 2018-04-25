<?php

use nullref\documents\helpers\Grid;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel \nullref\documents\models\DocumentItemSearch
 * @var $document \nullref\documents\models\Document
 * @var $variantNames array
 * @var $availableSizeOptionsMap array
 */

$this->title = Yii::t('documents', 'Document Items');
$this->params['breadcrumbs'][] = $this->title;

$removeUrlTemplate = yii\helpers\Url::to([
    '/documents/admin/document-item/delete-multiple',
    'ids' => 'DOCUMENT_ITEM_IDS',
]);

$this->registerJs(<<<JS
var removeUrl = "$removeUrlTemplate";

var documentItemCheckboxes = jQuery('.documentItemCheckbox');
var removeBtn = jQuery('#removeBtn');

documentItemCheckboxes.on('change', function (e) {
    var activeItems = documentItemCheckboxes.filter(':checked');
    if (activeItems.length > 0) {
        removeBtn.show();
        var selectedIds = [];
        activeItems.each(function (i, el) {
            selectedIds.push(jQuery(el).val());
        });
        removeBtn.attr('href', removeUrl.replace('DOCUMENT_ITEM_IDS', selectedIds.join(',')));
    } else {
        removeBtn.attr('href', '#');
        removeBtn.hide();
    }
});


JS
)
?>
<div class="document-item-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('documents', 'Delete selected'), [''], [
            'id' => 'removeBtn',
            'class' => 'btn btn-danger',
            'style' => 'display:none',
            'data-method' => 'post',
        ]) ?>
    </p>

    <?= GridView::widget([
        'id' => 'documentItemsGrid',
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => true,
            'lastPageLabel' => true,
        ],
        'columns' => array_merge([
            [
                'class' => CheckboxColumn::className(),
                'checkboxOptions' => [
                    'class' => 'documentItemCheckbox'
                ],
            ],
        ], Grid::getItemColumns($document),
            [
                'error',
                'status:documentItemStatus',
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                ],
            ]),
    ]); ?>

</div>
