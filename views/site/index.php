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

    <div class="row">
        <div class="col">
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($publicar, 'text')->textarea(['maxlength' => true, 'placeholder' => 'Publica algo...', ]) ?>
                <div class="form-group">        
                    <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <?php foreach ($comentarios as $comentario) : ?>
                <?php 
                    $id = $comentario['usuario_id'];
                    $log = Usuarios::findOne(['id' => $id]);
                    $url1 = Url::to(['comentarios/view', 'id' => $comentario['id']]);
                ?>
                    <div class="card">
                        <div class="card-header">
                            <?= Html::a($log['log_us'], ['usuarios/view', 'id' => $id]) ?>
                        </div>
                        <a href="<?= $url1 ?>">
                            <div class="card-body">
                                <blockquote class="blockquote mb-0">
                                    <p><?= $comentario['text'] ?></p>
                                </blockquote>
                            </div>
                        </a>
                    </div>
            <?php endforeach; ?>
        </div>
    </div>


</div>