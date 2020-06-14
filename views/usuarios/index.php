<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comfav-index">

<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $userSearch,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'log_us',
        'nombre',
        'apellido',
        'email',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>


</div>
