<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $dp yii\data\ActiveDataProvider */


$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'creator_id',
            'updater_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php if ($model->creator_id == Yii::$app->user->id): ?>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dp,
            'columns' => [


                [
                    'label' => 'Username',
                    'value' => function(\app\models\TaskUser $model) {
                        return $model->user->username;
                    },
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, \app\models\TaskUser $model, $key) {
                            $ico = \yii\bootstrap\Html::icon('remove');
                            return Html::a($ico, ['task-user/delete', 'id' => $model->id],
                                [
                                    'data' =>  [
                                        'confirm' => 'Удалить пользователю доступ к этой задаче?',
                                        'method' => 'post',
                                    ]
                                ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    <?php endif; ?>



</div>
