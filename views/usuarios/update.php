<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = 'Editar ' . $model->log_us;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-8">

    </div>
    <div class="col-4 card" style="border: none">
        <div class="row card-body" id="d">
            <div class="col-12 d-flex justify-content-center">
                <h1><?= $model['log_us'] ?></h1>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6>Seguidores</h6>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6>Seguidos</h6>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6><?= $num_segr ?></h6>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6><?= $num_sego ?></h6>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group d-flex justify-content-center">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'bio')->textarea(['maxlength' => true, 'value' => $model['bio']]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>