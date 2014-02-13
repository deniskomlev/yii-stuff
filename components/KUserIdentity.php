<?php

class KUserIdentity extends CUserIdentity
{
    public $passwordRequired = true;
    public $verifyByHash = false;
    private $_id;

    public function authenticate()
    {
        $user = User::model()->findByAttributes(array(
            'username' => strtolower($this->username)
        ));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            if (!$this->passwordRequired) {
                $this->errorCode = self::ERROR_NONE;
            } else {
                $passwordCorrect = (!$this->verifyByHash)
                    ? CPasswordHelper::verifyPassword($this->password, $user->password)
                    : (strcmp($this->password, $user->password) === 0);
                if (!$passwordCorrect) {
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                } else {
                    $this->errorCode = self::ERROR_NONE;
                }
            }
        }

        if ($this->errorCode === self::ERROR_NONE) {
            $this->_id = $user->id;
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