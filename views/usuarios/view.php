<?php

use app\models\Megustas;
use app\models\Seguidores;
use Symfony\Component\VarDumper\VarDumper;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use app\models\Usuarios;
use yii\bootstrap4\LinkPager;

$this->title = $model->id;
$this->params['breadcrumbs'][] = $this->title;


$url = Url::to(['seguidores/follow']);
$js = <<<EOT
var boton = $("#siguiendo");
boton.click(function(event) {
    event.preventDefault();
    $.ajax({
        method: 'GET',
        url: '$url',
        data: {
            'seguido_id': $seguido_id
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
?>
<div class="row">
    <div class="col-8">
        <?php foreach ($comentarios as $comentario) : ?>
            <?php
            $id = $comentario['usuario_id'];
            $log = Usuarios::findOne(['id' => $id]);
            $url1 = Url::to(['comentarios/view', 'id' => $comentario['id']]);
            $url3 = Url::to(['comentarios/delete', 'id' => $comentario['id']]);
            $likeCount = Megustas::find()->where(['comentario_id' => $comentario['id']])->all();
            $likeNum = count($likeCount);
            $megusta = Megustas::find()->andWhere([
                'comentario_id' => $comentario->id,
                'usuario_id' => Yii::$app->user->id,
            ])->one();
            $url2 = Url::to(['megustas/like', 'usuario_id' => Yii::$app->user->id, 'comentario_id' => $comentario['id']]);
            $url4 = Url::to(['megustas/view', 'comentario_id' => $comentario['id']]);
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
                                    <?= Html::a($log['log_us'], ['usuarios/view', 'id' => $id], ['class' => 'text-light']) ?>
                                </div>
                            </div>
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
                                    <?php if ($comentario['usuario_id'] == Yii::$app->user->id) : ?>
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
            <div class="col-12">
                <p> </p>
                <br>
                <p> </p>
            </div>
        <?php endforeach; ?>
        <?= LinkPager::widget([
            'pagination' => $pagination
        ]); ?>
    </div>
    <div class="col-4 card" style="border: none">
        <div class="row card-body" id="d">
            <div class="col-12 d-flex justify-content-center">
                <h1 class="text-light"><?= $model['log_us'] ?></h1>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <img src="user.svg" alt="" id="profile">
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6 class="text-light">Seguidores</h6>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6 class="text-light">Seguidos</h6>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6 class="text-light" id="seguidores"><?= $num_segr ?></h6>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <h6 class="text-light"><?= $num_sego ?></h6>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <?php if ($seguido_id == Yii::$app->user->id) : ?>
                    <?= Html::a('Editar', ['usuarios/update'], ['class' => 'btn btn-success text-light']) ?>
                <?php else : ?>
                    <?= Html::a(Seguidores::siguiendo($seguido_id) ? 'Dejar de seguir' : 'Seguir', ['seguidores/follow', 'seguido_id' => $seguido_id], ['class' => 'btn btn-success text-light', 'id' => 'siguiendo']) ?>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <br>
            </div>
            <div class="col-12">
                <p class="text-light">Biografia.</p>
            </div>
            <div class="col-12">
                <p><?= $model['bio'] ?></p>
            </div>
            <div class="col-12">
                <br>
            </div>
            <div class="col-2 d-flex justify-content-end">
                <img src="placeholder.svg" id="location">
            </div>
            <div class="col-10">
                <p><?= $model['ubi'] ?></p>
            </div>
        </div>
    </div>
</div>