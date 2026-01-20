<?php
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if (ModuleManager::isModuleInstalled('custom.dealcontactrest')) {
    Loader::includeModule('custom.dealcontactrest');
}
