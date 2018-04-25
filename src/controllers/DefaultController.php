<?php

namespace nullref\documents\controllers;

use nullref\core\interfaces\IAdminController;
use yii\web\Controller;

/**
 * Default controller for the `documents` module
 */
class DefaultController extends Controller implements IAdminController
{
    /**
     * Renders
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
