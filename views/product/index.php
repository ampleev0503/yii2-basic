<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                    'attribute' => 'name',
                    'value' => function(\app\models\Product $model) {
                        return Html::a($model->name, ['product/view', 'id' => $model->id]);
                    },
                    'format' => 'html',
            ],
            'price',
            [
                    'attribute' => 'created_at',
                    'contentOptions' => ['class' => 'small'],
                    'format' => 'datetime',
//                    'value' => function(\app\models\Product $model) {
//                        return \Yii::$app->formatter->format($model->created_at, 'datetime');
//                    },
            ],
            //'created_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
