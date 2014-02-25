<?php

class KActiveRecord extends CActiveRecord
{
    const SCENARIO_CREATE = 'insert';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
    const SCENARIO_SEARCH = 'search';

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     * @return CActiveRecord active record model instance.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns model ID
     *
     * @return integer
     */
    public function getId()
    {
        return (int) $this->id;
    }
}