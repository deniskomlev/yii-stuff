<?php

class DefaultController extends BlankModuleController
{
    public function actionIndex()
    {
        $this->render('index');
    }
}