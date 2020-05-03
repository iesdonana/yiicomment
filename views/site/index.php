<?php

use app\models\Usuarios;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$titulo = Usuarios::findOne(Yii::$app->user->id);

$this->title = 'Bienvenido ' . $titulo['log_us'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($publicar, 'text')->textarea(['maxlength' => true, 'placeholder' => 'Publica algo...', ]) ?>
            <div class="form-group">        
                <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>

    <div class="container">
        <div class="col-12 col-lg-6 mt-5 order-1 order-lg-0">
            <?php foreach ($comentarios as $comentario) : ?>
                <?php 
                    $id = $comentario['usuario_id'];
                    $log = Usuarios::findOne(['id' => $id]);
                ?>
                <div class="row">
                    <div class="col-md-4">
                    <?php $form = ActiveForm::begin(); ?>
                    <?php $url = Url::to(['usuarios/view', 'id' => $comentario['usuario_id']]); ?>
                        <a href=<?= $url ?>><h3 class=""><?= $log['log_us']?></h3></a>
                    <?php ActiveForm::end(); ?>
                    </div>
                    <div class="col-6 col-md-4 comentarios">
                        <p class=""><?= $comentario['text']?></p>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>


</div>