<?php

namespace app\controllers;

use app\models\Product;
use app\models\User;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\web\Controller;

class TestController extends Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    public function actionIndex()
    {
//        $model = new Product();
//
//        $model->id = 1;
//        $model->name = "  <b>  Test   </b>  ";
//        $model->price = 0;
//        $model->created_at = 1520269969;
//
//        $model->validate();
//
//        return VarDumper::dumpAsString($model->safeAttributes(), 5, true);
//
//        return $this->render('index', [
//            'model' => $model,
//        ]);

        $model = User::findOne(3);
        $result = $model->accessedTasks;

        //return VarDumper::dumpAsString($result, 5, true);

        return $this->render('index', [
            'result' => $result,
        ]);
    }

    public function actionTurnOn()
    {
        $state = \Yii::$app->test->turnOn();
        return $this->render('turn', ['state' => $state]);
    }

    public function actionInsert()
    {
           // с помощью insert()
        \Yii::$app->db->createCommand()->insert('user', [
            'username' => 'fourth_name',
            'name' => 'Petr',
            'password_hash' => md5('pass4'),
            'creator_id' => 1,
            'created_at' => 1539109097,
        ])->execute();

        \Yii::$app->db->createCommand()->insert('user', [
            'username' => 'fifth_name',
            'name' => 'Olga',
            'password_hash' => md5('pass5'),
            'creator_id' => 2,
            'created_at' => 1539109097,
        ])->execute();

        \Yii::$app->db->createCommand()->insert('user', [
            'username' => 'sixth_name',
            'name' => 'Anna',
            'password_hash' => md5('pass6'),
            'creator_id' => 3,
            'created_at' => 1539109097,
        ])->execute();

        \Yii::$app->db->createCommand()->batchInsert('task', ['title', 'description', 'creator_id', 'created_at'], [
            ['The first task', 'test task 1', 1, 1539109097],
            ['The second task', 'test task 2', 2, 1539109097],
            ['The third task', 'test task 3', 3, 1539109097],
        ])->execute();
    }

    public function actionSelect()
    {
        $query1 = (new Query())->from('user')->where(['=', 'id', 1]); // правильнее вот так: where(['id' => 1])

        $result[] = $query1->one();

        $query2 = (new Query())->from('user')->where(['>', 'id', 1])->orderBy(['name' => SORT_ASC]);

        $result[] = $query2->all();

        $query3 = (new Query())->from('user');

        $result[] = $query3->count();

        $query4 = (new Query())->from('task')->innerJoin('user', 'user.id = task.creator_id');

        $result[] = $query4->all();

        return VarDumper::dumpAsString($result, 5, true);
    }
}
