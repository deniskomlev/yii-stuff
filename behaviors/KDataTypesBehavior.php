<?php

class KDataTypesBehavior extends CActiveRecordBehavior
{
    public $dataTypes = array();

    public function afterFind($event)
    {
        foreach ($this->dataTypes as $property => $type) {
            $value = $this->owner->getAttribute($property);
            settype($value, $type);
            $this->owner->setAttribute($property, $value);
        }
    }
}