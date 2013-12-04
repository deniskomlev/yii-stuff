<?php

class BlankModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            "{$this->id}.components.*",
        ));
    }
}