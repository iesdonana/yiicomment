<?php

use app\models\Usuarios;
use Symfony\Component\VarDumper\VarDumper;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

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

    <div class="row">
        <div class="col-12 col-lg-6 mt-5 order-1 order-lg-0">
            <?php foreach ($comentarios as $comentario) : ?>
                <?php 
                    $id = $comentario['usuario_id'];
                    $log = Usuarios::findOne(['id' => $id]);
                ?>
                <div class="row">
                    <div class="col">
                        <h3 class=""><?= $log['log_us']?></h3>
                        <p class=""><?= $comentario['text']?></p>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>


</div>