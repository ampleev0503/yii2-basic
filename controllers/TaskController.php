<?php

namespace app\controllers;

use app\models\query\TaskQuery;
use Yii;
use app\models\Task;
use app\models\search\TaskSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'], // @ - для авторизованных; ! - для неавторизованных
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionMy()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        /** @var $query TaskQuery*/
        $query = $dataProvider->query;
        $query->byCreator(Yii::$app->user->id);

        return $this->render('my', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionShared()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        /** @var $query TaskQuery*/
        $query = $dataProvider->query;
        $query->byCreator(Yii::$app->user->id)
        ->innerJoinWith(Task::RELATION_TASK_USERS);

        return $this->render('shared', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionAccessed()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /** @var $query TaskQuery*/
        $query = $dataProvider->query;
        $query->innerJoinWith(Task::RELATION_TASK_USERS)
            ->where(['user_id' => Yii::$app->user->id]);

        return $this->render('accessed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dp = new ActiveDataProvider([
            'query' => $model->getTaskUsers(),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dp' => $dp,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Задача успешно создана');
            return $this->redirect(['task/my']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!$model || $model->creator_id != Yii::$app->user->id)
        {
            throw new ForbiddenHttpException();
        }



        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Задача успешно обновлена');
            return $this->redirect(['task/my']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        if(!$model || $model->creator_id != Yii::$app->user->id)
        {
            throw new ForbiddenHttpException();
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Задача успешно удалена');

        return $this->redirect(['task/my']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
