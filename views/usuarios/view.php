<?php

use app\models\Megustas;
use Symfony\Component\VarDumper\VarDumper;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use app\models\Usuarios;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;
    /**<?php if ($r['texto'] == 'Seguir') : ?>
        <?= Html::a($r['texto'], ['seguidores/create', 'seguido_id' => $seguido_id], ['class' => 'btn btn-success']) ?>
    <?php else : ?>
        <?= Html::a($r['texto'], ['seguidores/delete', 'seguido_id' => $seguido_id], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>*/
?>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col-8">
            <?php foreach ($comentarios as $comentario) : ?>
                    <?php 
                        $id = $comentario['usuario_id'];
                        $log = Usuarios::findOne(['id' => $id]);
                        $url1 = Url::to(['comentarios/view', 'id' => $comentario['id']]);
                        $url2 = Url::to(['megustas/create', 'usuario_id' => $id, 'comentario_id' => $comentario['id']]);
                        $megusta = Megustas::find()->where(['usuario_id' => $id, 'comentario_id' => $comentario['id']])->one();
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
                                            <p class="text-light"><?= $comentario['text'] ?></p>
                                        </blockquote>
                                    </div>
                                </a>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-4 d-flex justify-content-center">
                                            <a href="<?= $url2 ?>">
                                                <?php if (isset($megusta)) : ?>
                                                    <img src="heart.svg" alt="like">
                                                <?php else : ?>
                                                    <img src="heart-outline.svg" alt="dislike">
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                        <div class="col-4">

                                        </div>
                                        <div class="col-4">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div> 
            <?php endforeach; ?>
            </div>
            <div class="col-4">
            
            </div>
        </div>
    </div>
</div>

