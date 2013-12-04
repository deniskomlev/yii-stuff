<?php
/**
 * This behavior allows to create url rules inside of a module file
 * called "rules.php" inside of module "config" directory.
 *
 * It should be specified in application "main.php" file in a following way:
 *
 * 'behaviors'=> array(
 *     array(
 *        'class'=>'application.utils.behaviors.KModuleUrlRulesBehavior',
 *     ),
 * ),
 *
 * The "rules.php" file must return an array like standard rules in config file.
 */

class KModuleUrlRulesBehavior extends CBehavior
{
    public function events()
    {
        return array_merge(parent::events(), array(
            'onBeginRequest' => 'beginRequest',
        ));
    }

    public function beginRequest($event)
    {
        $moduleDirectories = KFileHelper::getDirectoryList(Yii::getPathOfAlias('application.modules'));
        if ($moduleDirectories) {
            $urlManager = Yii::app()->getUrlManager();
            foreach ($moduleDirectories as $modulePath) {
                $rulesFile = $modulePath . '/config/rules.php';
                if (is_file($rulesFile))
                    $urlManager->addRules(require($rulesFile));
            }
        }
    }
}