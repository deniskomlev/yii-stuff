<?php

class UserIdentity extends CUserIdentity
{
    public $passwordRequired = true;
    private $_id;

    public function authenticate()
    {
        $user = User::model()->findByAttributes(array(
            'username'=>strtolower($this->username)
        ));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        elseif ($this->passwordRequired && !CPasswordHelper::verifyPassword($this->password, $user->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->_id = $user->id;
            $this->errorCode = self::ERROR_NONE;
        }

        return $this->errorCode === self::ERROR_NONE;
    }

    /**
     * By default, Yii::app()->user->id returns username instead of database ID.
     * Override this behaviour.
     */
    public function getId()
    {
        return $this->_id;
    }
}