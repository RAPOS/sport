<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $comment
 * @property double $raiting
 * @property integer $status
 * @property integer $user_id
 * @property integer $type
 * @property integer $request_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Request $request
 * @property User $user
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'status', 'user_id', 'type', 'request_id', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['raiting'], 'number'],
            [['created_at', 'updated_at'], 'required'],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => Request::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'object_id' => Yii::t('app', 'Object ID'),
            'comment' => Yii::t('app', 'Comment'),
            'raiting' => Yii::t('app', 'Raiting'),
            'status' => Yii::t('app', 'Status'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'request_id' => Yii::t('app', 'Request ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function getCountComments($obj, $type , $rating)
    {
        return Comments::find()
            ->where(['object_id' => $obj])
            ->andWhere(['in', 'type', $type])
            ->andWhere(['in', 'rating', $rating])
            ->count();
        //return Comments::find()->where('object_id =:obj AND type IN ('.$type.') AND rating IN ('.$rating.')', [':obj' => $obj])->count();
    }
}
