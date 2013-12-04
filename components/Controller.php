<?php

class Controller extends CController
{
    public $layout = '//layouts/main';
    public $pageDescription = '';
    public $pageKeywords = '';
    public $allowRobots = true;
    public $breadcrumbs = array();
    private $_assetsUrl;

    public function init()
    {
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/bootstrap/css/bootstrap.min.css');
        Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/bootstrap/js/bootstrap.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/main.css');
    }

    protected function beforeRender($view)
    {
        $config = array(
            'baseUrl' => Yii::app()->baseUrl,
            'assetsUrl' => $this->getAssetsUrl(),
        );
        $js = 'var config=' . json_encode($config) . ';';
        Yii::app()->clientScript->registerScript('config', $js, CClientScript::POS_HEAD);
        return true;
    }

    protected function afterRender($view, &$output)
    {
        if ($this->pageKeywords)
            Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->pageKeywords), 'keywords');
        if ($this->pageDescription)
            Yii::app()->clientScript->registerMetaTag(CHtml::encode($this->pageDescription), 'description');
        if (!$this->allowRobots)
            Yii::app()->clientScript->registerMetaTag('noindex,nofollow', 'robots');
        return true;
    }

    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null) {
            $path = Yii::getPathOfAlias('application.assets');
            $this->_assetsUrl = Yii::app()->assetManager->publish($path, false, -1, YII_DEBUG);
        }
        return $this->_assetsUrl;
    }
}