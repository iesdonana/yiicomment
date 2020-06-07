<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Usuarios;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php $log = Usuarios::findOne(['id' => Yii::$app->user->id]) ?>

</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-dark bg-dark navbar-expand-md fixed-top',
            ],
            'collapseOptions' => [
                'class' => 'justify-content-end',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                ['label' => 'Inicio', 'url' => ['/site/index']],
                ['label' => 'Busqueda', 'url' => ['usuarios/busqueda']],
                ['label' => 'Perfil', 'url' => ['usuarios/view', 'id' => Yii::$app->user->id]],
                [
                    'label' => Yii::$app->user->isGuest ? 'Usuarios' : $log['log_us'],
                    'items' => [
                        Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/site/login']]) : (Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->log_us . ')',
                                ['class' => 'dropdown-item'],
                            )
                            . Html::endForm()),
                        ['label' => 'Registrarse', 'url' => ['usuarios/registrar']],
                    ],
                ],
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container" style="padding-left: 0;">
            <?= Alert::widget() ?>
            <?= $content ?>
            <?php
            if (!Yii::$app->getRequest()->getCookies()->getValue('aceptar')) {
                Yii::$app->session->setFlash('warning', 'Este sitio usa cookies, pulsa en el boton para confirmar que aceptas el uso de cookies ' . Html::a('Aceptar', ['site/cookie'], ['class' => 'btn btn-outline-warning']));
            }
            ?>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>