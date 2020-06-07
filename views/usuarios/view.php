<?php

use app\models\Megustas;
use app\models\Seguidores;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use app\models\Usuarios;
use yii\bootstrap4\LinkPager;
use app\models\comfav;


$this->title = 'Comentarios de ' . $model->log_us;
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
Yii::$app->formatter->locale = 'ES';
?>
<div class="row">
    <div class="col-8">
        <div class="row">
            <div class="col-4 d-flex justify-content-center">
                <h4><?= Html::a('Comentarios', ['usuarios/view', 'id' => $seguido_id]) ?></h4>
            </div>
            <div class="col-4 d-flex justify-content-center">
                <h4><?= Html::a('Likes', ['usuarios/like', 'id' => $seguido_id]) ?></h4>
            </div>
            <div class="col-4 d-flex justify-content-center">
                <h4><?= Html::a('Favoritos', ['comfav/view', 'id' => $seguido_id]) ?></h4>
            </div>
        </div>
        <div class="col-12">
            <hr>
        </div>
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
            $comfav = comfav::find()->andWhere([
                'comentario_id' => $comentario->id,
                'usuario_id' => Yii::$app->user->id,
            ])->one();
            $url2 = Url::to(['megustas/like', 'usuario_id' => Yii::$app->user->id, 'comentario_id' => $comentario['id']]);
            $url4 = Url::to(['megustas/view', 'comentario_id' => $comentario['id']]);
            $url5 = Url::to(['comfav/comfav', 'comentario_id' => $comentario['id']]);
            ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-2 d-flex justify-content-center">
                                    <?php if ($model['url_img'] == 'user.svg') : ?>
                                        <img src="user.svg" id="user">
                                    <?php else : ?>
                                        <?= Html::img(Yii::getAlias('@uploads') . '/' . $model->url_img) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="col-8">
                                    <?= Html::a($log['log_us'], ['usuarios/view', 'id' => $id], ['class' => 'text-light']) ?>
                                </div>
                                <div class="col-2 d-flex justify-content-center">
                                    <small><?= Yii::$app->formatter->asDate($comentario['created_at']); ?></small>
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
                                    <a href="<?= $url5 ?>">
                                        <?php if (isset($comfav)) : ?>
                                            <img src="marcado.svg" id="marcador">
                                        <?php else : ?>
                                            <img src="marcador.svg" id="marcador">
                                        <?php endif; ?>
                                    </a>
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
            <div class="col-12 d-flex justify-content-center" itemscope itemtype="http://schema.org/Person">
                <h1 itemprop="name" class="text-light"><?= $model['log_us'] ?></h1>
            </div>
            <div class="col-12 d-flex justify-content-center" itemscope itemtype="http://schema.org/Person">
                <?php if ($model['url_img'] == 'user.svg') : ?>
                    <img src="user.svg" id="profile">
                <?php else : ?>
                    <?= Html::img(Yii::getAlias('@uploads') . '/' . $model->url_img) ?>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-6 d-flex justify-content-center" itemscope itemtype="http://schema.org/Person">
                <h6 itemprop="follows" class="text-light">Seguidores</h6>
            </div>
            <div class="col-6 d-flex justify-content-center" itemscope itemtype="http://schema.org/Person">
                <h6 itemprop="follows" class="text-light">Seguidos</h6>
            </div>
            <div class="col-6 d-flex justify-content-center" itemscope itemtype="http://schema.org/Person">
                <h6 itemprop="follows"><?= Html::a($num_segr, ['seguidores/seguidores', 'usuario_id' => $model['id'], ['id' => 'seguidores']]) ?></h6>
            </div>
            <div class="col-6 d-flex justify-content-center" itemscope itemtype="http://schema.org/Person">
                <h6 itemprop="follows"><?= Html::a($num_sego, ['seguidores/seguidos', 'usuario_id' => $model['id'], ['id' => 'seguidores']]) ?></h6>
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
            <div class="col-10" itemscope itemtype="http://schema.org/Person">
                <p itemprop="address"><?= $model['ubi'] ?></p>
            </div>
        </div>
    </div>
</div>