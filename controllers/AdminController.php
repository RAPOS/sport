<?php

namespace app\controllers;

use app\models\AdminPlaceFilterForm;
use app\models\Blog;
use app\models\Event;
use app\models\Notification;
use app\models\Place;
use app\models\NetCountry;
use app\models\NetCity;
use app\models\PlaceGalary;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\imagine\Image;
use app\models\UserProfile;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use app\components\BaseController;

class AdminController extends BaseController
{
    public $layout = "admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserAdmin(Yii::$app->user->getId());
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $statsAll['user'] = User::find()->count();
        $statsAll['placeAdopted'] = Place::find()->where('status = 1')->count();
        $statsAll['placeNotAdopted'] = Place::find()->where('status = 0')->count();
        $statsAll['event'] = Event::find()->count();

        $statsToday['user'] = User::find()->where("DATE_FORMAT(date_reg, '%Y-%m-%d') = CURDATE()")->count();
        $statsToday['placeAdopted'] = Place::find()->where("DATE_FORMAT(date, '%Y-%m-%d') = CURDATE() AND status = 1")->count();
        $statsToday['placeNotAdopted'] = Place::find()->where("DATE_FORMAT(date, '%Y-%m-%d') = CURDATE() AND status = 0")->count();
        $statsToday['event'] = Event::find()->where("DATE_FORMAT(date, '%Y-%m-%d') = CURDATE()")->count();

        return $this->render('index',[
            'statsAll' => $statsAll,
            'statsToday' => $statsToday,
        ]);
    }

    public function actionPlace()
    {
        $filterPlace = new AdminPlaceFilterForm();
        if(isset($_POST['AdminPlaceFilterForm']))
            $filterPlace->attributes = $_POST['AdminPlaceFilterForm'];

        $placis = Place::find();
        if (isset($_POST['AdminPlaceFilterForm']['city']) AND $_POST['AdminPlaceFilterForm']['city'])
            $placis->andWhere('city_id = '.$_POST['AdminPlaceFilterForm']['city']);

        if (isset($_POST['AdminPlaceFilterForm']['status']) AND $_POST['AdminPlaceFilterForm']['status'] !=9)
            $placis->andWhere('status = '.$_POST['AdminPlaceFilterForm']['status']);

        $searchModel = new Place();
        $dataProvider = new ActiveDataProvider([
            'query' => $placis,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'name' => [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                        'label' => 'name',
                        'default' => SORT_ASC
                    ],
                    'city_id' => [
                        'asc' => ['city_id' => SORT_ASC],
                        'desc' => ['city_id' => SORT_DESC],
                    ],
                    'adress' => [
                        'asc' => ['adress' => SORT_ASC],
                        'desc' => ['adress' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['city_id' => SORT_ASC],
                        'desc' => ['city_id' => SORT_DESC],
                    ]
                ]
            ],
        ]);

        $countries = NetCountry::getListCountries();

        if(isset($_POST['AdminPlaceFilterForm']['country']))
            $listCities = NetCity::getListCities($_POST['AdminPlaceFilterForm']['country']);
        else
            $listCities = [];


        return $this->render('place', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterPlace' => $filterPlace,
            'listCities' => $listCities,
            'countries' => $countries
        ]);
    }

    public function actionNewplace()
    {
        $countries = NetCountry::getListCountries();
        $modelPlace = new Place();
        $modelPlace->scenario = "adminAdd";

        if (Yii::$app->request->isAjax && $modelPlace->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelPlace);
        }

        if ($modelPlace->load(Yii::$app->request->post())) {
            $modelPlace->status = 1;
            $modelPlace->user_id = Yii::$app->user->getId();
            $modelPlace->save();
            $modelPlace->images = UploadedFile::getInstances($modelPlace, 'images');

            foreach ($modelPlace->images as $file) {
                $fileName = md5($file->baseName . time());
                $file->saveAs("upload/place/original/" . $fileName . "." . $file->extension);
                $galary = new PlaceGalary();
                $galary->place_id = $modelPlace->id;
                $galary->image = $fileName . "." . $file->extension;
                $galary->save();

                Image::thumbnail("upload/place/original/".$galary->image , 64, 64)
                    ->save('upload/place/mini/'.$fileName . "." . $file->extension, ['quality' => 60]);
                Image::thumbnail("upload/place/original/".$galary->image , 140, 140)
                    ->save('upload/place/normal/'.$fileName . "." . $file->extension, ['quality' => 60]);
                Image::thumbnail('upload/place/original/'. $galary->image, 1280, 1024)
                    ->save('upload/place/large/'. $fileName. '.' .$file->extension, ['quality' => 80]);
            }
            $this->redirect("/admin/place");
        }

        return $this->render('newPalce', [
            'modelPlace' => $modelPlace,
            'countries' => $countries,
            'cities' => [],
            'galary' => [],
        ]);
    }

    public function actionPlacedelete()
    {
        $place = Place::findOne($_GET['id']);
        $status = $place->status;
        $place->delete();
        if($status == 0)
            $this->redirect("/admin/place?type=notAdopted");
        else
            $this->redirect("/admin/place");
    }

    public function actionUserdelete()
    {
        User::findOne($_GET['id'])->delete();
        $this->redirect("/admin/users");
    }

    public function actionUseredit($id){
        $user = isset($id) ? User::findOne($id) : new User();
        $user->scenario = 'admin_edit';

        if (Yii::$app->request->isAjax && $user->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        if ($user->load(Yii::$app->request->post()) && $user->validate()) {
            $user->attributes = Yii::$app->request->post('User');
            $user->save();
            $this->redirect("/admin/users");
        }

        return $this->render('editUser', [
            'modelUser' => $user
        ]);
    }

    public function actionPlaceedit()
    {
        $place = Place::findOne($_GET['id']);
        $galary = $place->getImagesList("file-preview-image");
        $countries = NetCountry::getListCountries();
        $place->country_id = $place->netCity->country_id;
        $cities = NetCity::getListCities($place->country_id);


        if (Yii::$app->request->isAjax && $place->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($place);
        }

        if ($place->load(Yii::$app->request->post())) {
            $place->save();
            $place->images = UploadedFile::getInstances($place, 'images');
            foreach ($place->images as $file) {
                $fileName = md5($file->baseName . time());
                $file->saveAs("upload/place/original/" . $fileName . "." . $file->extension);
                $galary = new PlaceGalary();
                $galary->place_id = $place->id;
                $galary->image = $fileName . "." . $file->extension;
                $galary->save();

                Image::thumbnail("upload/place/original/".$galary->image , 64, 64)
                    ->save('upload/place/mini/'.$fileName . "." . $file->extension, ['quality' => 50]);

                Image::thumbnail("upload/place/original/".$galary->image , 140, 140)
                    ->save('upload/place/normal/'.$fileName . "." . $file->extension, ['quality' => 50]);

                Image::thumbnail('upload/place/original/'. $galary->image, 1280, 1024)
                    ->save('upload/place/large/'. $fileName. '.' .$file->extension, ['quality' => 80]);
            }
            $this->redirect("/admin/place");
        }


        return $this->render('newPalce', [
            'modelPlace' => $place,
            'countries' => $countries,
            'cities' => $cities,
            'galary' => $galary,
            'isEdit' => true
        ]);
    }

    public function actionAdopt()
    {
        $place = Place::findOne($_GET['id']);
        $place->status = 1;
        $place->save(false,['status']);
        $this->redirect("/admin/place");
    }

    public function actionAdoptavatar()
    {
        $profile = UserProfile::find()->where('id = :id', [':id' => $_GET['id']])->one();
        $profile->avatar_moderation = $_GET['status'];
        $profile->save(false);
        if ($_GET['status'] == 1)
            Notification::newNotification($profile->user_id, 6, $text = Yii::t('app', 'your_avatar_accept'));
        else
            Notification::newNotification($profile->user_id, 6, $text = Yii::t('app', 'your_avatar_reject'));

        $this->redirect("/admin/avatarmoderation");
    }

    public function actionUsers()
    {
        $users = User::find()->orderBy('id DESC');

        $searchModel = new User();
        $dataProvider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'first_name',
                    'email',
                    'date_reg'
                ]
            ],

        ]);

        return $this->render('users', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionAvatarmoderation()
    {
        $usersProfile = UserProfile::find()->where("photo != '' AND avatar_moderation = 0"); //orderBy('id DESC');

        $searchModel = new UserProfile();
        $dataProvider = new ActiveDataProvider([
            'query' => $usersProfile,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'user_id',
                    'photo',
                    'avatar_moderation'
                ]
            ],

        ]);

     //   print_r($dataProvider); die;

        return $this->render('avatar_moderation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionBlog()
    {
        $blog = Blog::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $blog,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('blog', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionBlogAdd($id = null)
    {
        $model = isset($id) ? Blog::findOne($id) : new Blog();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->attributes = Yii::$app->request->post('Blog');
            $model->date = time();
            $model->save();

            return $this->redirect('blog');
        }

        return $this->render('blog-add', ['model' => $model]);
    }

    public function actionBlogDelete($id)
    {
        Blog::findOne($id)->delete();

        return $this->redirect('blog');
    }
}


