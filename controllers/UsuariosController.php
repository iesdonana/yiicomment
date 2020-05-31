<?php

namespace app\controllers;

use app\models\Comentarios;
use app\models\Megustas;
use app\models\Seguidores;
use app\models\Usuarios;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;



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
            $url = Url::to([
                'usuarios/activar',
                'id' => $model->id,
                'token' => $model->token,
            ], true);

            $body = <<<EOT
                <h2>Pulsa el siguiente enlace para confirmar la cuenta de correo.<h2>
                <a href="$url">Confirmar cuenta</a>
            EOT;
            $this->enviarMail($body, $model->email);
            Yii::$app->session->setFlash('success', 'Se ha creado el usuario correctamente.');
            return $this->redirect(['site/login']);
        }

        return $this->render('registrar', [
            'model' => $model,
        ]);
    }

    public function enviarMail($cuerpo, $dest)
    {
        return Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpUsername'])
            ->setTo($dest)
            ->setSubject('Confirmar Cuenta')
            ->setHtmlBody($cuerpo)
            ->send();
    }

    public function actionActivar($id, $token)
    {
        $usuario = $this->findModel($id);
        if ($usuario->token === $token) {
            $usuario->token = null;
            $usuario->save();
            Yii::$app->session->setFlash('success', 'Usuario validado. Inicie sesión.');
            return $this->redirect(['site/login']);
        }
        Yii::$app->session->setFlash('error', 'La validación no es correcta.');
        return $this->redirect(['site/index']);
    }

    public function actionView($id)
    {
        $model = Usuarios::findOne(['id' => $id]);

        $query = Comentarios::find()->where(['IN', 'usuario_id', $id])->orderBy(['created_at' => SORT_DESC]);

        $count = $query->count();

        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => 5
        ]);

        $comentarios = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

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
            'pagination' => $pagination
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
            Yii::$app->session->setFlash('success', 'Se ha modificado tu perfil.');
            return $this->redirect(['usuarios/view', 'id' => $model['id']]);
        }

        return $this->render('update', [
            'model' => $model,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página no existe.');
    }
}
