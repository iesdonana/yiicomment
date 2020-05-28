<?php

use app\models\Usuarios;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use app\models\Megustas;

$model = Usuarios::findOne(Yii::$app->user->id);

$this->title = 'Bienvenido ' . $model['log_us'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-12">
                        <h1><?= Html::encode($this->title) ?></h1>
                    </div>
                    <div class="col-12">
                        <?php $form = ActiveForm::begin(); ?>
                            <?= $form->field($publicar, 'text')->textarea(['maxlength' => true, 'placeholder' => 'Publica algo...', ]) ?>
                            <div class="form-group d-flex justify-content-end">        
                                <?= Html::submitButton('Publicar', ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div> 
                </div>
                <?php foreach ($comentarios as $comentario) : ?>
                    <?php 
                        $id = $comentario['usuario_id'];
                        $log = Usuarios::findOne(['id' => $id]);
                        $url1 = Url::to(['comentarios/view', 'id' => $comentario['id']]);
                        $url3 = Url::to(['comentarios/delete', 'id' => $comentario['id']]);
                        $likeCount = Megustas::find()->where(['comentario_id' => $comentario['id']])->all();
                        $likeNum = count($likeCount);
                        $megusta = Megustas::find()->where(['usuario_id' => $id, 'comentario_id' => $comentario['id']])->one();

                    if (isset($megusta)) {
                            $url2 = Url::to(['megustas/delete', 'usuario_id' => $id, 'comentario_id' => $comentario['id']]);    
                    } else {
                            $url2 = Url::to(['megustas/create', 'usuario_id' => $id, 'comentario_id' => $comentario['id']]);
                    }
                    ?>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <?= Html::a($log['log_us'], ['usuarios/view', 'id' => $id], ['class' => 'text-light']) ?>
                                </div>
                                <a href="<?= $url1 ?>">
                                    <div class="card-body">
                                        <blockquote class="blockquote mb-0">
                                            <p class=""><?= $comentario['text'] ?></p>
                                        </blockquote>
                                    </div>
                                </a>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-4 d-flex justify-content-center">
                                            <div class="row">
                                                <div class="col-2 d-flex justify-content-center">
                                                    <a href="<?= $url2 ?>">
                                                        <?php if (isset($megusta)) : ?>
                                                            <img src="like.png" alt="like">
                                                        <?php else : ?>
                                                            <img src="dislike.svg" alt="dislike">
                                                        <?php endif; ?>
                                                    </a>
                                                </div>
                                                <div class="col-2 d-flex justify-content-center">
                                                    <a href="">
                                                        <p class="text-light"><?= $likeNum ?></p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 d-flex justify-content-center">

                                        </div>
                                        <div class="col-4">
                                            <?php if ($comentario['usuario_id'] == Yii::$app->user->id) : ?>
                                                <a href="<?= $url3 ?>">
                                                    <img src="trashcan.png" alt="borrar" id="eliminar">
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <p> </p>
                        <br>
                        <p> </p>
                    </div> 
                <?php endforeach; ?>
            </div>
            <div class="col-4">

            </div>
        </div>
    </div>
</div>
