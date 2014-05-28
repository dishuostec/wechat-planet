<?php

class ApiModule extends CWebModule
{
    protected function init()
    {
        $id = $this->getId();

        $this->setImport(array(
            $id.'.components.*',
            $id.'.controllers.*',
        ));

        parent::init();
    }
}