<?php

namespace app\controllers;

use Yii;
use app\models\Comentarios;
use app\models\ComentariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Seguidores;
use yii\filters\AccessControl;

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
                'only' => ['view', 'create', 'delete'],
                'rules' => [
                    [
                        'actions' => ['view', 'create', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays a single Comentarios model.
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

        $seguidores = Seguidores::find()->where(['seguido_id' => $id])->all();
        $seguidos = Seguidores::find()->where(['seguidor_id' => $id])->all();
        
        $num_segr = count($seguidores);
        $num_sego = count($seguidos);

        return $this->render('view', [
            'model' => $model,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
        ]);
    }

    /**
     * Creates a new Comentarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $id = Yii::$app->user->id;

        if ($id === null) {
            Yii::$app->session->setFlash('error', 'Debe estar logueado.');
            return $this->goHome();
        }

        $model = new Comentarios(['usuario_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index.php');
        }
    }

    /**
     * 
     * @param integer $id
     * @return void
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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
