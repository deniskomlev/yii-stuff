<?php
/**
 * Alternative implementation of CTimestampBehavior. Unlike of original one,
 * this one generates timestamp in PHP instead of creating SQL operator.
 * That solves issue when CTimestampBehavior uses NOW() for SQLite.
 */

class KTimestampBehavior extends CActiveRecordBehavior
{
    public $createAttribute;
    public $updateAttribute;
    public $setUpdateOnCreate = false;
    public $asDateTime = false;

    public function beforeSave($event)
    {
        $timestamp = (!$this->asDateTime) ? time() : date('Y-m-d H:i:s');
        if ($this->owner->isNewRecord) {
            if ($this->createAttribute)
                $this->owner->{$this->createAttribute} = $timestamp;
            if ($this->setUpdateOnCreate && $this->updateAttribute)
                $this->owner->{$this->updateAttribute} = $timestamp;
        }
        else {
            if ($this->updateAttribute)
                $this->owner->{$this->updateAttribute} = $timestamp;
        }
    }
}