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
                'only' => ['registrar'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    // everything else is denied by default
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

    public function actionIndex()
    {
        $model = Usuarios::findOne(['id' => Yii::$app->user->id]);

        $ids = $model->getSeguidos()->select('id')->column();

        $comentarios = Comentarios::find('comentario c')->where(['IN', 'usuario_id', $ids])->all();

        return $this->render('index', [
            'comentarios' => $comentarios
        ]);
    }

    public function actionView($id)
    {
        $model = Usuarios::findOne(['id' => $id]);

        $seguir = Seguidores::find()
        ->andWhere([
            'seguidor_id' => Yii::$app->user->id,
            'seguido_id' => $id
        ])->one();

        $comentarios = Comentarios::find()->where(['IN', 'usuario_id', $id])->orderBy(['created_at' => SORT_DESC])->all();

        $seguidores = Seguidores::find()->where(['seguido_id' => $id])->all();
        $seguidos = Seguidores::find()->where(['seguidor_id' => $id])->all();
        

        $num_segr = count($seguidores);
        $num_sego = count($seguidos);

        $r =[];

        if ($seguir) {
            $r['texto'] = 'Dejar de Seguir';
        } elseif ($id == Yii::$app->user->id) {
            $r['texto'] = 'Editar';
        } else {
            $r['texto'] = 'Seguir';
        }
    

        return $this->render('view', [
            'model' => $model,
            'r' => $r,
            'seguido_id' => $id,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
            'comentarios' => $comentarios,
        ]);
    }
}
