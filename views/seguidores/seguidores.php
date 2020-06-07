<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Seguidores */

$this->params['breadcrumbs'][] = $this->title = 'Seguidores';
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-12">
        <div class="col-12">
            <h1>Seguidores</h1>
        </div>
        <div class="col-12">
            <hr>
        </div>
        <?php if ($usuarios == null) : ?>
            <div class="col-12 d-flex justify-content-center">
                <h3>Aqui no hay nada que enseñar</h3>
            </div>
        <?php endif; ?>
        <?php foreach ($usuarios as $usuario) : ?>
            <?php  ?>
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-2 d-flex justify-content-center">
                        <?php if ($usuario['url_img'] == 'user.svg') : ?>
                            <img src="user.svg" id="img">
                        <?php else : ?>
                            <?= Html::img(Yii::getAlias('@uploads') . '/' . $usuario->url_img) ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-10">
                        <div class="card-body">
                            <h5><?= Html::a($usuario['log_us'], ['usuarios/view', 'id' => $usuario['id']], ['class' => 'card-title text-dark']) ?></h5>
                            <p class="card-text text-dark"><?= $usuario['bio'] ?></p>
                            <p class="card-text d-flex justify-content-end"><img src="placeholder.svg" alt="" id="location" style="margin-right: 2%"><small><?= $usuario['ubi'] ?></small></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>