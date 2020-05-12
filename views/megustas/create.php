<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Megustas */

$this->title = 'Create Megustas';
$this->params['breadcrumbs'][] = ['label' => 'Megustas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="megustas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
