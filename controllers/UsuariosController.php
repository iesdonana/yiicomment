<?php

namespace app\controllers;

use app\models\Comentarios;
use app\models\Megustas;
use app\models\Seguidores;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

require '../web/uploads3.php';

class UsuariosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['view', 'update', 'index', 'delete', 'like'],
                'rules' => [
                    [
                        'actions' => ['view', 'update', 'index', 'delete', 'like'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * A este action solo puede entrar el usuario admin, lista los usuarios registrados.
     *
     * @return void
     */
    public function actionIndex()
    {
        $id = Yii::$app->user->id;
        $usuario = Usuarios::findOne(['id' => $id]);

        if ($usuario['log_us'] != 'admin') {
            Yii::$app->session->setFlash('error', 'Si no eres admin no puedes entrar aqui :( .');
            return $this->redirect(['site/index']);
        }

        $userSearch = new UsuariosSearch();
        $dataProvider = $userSearch->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'userSearch' => $userSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Permite borrar usuarios.
     *
     * @param [type] $id
     * @return void
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user->id;
        $admin = Usuarios::findOne(['id' => $user]);

        if ($admin['log_us'] != 'admin') {
            Yii::$app->session->setFlash('error', 'Si no eres admin no puedes entrar aqui :( .');
            return $this->redirect(['site/index']);
        }

        $eliminar = Usuarios::findOne(['id' => $id]);
        $eliminar->delete();

        return $this->redirect(['usuarios/index']);
    }

    /**
     * Registro de usuarios y envio de correo de confirmacion.
     *
     * @return void
     */
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

    /**
     * Envia el email al usuario registrado.
     *
     * @param [type] $cuerpo
     * @param [type] $dest
     * @return void
     */
    public function enviarMail($cuerpo, $dest)
    {
        return Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpUsername'])
            ->setTo($dest)
            ->setSubject('Confirmar Cuenta')
            ->setHtmlBody($cuerpo)
            ->send();
    }

    /**
     * Activa la cuenta con el enlace del correo.
     *
     * @param [type] $id
     * @param [type] $token
     * @return void
     */
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

    /**
     * Lista todos los comentarios del usuario.
     *
     * @param [type] $id
     * @return void
     */
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

    /**
     * Lista todos los likes del usuario.
     *
     * @param [type] $id
     * @return void
     */
    public function actionLike($id)
    {
        $model = Usuarios::findOne(['id' => $id]);

        $likes = Megustas::find()->where(['usuario_id' => $id])->select('comentario_id')->column();

        $query = Comentarios::find()->where(['id' => $likes])->orderBy(['created_at' => SORT_DESC]);

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

        return $this->render('likes', [
            'model' => $model,
            'seguido_id' => $id,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
            'comentarios' => $comentarios,
            'pagination' => $pagination,
            'likes' => $likes
        ]);
    }

    /**
     * Actualiza la biografia, localizacion y la foto de perfil del usuario.
     *
     * @return void
     */
    public function actionUpdate()
    {
        $model = Usuarios::findOne(['id' => Yii::$app->user->id]);

        $seguidores = Seguidores::find()->where(['seguido_id' => Yii::$app->user->id])->all();
        $seguidos = Seguidores::find()->where(['seguidor_id' => Yii::$app->user->id])->all();

        $num_segr = count($seguidores);
        $num_sego = count($seguidos);

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($_FILES)) {
                uploadImagen($model);
                $model->url_img = $_FILES['Usuarios']['name']['url_img'];
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Se ha modificado tu perfil.');
                return $this->redirect(['usuarios/view', 'id' => $model['id']]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
        ]);
    }

    /**
     * Encontrar modelo con el id.
     *
     * @param [type] $id
     * @return void
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página no existe.');
    }

    /**
     * Busqueda de text y log_us de usuario en las tablas Usuarios y Comentarios.
     *
     * @return void
     */
    public function actionBusqueda()
    {
        $usuarios = new ActiveDataProvider([
            'query' => Usuarios::find()->where('1=0'),
        ]);
        $comentarios = new ActiveDataProvider([
            'query' => Comentarios::find()->where('1=0'),
        ]);

        if (($cadena = Yii::$app->request->get('cadena', ''))) {
            $usuarios->query->where(['ilike', 'log_us', $cadena]);
            $comentarios->query->where(['ilike', 'text', $cadena]);
        }

        return $this->render('busqueda', [
            'usuarios' => $usuarios,
            'comentarios' => $comentarios,
            'cadena' => $cadena,
        ]);
    }
}
