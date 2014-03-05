<?php

/**
 * Helper class to provide easier access to functionality which is
 * already included in Yii but sometimes a is bit difficult to remember.
 *
 * @version 1.0.2 (2014-03-05)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KYiiHelper
{
    // ------------------------------------------------------------------------

    /**
     * Validate the email address.
     */
    static public function isValidEmail($email)
    {
        $validator = new CEmailValidator;
        return $validator->validateValue($email);
    }

    // ------------------------------------------------------------------------

    static public function getCookie($name)
    {
        return Yii::app()->request->cookies->contains($name)
            ? Yii::app()->request->cookies[$name]->value
            : null;
    }

    // ------------------------------------------------------------------------

    static public function setCookie($name, $value, $options = array())
    {
        if (isset($options['time'])) {
            $options['expire'] = time() + $options['time'];
            unset($options['time']);
        }
        Yii::app()->request->cookies[$name] = new CHttpCookie($name, $value, $options);
    }

    // ------------------------------------------------------------------------

    static public function removeCookie($name, $options = array())
    {
        Yii::app()->request->cookies->remove($name, $options);
    }

    // ------------------------------------------------------------------------

    /**
     * Converts the filesystem path to url.
     */
    static public function getUrlFromPath($path)
    {
        $path = KFileHelper::fixSeparator($path, '/');
        $webroot = KFileHelper::fixSeparator(Yii::getPathOfAlias('webroot'), '/');
        return Yii::app()->baseUrl . KTextHelper::trimPrefix($path, $webroot);
    }

    // ------------------------------------------------------------------------

    /**
     * Allows to include view file containing JavaScript code. It will be
     * parsed like standard view file and registered using registerScript()
     * method of ClientScript component.
     *
     * The view file may contain opening and closing <script> tag.
     *
     * @param string  $view
     * @param integer $position
     * @param array   $htmlOptions
     * @return string
     */
    static public function registerScriptFromView($view, $position = null, $htmlOptions = array())
    {
        $script = Yii::app()->getController()->renderPartial($view, null, true);
        $script = trim($script);
        $script = preg_replace('%^<script[^>]*>%', '', $script);
        $script = preg_replace('%</script>$%', '', $script);
        $id = Yii::app()->getController()->getViewFile($view);
        return Yii::app()->getClientScript()->registerScript($id, $script, $position, $htmlOptions);
    }
}