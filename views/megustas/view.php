<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Megustas */

$this->params['breadcrumbs'][] = ['label' => 'Megustas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="megustas-view">

    <?= var_dump($usuarios->count()) ?>

</div>
