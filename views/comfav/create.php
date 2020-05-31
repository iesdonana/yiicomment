<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\comfav */

$this->title = 'Create Comfav';
$this->params['breadcrumbs'][] = ['label' => 'Comfavs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comfav-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
