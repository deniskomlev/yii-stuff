<?php
/**
 * This behavior allows automatically generate transliterated and unique
 * alias based on some field.
 */

class KAutoAliasBehavior extends CActiveRecordBehavior
{
    public $sourceAttribute;
    public $aliasAttribute;
    public $maxLength = 50;

    public function beforeValidate($event)
    {
        if (KTextHelper::isEmptyString($this->owner->{$this->aliasAttribute})) {
            $source = $this->owner->{$this->sourceAttribute};
            $alias = str_replace('_', '-', Transliteration::file($source));
            $alias = substr($alias, 0, $this->maxLength);
            $alias = $this->getUniqueValue($alias);
            $this->owner->{$this->aliasAttribute} = $alias;
        }
    }

    protected function getUniqueValue($alias)
    {
        $count = 1;
        $condition = 'LOWER(' . $this->aliasAttribute . ') = :alias';
        $params[':alias'] = $alias;
        if (!$this->owner->isNewRecord) {
            $primaryKey = $this->owner->primaryKey();
            $condition .= ' AND ' . $primaryKey . ' != :pk';
            $params[':pk'] = $this->owner->$primaryKey;
        }
        while ($this->owner->exists($condition, $params)) {
            $params[':alias'] = $alias . '-' . $count;
            $count++;
        }
        return $params[':alias'];
    }
}