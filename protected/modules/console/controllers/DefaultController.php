<?php

class DefaultController extends CController
{
    public function actionIndex()
    {
        $this->renderPartial('index');
    }
}