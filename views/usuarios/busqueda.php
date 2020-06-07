<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'My Yii Application';
?>
<div class="site-index">

        <p >Busca por usuarios o comentario.</p>

        <p>
            <?= Html::beginForm(['usuarios/busqueda'], 'get') ?>
                <div class="form-group">
                    <?= Html::textInput('cadena', $cadena, ['class' => 'form-control']) ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                </div>
            <?= Html::endForm() ?>
        </p>


    <div class="body-content">
        <?php if ($usuarios->totalCount > 0): ?>
            <h3>Usuarios</h3>
            <div class="row">
                <?= GridView::widget([
                    'dataProvider' => $usuarios,
                    'columns' => [
                        'log_us',
                        'nombre',
                        'apellido',
                        'email',
                        'bio',
                    ],
                ]) ?>
            </div>
        <?php endif ?>
        <?php if ($comentarios->totalCount > 0): ?>
            <h3>Comentarios</h3>
            <div class="row">
                <?= GridView::widget([
                    'dataProvider' => $comentarios,
                    'columns' => [
                        'text',
                        'created_at'
                    ],
                ]) ?>
            </div>
        <?php endif ?>
    </div>
</div>