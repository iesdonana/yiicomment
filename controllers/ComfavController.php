<?php

namespace app\controllers;

use Yii;
use app\models\comfav;
use app\models\comfavSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Comentarios;
use app\models\Seguidores;
use app\models\Usuarios;
use yii\data\Pagination;



/**
 * ComfavController implements the CRUD actions for comfav model.
 */
class ComfavController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all comfav models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new comfavSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single comfav model.
     * @param integer $usuario_id
     * @param integer $comentario_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Usuarios::findOne(['id' => $id]);

        $likes = comfav::find()->where(['usuario_id' => $id])->select('comentario_id')->column();

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

        return $this->render('view', [
            'model' => $model,
            'seguido_id' => $id,
            'num_segr' => $num_segr,
            'num_sego' => $num_sego,
            'comentarios' => $comentarios,
            'pagination' => $pagination,
            'likes' => $likes
        ]);
    }

    public function actionComfav($comentario_id)
    {
        $model = comfav::find()->andWhere([
            'comentario_id' => $comentario_id,
            'usuario_id' => Yii::$app->user->id,
        ])->one();

        if ($model) {
            $model->delete();
            return $this->goBack();
        } else {
            $like = new comfav([
                'comentario_id' => $comentario_id,
                'usuario_id' => Yii::$app->user->id,
            ]);
            $like->save();
            return $this->goBack();
        }
    }

    /**
     * Finds the comfav model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $usuario_id
     * @param integer $comentario_id
     * @return comfav the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($usuario_id, $comentario_id)
    {
        if (($model = comfav::findOne(['usuario_id' => $usuario_id, 'comentario_id' => $comentario_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
