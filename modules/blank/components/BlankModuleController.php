<?php

class BlankModuleController extends CController
{
    public function init()
    {
        parent::init();

        if (!Yii::app()->request->isAjaxRequest) {
            $assetsPath = Yii::getPathOfAlias('blank.assets');
            $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
            Yii::app()->clientScript->registerScriptFile($assetsUrl.'/blank.js', CClientScript::POS_END);
        }
    }
}