<?php

namespace app\controllers;

use app\models\Product;
use yii\helpers\VarDumper;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $model = new Product();

        $model->id = 1;
        $model->name = "  <b>  Test   </b>  ";
        $model->price = 0;
        $model->created_at = 1520269969;

        $model->validate();

        return VarDumper::dumpAsString($model->safeAttributes(), 5, true);

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionTurnOn()
    {
        $state = \Yii::$app->test->turnOn();
        return $this->render('turn', ['state' => $state]);
    }
}
