<?php

namespace nullref\documents\controllers\admin;

use nullref\core\interfaces\IAdminController;
use nullref\documents\models\config\Export;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ExportConfigController implements the CRUD actions for Export model for export.
 */
class ExportConfigController extends Controller implements IAdminController
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
        $dataProvider = new ActiveDataProvider([
            'query' => Export::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Export model.
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
     * Creates a new Export model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param bool $redirect
     * @return mixed
     */
    public function actionCreate($redirect = false)
    {
        $model = Export::create();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($redirect) {
                return $this->redirect($redirect);
            } else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Export model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param bool $redirect
     * @return mixed
     */
    public function actionUpdate($id, $redirect = false)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($redirect) {
                return $this->redirect($redirect);
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Export model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        try {
            $model->delete();
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', Yii::t('documents', 'Cannot delete config with linked documents'));
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['index']);
    }
}
