<?php

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

Loader::includeModule('crm');

EventManager::getInstance()->addEventHandler(
    'rest',
    'OnRestServiceBuildDescription',
    ['Custom\\DealContactRest\\Rest\\Service', 'getDescription']
);
