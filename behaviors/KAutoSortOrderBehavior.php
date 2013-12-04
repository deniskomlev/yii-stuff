<?php
/**
 * This behavior automatically sets the sort order attribute
 * for new records.
 */

class KAutoSortOrderBehavior extends CActiveRecordBehavior
{
    public $sortAttribute;
    public $inverse = false;

    public function beforeSave($event)
    {
        if ($this->owner->isNewRecord) {
            $function = $this->inverse ? 'MIN' : 'MAX';
            $sortOrder = Yii::app()->db->createCommand()
                ->select($function . '(' . $this->sortAttribute . ')')
                ->from($this->owner->tableName())
                ->queryScalar();
            $sortOrder = $this->inverse ? $sortOrder - 1 : $sortOrder + 1;
            $this->owner->{$this->sortAttribute} = $sortOrder;
        }
    }
}