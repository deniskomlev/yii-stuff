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
    private $_cssFilesQueue = array();
    private $_scriptFilesQueue = array();

    public function init()
    {
        Yii::app()->clientScript->registerCoreScript('jquery');
        $this->enqueueCssFile(1, $this->getAssetsUrl() . '/libraries/bootstrap/css/bootstrap.min.css');
        $this->enqueueScriptFile(1, $this->getAssetsUrl() . '/libraries/bootstrap/js/bootstrap.min.js', CClientScript::POS_END);
        $this->enqueueCssFile(100, $this->getAssetsUrl() . '/css/main.css');
        $this->enqueueScriptFile(100, $this->getAssetsUrl() . '/js/main.js', CClientScript::POS_END);

        $this->setJsConfig(array(
            'baseUrl' => Yii::app()->baseUrl,
            'assetsUrl' => $this->getAssetsUrl(),
        ));
    }

    protected function beforeRender($view)
    {
        $this->registerEnqueuedResources();
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

    public function enqueueCssFile($priority, $url, $media = '')
    {
        $this->_cssFilesQueue[] = array(
            'priority' => $priority,
            'url' => $url,
            'media' => $media
        );
    }

    public function enqueueScriptFile($priority, $url, $position = null, $htmlOptions = array())
    {
        $this->_scriptFilesQueue[] = array(
            'priority' => $priority,
            'url' => $url,
            'position' => $position,
            'htmlOptions' => $htmlOptions
        );
    }

    public function dequeueCssFile($url)
    {
        foreach ($this->_cssFilesQueue as $key => $item) {
            if ($url == $item['url']) unset($this->_cssFilesQueue[$key]);
        }
    }

    public function dequeueScriptFile($url)
    {
        foreach ($this->_scriptFilesQueue as $key => $item) {
            if ($url == $item['url']) unset($this->_scriptFilesQueue[$key]);
        }
    }

    protected function registerJsConfig()
    {
        $js = 'var config=' . json_encode($this->_jsConfig) . ';';
        Yii::app()->clientScript->registerScript('config', $js, CClientScript::POS_HEAD);
    }

    protected function registerEnqueuedResources()
    {
        if (!empty($this->_cssFilesQueue)) {
            $queue = KArrayHelper::sortTable($this->_cssFilesQueue, 'priority');
            foreach ($queue as $item) {
                Yii::app()->clientScript->registerCssFile($item['url'], $item['media']);
            }
        }
        if (!empty($this->_scriptFilesQueue)) {
            $queue = KArrayHelper::sortTable($this->_scriptFilesQueue, 'priority');
            foreach ($queue as $item) {
                Yii::app()->clientScript->registerScriptFile($item['url'], $item['position'], $item['htmlOptions']);
            }
        }
    }
}