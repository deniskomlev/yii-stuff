<?php

class KPasswordBehavior extends CActiveRecordBehavior
{
    public $passwordAttribute = 'password';
    private $_oldPassword;

    public function afterFind($event)
    {
        $this->_oldPassword = $this->owner->{$this->passwordAttribute};
    }

    public function beforeSave($event)
    {
        $password = $this->owner->{$this->passwordAttribute};
        if ($this->owner->isNewRecord) {
            $this->owner->{$this->passwordAttribute} = CPasswordHelper::hashPassword($password);
        }
        else if (strcmp($password, $this->_oldPassword) !== 0) {
            $this->owner->{$this->passwordAttribute} = CPasswordHelper::hashPassword($password);
        }
    }
}