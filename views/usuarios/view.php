<?php

use Symfony\Component\VarDumper\VarDumper;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;
?>
<div class="usuarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <h1><?= $model['log_us'] ?></h1>

    <?php var_dump($model['id']) ?>

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($seguir, 'seguido_id')->hiddenInput(['value'=> $model['id']])->label(false); ?>
        <?= $form->field($seguir, 'seguidor_id')->hiddenInput(['value'=> Yii::$app->user->id])->label(false); ?>
        <?= Html::submitButton('Seguir', ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>
</div>
