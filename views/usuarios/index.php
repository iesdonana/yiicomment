<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuarios-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-12 col-lg-6 mt-5 order-1 order-lg-0">
            <?php foreach ($comentarios as $comentario) : ?>
                <div class="row">
                    <div class="col">
                        <p class=""><?= $comentario['text']?></p>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>


</div>
