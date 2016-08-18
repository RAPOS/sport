<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.08.2015
 * Time: 11:49
 */

namespace app\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use app\models\User;

/* @property string $username */

class AccountActivation extends Model
{
    /* @var $user User */
    private $_user;

    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            throw new InvalidParamException('Ключ не может быть пустым!');
        $this->_user = User::findByEmailConfirmToken($key);
        if(!$this->_user)
            throw new InvalidParamException('Не верный ключ!');
        parent::__construct($config);
    }

    public function activateAccount()
    {
        $user                   = $this->_user;
        $user->status           = User::STATUS_ACTIVE;
        $user->email_status     = 1;
        $user->removeEmailConfirmToken();
        return $user->save() ? $user : false;
    }

    public function getUsername()
    {
        $user = $this->_user;
        return $user->username;
    }

}