<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\PlaceForm;
use app\models\PlaceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlaceController implements the CRUD actions for Place model.
 */
class PlaceController extends BehaviorsController
{
    /**
     * Lists all Place models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if ($user->email_status == null) {
            return $this->redirect('profile-redirect');
        }

        $searchModel = new PlaceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Place model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Place model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlaceForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'message' => \Yii::t('app', 'Место успешно добавлено.'),
                ]
            );
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Place model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'message' => \Yii::t('app', 'Место успешно изменено.'),
                ]
            );
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Place model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        \Yii::$app->session->set(
            'message',
            [
                'type' => 'success',
                'message' => \Yii::t('app', 'Место успешно удалено.'),
            ]
        );

        return $this->redirect(['index']);
    }

    /**
     * Finds the Place model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlaceForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlaceForm::findOne($id)) !== null) {
            if ($model->user->email_status == null) {
                return $this->redirect('profile-redirect');
            }
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProfileRedirect()
    {
        \Yii::$app->session->set(
            'message',
            [
                'type' => 'danger',
                'message' => \Yii::t('app', 'Подтвердите ваш емайл.'),
            ]
        );
        if (Yii::$app->user->can('office')) {
            return $this->redirect('/profile/office-profile');
        }

        return $this->redirect('/profile/user-profile');
    }
}
