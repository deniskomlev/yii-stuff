<?php

class BlankModel extends CActiveRecord
{
    /**
     * @var integer $id
     * @var integer $create_time
     * @var integer $update_time
     */

    /**
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{table_name}}';
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        return array(
            // your relations
        );
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        return array(
            // your rules
        );
    }

    /**
     * @return array the behaviors attached to model
     */
    public function behaviors()
    {
        return array(
            'KTimestampBehavior' => array(
                'class' => 'ext.utils.behaviors.KTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            // your code
            return true;
        }
        else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // your code
    }

    public function getUrl()
    {
        return (!$this->isNewRecord)
            ? Yii::app()->createUrl('site/default/view', array('id' => $this->id))
            : false;
    }
}