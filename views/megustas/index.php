<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MegustasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Megustas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="megustas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Megustas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'usuario_id',
            'comentario_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
