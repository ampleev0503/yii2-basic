<?php

namespace app\controllers;

use app\models\Product;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $model = new Product();

        $model->id = 1;
        $model->name = "GUESS";
        $model->category = "shorts";
        $model->cost = 2000;

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
