<?php

class AuthModule extends CWebModule
{
    protected function init()
    {
        $id = $this->getId();

        $this->setImport(array(
            $id.'.controllers.*',
        ));

        parent::init();
    }
}