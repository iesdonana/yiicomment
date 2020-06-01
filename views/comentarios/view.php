<?php

use app\models\Usuarios;
use yii\bootstrap4\Html;
use app\models\Megustas;
use yii\helpers\Url;
use app\models\Seguidores;

$this->title = $model->id;
$this->params['breadcrumbs'][] = $this->title;
$user = Usuarios::find()->where(['id' => $model->usuario_id])->one();
$url = Url::to(['seguidores/follow']);
$js = <<<EOT
var boton = $("#siguiendo");
boton.click(function(event) {
    event.preventDefault();
    $.ajax({
        method: 'GET',
        url: '$url',
        data: {
            'seguido_id': $model->usuario_id
        },
        success: function (data, code, jqXHR) {
            var texto= data[0]?"Dejar de seguir":"Seguir"
            boton.toggle("slide",1000);
            setTimeout( ()=> {
                boton.html(texto);
            }, 1000);
            boton.toggle("slide",1000);
            var seguidores = document.getElementById('seguidores')
            seguidores.innerHTML = data[1]
    }
    });
});
EOT;
$this->registerJs($js);
$seguidores = Seguidores::find()->where(['seguido_id' => $model['id']])->all();
$seguidos = Seguidores::find()->where(['seguidor_id' => $model['id']])->all();
?>
<div class="row">
    <div class="col-8">
        <?php
        $url1 = Url::to(['comentarios/view', 'id' => $model['id']]);
        $url3 = Url::to(['comentarios/delete', 'id' => $model['id']]);
        $likeCount = Megustas::find()->where(['comentario_id' => $model['id']])->all();
        $likeNum = count($likeCount);
        $megusta = Megustas::find()->andWhere([
            'comentario_id' => $model->id,
            'usuario_id' => Yii::$app->user->id,
        ])->one();
        $url2 = Url::to(['megustas/like', 'usuario_id' => Yii::$app->user->id, 'comentario_id' => $model['id']]);
        $url4 = Url::to(['megustas/view', 'comentario_id' => $model['id']]);
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-2 d-flex justify-content-center">
                                <img src="user.svg" alt="" id="user">
                            </div>
                            <div class="col-8">
                                <?= Html::a($user['log_us'], ['usuarios/view', 'id' => $user->id], ['class' => 'text-light']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p class="text-dark"><?= $model['text'] ?></p>
                        </blockquote>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-4 d-flex justify-content-center" id="actions">
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
                                        <a href="<?= $url4 ?>">
                                            <p class="text-light"><?= $likeNum ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 d-flex justify-content-center">

                            </div>
                            <div class="col-4" id="actions">
                                <?php if ($model['usuario_id'] == Yii::$app->user->id) : ?>
                                    <a href="<?= $url3 ?>">
                                        <img src="trashcan.png" alt="borrar" id="eliminar">
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 card" style="border: none">
        <div class="row card-body" id="d">
            <div class="col-12 d-flex justify-content-center">
                <h1><?= $user['log_us'] ?></h1>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <img src="user.svg" alt="" id="profile">
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
                <h6><?= Html::a(count($seguidores), ['seguidores/seguidos', 'usuario_id' => $model['id'], ['id' => 'seguidores']]) ?></h6>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6><?= Html::a(count($seguidos), ['seguidores/seguidos', 'usuario_id' => $model['id'], ['id' => 'seguidores']]) ?></h6>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <?php if ($model['usuario_id'] == Yii::$app->user->id) : ?>
                    <?= Html::a('Editar', ['usuarios/update'], ['class' => 'btn btn-success text-light']) ?>
                <?php else : ?>
                    <?= Html::a(Seguidores::siguiendo($model->usuario_id) ? 'Dejar de seguir' : 'Seguir', ['seguidores/follow', 'seguido_id' => $model->usuario_id], ['class' => 'btn btn-success text-light', 'id' => 'siguiendo']) ?>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <br>
            </div>
            <div class="col-12">
                <p class="text-light">Biografia.</p>
            </div>
            <div class="col-12">
                <p><?= $user['bio'] ?></p>
            </div>
            <div class="col-12">
                <br>
            </div>
            <div class="col-2 d-flex justify-content-end">
                <img src="placeholder.svg" id="location">
            </div>
            <div class="col-10">
                <p><?= $user['ubi'] ?></p>
            </div>
        </div>
    </div>
</div>