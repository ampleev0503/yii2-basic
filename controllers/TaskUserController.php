<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Yii;
use app\models\TaskUser;
use app\models\search\TaskUserSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskUserController implements the CRUD actions for TaskUser model.
 */
class TaskUserController extends Controller
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
                    'delete-all' => ['post']
                ],
            ],
        ];
    }

    /**
     * Creates a new TaskUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($taskId)
    {
        $task = Task::findOne($taskId);
        if(!$task || $task->creator_id != Yii::$app->user->id)
        {
            throw new ForbiddenHttpException();
        }

        $model = new TaskUser();
        $model->task_id = $taskId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Доступ предоставлен');
            return $this->redirect(['task/shared']);
        }

        $queryAccessedUsers = TaskUser::find()->select('user_id')->where(['task_id' => $taskId]);

        $users = User::find()
            ->where(['<>', 'id', Yii::$app->user->id])
            ->andWhere(['not in', 'id', $queryAccessedUsers])
            ->select('username')
            ->indexBy('id')
            ->column();

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Creates a new TaskUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDeleteAll($taskId)
    {
        $task = Task::findOne($taskId);
        if(!$task || $task->creator_id != Yii::$app->user->id)
        {
            throw new ForbiddenHttpException();
        }

        $task->unlinkAll(Task::RELATION_SHARED_USERS, true);

        Yii::$app->session->setFlash('success', 'Доступ удален');
        return $this->redirect(['task/shared']);
    }

    /**
     * Deletes an existing TaskUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(!$model || $model->task->creator_id != Yii::$app->user->id)
        {
            throw new ForbiddenHttpException();
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Доступ к этой задаче удален');
        return $this->redirect(['task/shared']);
    }

    /**
     * Finds the TaskUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TaskUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TaskUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
