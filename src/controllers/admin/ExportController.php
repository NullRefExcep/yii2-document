<?php

namespace nullref\documents\controllers\admin;

use nullref\core\interfaces\IAdminController;
use nullref\documents\models\Document;
use nullref\documents\models\document\Export;
use nullref\documents\models\forms\ExportForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ExportController implements the CRUD actions for Export model.
 */
class ExportController extends Controller implements IAdminController
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
     * Lists all Export models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Document::find()
            ->andWhere(['type' => Document::TYPE_EXPORT])
            ->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $filePath = $model->file_path;

        return Yii::$app->response->sendFile($filePath);
    }

    /**
     * Finds the Export model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Export the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Export::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRerun($id)
    {
        $model = $this->findModel($id);
        ExportForm::runJob($model);
        return $this->redirect(['index']);
    }

    /**
     * Creates a new Export model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExportForm();

        $model->load(Yii::$app->request->get());

        if ($model->load(Yii::$app->request->post()) && $model->createRecord()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Export model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
