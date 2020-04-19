<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Inicio';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($publicar, 'text')->textInput(['maxlength' => true]) ?>

            <div class="form-group">        
                <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?>
            </div>
    <?php ActiveForm::end(); ?>

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