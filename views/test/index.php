<?php
/**
 * @var \app\models\Product $model
 */
?>

<h1><?= $model->cost ?></h1>
<?= \yii\widgets\DetailView::widget(['model' => $model]) ?>
