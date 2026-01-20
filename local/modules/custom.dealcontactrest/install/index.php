<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;


class custom_dealcontactrest extends CModule
{
    public function __construct()
    {
        $this->MODULE_ID = basename(dirname(__DIR__));
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');
        $this->MODULE_ID = 'custom.dealcontactrest';
        $this->MODULE_NAME = 'REST сделки + контакты';
        $this->MODULE_DESCRIPTION = 'Расширение REST API: сделки и связанные контакты';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->PARTNER_NAME = 'Custom';
    }
    
    
    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }
    
    
    public function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}