<?php

namespace app\controllers;

use Yii;
use app\models\Comentarios;
use app\models\ComentariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Seguidores;
use yii\filters\AccessControl;
use app\models\Usuarios;

class ComentariosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Vista de un comentario.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $seguir = Seguidores::find()

        ->andWhere([
            'seguidor_id' => Yii::$app->user->id,
            'seguido_id' => $id
        ])->one();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Solo deja pasar al usuario admin y es para administrar comentarios
     *
     * @return void
     */
    public function actionIndex()
    {
       // $id = Yii::$app->user->id;
       // $usuario = Usuarios::findOne(['id' => $id]);

        //if ($usuario['log_us'] != 'admin') {
        //    Yii::$app->session->setFlash('error', 'Si no eres admin no puedes entrar aqui :( .');
        //    return $this->redirect(['site/index']);
        //}

        $searchModel = new ComentariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Recibe el id del comentario y lo elimina.
     * @param integer $id
     * @return void
     */
    public function actionDelete($id)
    {
        $model = Comentarios::findOne(['id' => $id]);

        $model->delete();

        return $this->goHome();
    }

    /**
     * Está funcion recibe el id y te devuelve el comentario
     * @param $id
     * @return void
     */
    protected function findModel($id)
    {
        if (($model = Comentarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
