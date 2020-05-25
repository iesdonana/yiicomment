<?php

namespace app\controllers;

use app\models\Comentarios;
use app\models\Megustas;
use app\models\Seguidores;
use app\models\Usuarios;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;


class UsuariosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['view', 'update'],
                'rules' => [
                    [
                        'actions' => ['view', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionRegistrar()
    {
        $model = new Usuarios(['scenario' => Usuarios::SCENARIO_CREAR]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha creado el usuario correctamente.');
            return $this->redirect(['site/login']);
        }

        return $this->render('registrar', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $model = Usuarios::findOne(['id' => $id]);

        $comentarios = Comentarios::find()->where(['IN', 'usuario_id', $id])->orderBy(['created_at' => SORT_DESC])->all();

        $seguidores = Seguidores::find()->where(['seguido_id' => $id])->all();
        $seguidos = Seguidores::find()->where(['seguidor_id' => $id])->all();

        $num_segr = count($seguidores);
        $num_sego = count($seguidos);

        return $this->render('view', [
            'model' => $model,
            'seguido_id' => $id,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
            'comentarios' => $comentarios,
        ]);
    }

    public function actionUpdate()
    {
        $model = Usuarios::findOne(['id' => Yii::$app->user->id]);

        $seguidores = Seguidores::find()->where(['seguido_id' => Yii::$app->user->id])->all();
        $seguidos = Seguidores::find()->where(['seguidor_id' => Yii::$app->user->id])->all();

        $num_segr = count($seguidores);
        $num_sego = count($seguidos);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado tu biografia.');
            return $this->redirect(['usuarios/view', 'id' => $model['id']]);
        }

        return $this->render('update', [
            'model' => $model,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
        ]);
    }
}
