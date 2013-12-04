<?php
/**
 * This behavior allows to swap two database records
 * using their position attribute.
 */

class KMoveUpDownBehavior extends CActiveRecordBehavior
{
    const DIRECTION_UP = 1;
    const DIRECTION_DOWN = 2;

    public $sortAttribute;
    public $criteria;       // additional criteria, if required

    /**
     * Swap the sort order attribute of current record with previous one.
     */
    public function moveUp()
    {
        if (!$this->owner->isNewRecord)
            $this->move(self::DIRECTION_UP);
    }

    /**
     * Swap the sort order attribute of current record with next one.
     */
    public function moveDown()
    {
        if (!$this->owner->isNewRecord)
            $this->move(self::DIRECTION_DOWN);
    }

    /**
     * Perform the swap operation.
     */
    protected function move($direction)
    {
        $dir = ($direction == self::DIRECTION_UP) ? ' DESC' : ' ASC';
        $op = ($direction == self::DIRECTION_UP) ? ' < ' : ' > ';

        $criteria = new CDbCriteria();
        $criteria->limit = 1;
        $criteria->order = $this->sortAttribute . $dir;
        $criteria->addCondition($this->sortAttribute . $op . $this->owner->{$this->sortAttribute});

        if ($this->criteria)
            $criteria->mergeWith($this->criteria);

        $nextRow = $this->owner->find($criteria);

        if ($nextRow) {
            $sortOrder = $this->owner->{$this->sortAttribute};
            $nextSortOrder = $nextRow->{$this->sortAttribute};
            if ($this->owner->saveAttributes(array($this->sortAttribute => $nextSortOrder))) {
                if (!$nextRow->saveAttributes(array($this->sortAttribute => $sortOrder))) {
                    // Trying to undo changes in first model if unable to save second model
                    $this->owner->saveAttributes(array($this->sortAttribute => $sortOrder));
                }
            }
        }
    }
}