<?php

use app\models\Usuarios;
use yii\bootstrap4\Html;
use app\models\Megustas;
use yii\helpers\Url;

$this->title = $model->id;
$this->params['breadcrumbs'][] = $this->title;
$user = Usuarios::find()->where(['id' => $model->usuario_id])->one();
?>
<div class="row">
    <div class="col-8">
        <?php
        $url1 = Url::to(['comentarios/view', 'id' => $model['id']]);
        $likeCount = Megustas::find()->where(['comentario_id' => $model['id']])->all();
        $likeNum = count($likeCount);
        $megusta = Megustas::find()->where(['usuario_id' => $user->id, 'comentario_id' => $model['id']])->one();

        if (isset($megusta)) {
            $url2 = Url::to(['megustas/delete', 'usuario_id' => $user->id, 'comentario_id' => $model['id']]);
        } else {
            $url2 = Url::to(['megustas/create', 'usuario_id' => $user->id, 'comentario_id' => $model['id']]);
        }
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <?= Html::a($user['log_us'], ['usuarios/view', 'id' => $user->id], ['class' => 'text-light']) ?>
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
                                        <a href="">
                                            <p class="text-light"><?= $likeNum ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 d-flex justify-content-center">

                            </div>
                            <div class="col-4" id="actions">
                                <?php if ($model['usuario_id'] == Yii::$app->user->id) : ?>
                                    <img src="trashcan.png" alt="borrar" id="eliminar">
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
            <div class="col-12">
                <br>
                <br>
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
                <br>
                <br>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <?php if ($r['texto'] == 'Seguir') : ?>
                    <?= Html::a($r['texto'], ['seguidores/create', 'seguido_id' => $user['id']], ['class' => 'btn btn-success']) ?>
                <?php elseif ($r['texto'] == 'Dejar de seguir') : ?>
                    <?= Html::a($r['texto'], ['seguidores/delete', 'seguido_id' => $user['id']], ['class' => 'btn btn-success', 'id' => 'unfollow']) ?>
                <?php else : ?>
                    <?= Html::a($r['texto'], ['usuarios/update'], ['class' => 'btn btn-success text-light']) ?>
                <?php endif; ?>
            </div>
            <div class="col-12">
                <br>
                <br>
            </div>
            <div class="col-12">
                <p><?= $user['bio'] ?></p>
            </div>
        </div>
    </div>
</div>