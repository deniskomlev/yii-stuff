<?php

class Controller extends CController
{
    public $layout = '//layouts/main';
    public $pageDescription = '';
    public $pageKeywords = '';
    public $allowRobots = true;
    public $breadcrumbs = array();
    private $_assetsUrl;
    private $_jsConfig = array();
    private $_data = array();  // global data needed to be availiable in nested views

    public function init()
    {
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/bootstrap/css/bootstrap.min.css');
        Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/bootstrap/js/bootstrap.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/main.css');

        $this->setJsConfig(array(
            'baseUrl' => Yii::app()->baseUrl,
            'assetsUrl' => $this->getAssetsUrl(),
        ));
    }

    protected function beforeRender($view)
    {
        $this->registerJsConfig();
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

    public function data($param)
    {
        return isset($this->_data[$param])
            ? $this->_data[$param]
            : null;
    }

    public function setData($data)
    {
        if (is_array($data)) {
            $this->_data = CMap::mergeArray($this->_data, $data);
        }
    }

    public function setJsConfig($config)
    {
        if (is_array($config)) {
            $this->_jsConfig = CMap::mergeArray($this->_jsConfig, $config);
        }
    }

    protected function registerJsConfig()
    {
        $js = 'var config=' . json_encode($this->_jsConfig) . ';';
        Yii::app()->clientScript->registerScript('config', $js, CClientScript::POS_HEAD);
    }
}