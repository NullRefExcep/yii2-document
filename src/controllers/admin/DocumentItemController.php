<?php

namespace nullref\documents\controllers\admin;

use nullref\core\interfaces\IAdminController;
use nullref\documents\models\Document;
use nullref\documents\models\DocumentItem;
use nullref\documents\models\DocumentItemSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DocumentItemController implements the CRUD actions for DocumentItem model.
 */
class DocumentItemController extends Controller implements IAdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DocumentItem models.
     * @param int $id Document id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($id)
    {
        $document = Document::findOne($id);
        if (!$document) {
            throw new NotFoundHttpException('Requested page not found');
        }
        $params = Yii::$app->request->queryParams;
        $searchModel = new DocumentItemSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->query->andWhere(['document_id' => $id]);


        /** @var ActiveQuery $q */
        $q = clone $dataProvider->query;
        $variantNames = ArrayHelper::map($q->asArray()->all(), 'id', 'variant_name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'document' => $document,
            'variantNames' => $variantNames,
        ]);
    }


    /**
     * Displays a single DocumentItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the DocumentItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DocumentItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new DocumentItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DocumentItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DocumentItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DocumentItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $doc_id = $this->getDocumentId($id);
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'id' => $doc_id]);
    }

    protected function getDocumentId($documentItemId)
    {
        return DocumentItem::find()->select('document_id')->where(['id' => $documentItemId])->scalar();
    }

    /**
     * Deletes an existing DocumentItem models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $ids
     * @return mixed
     */
    public function actionDeleteMultiple($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $doc_id = $this->getDocumentId($id);
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index', 'id' => $doc_id]);
    }

}
