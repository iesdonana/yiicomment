<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\comfav */

$this->title = 'Update Comfav: ' . $model->usuario_id;
$this->params['breadcrumbs'][] = ['label' => 'Comfavs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usuario_id, 'url' => ['view', 'usuario_id' => $model->usuario_id, 'comentario_id' => $model->comentario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comfav-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
