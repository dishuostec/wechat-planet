<?php

class FileCache extends CFileCache
{
    public $nameServiceKeys;
    public $nameServiceKeyMaster;
    public $nameServiceKeysSlave;
    public $serverConfigs;
    public $getIDC;
    public $setIDC;
    public $localIDC;
    public $getRetryCount;
    public $getRetryInterval;
    public $setRetryCount;
    public $setRetryInterval;
    public $enablePerformReport;
}