<?php

namespace app\controllers;

use app\models\EventForm;
use app\models\User;
use Yii;
use app\models\EventSearch;
use yii\web\NotFoundHttpException;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends BehaviorsController
{
    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if ($user->email_status == null) {
            return $this->redirect('profile-redirect');
        }

        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSetSchedule()
    {
        $model = new EventForm();

        $model->load(Yii::$app->request->post());

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Event model.
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
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EventForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $model EventForm */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EventForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EventForm::findOne($id)) !== null) {
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
