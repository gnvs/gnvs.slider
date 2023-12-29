<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => Loc::getMessage('GNVS_FAVORITE_DESCRIPTION_NAME'),
    "DESCRIPTION" => Loc::getMessage('GNVS_FAVORITE_DESCRIPTION_DESCRIPTION'),
    "ICON" => "/images/news_list.gif",
    "PATH" => array(
		"ID" => "Gnvs",
    	"NAME" => "Gnvs",
    	"SORT" => 10,
		"CHILD" => array(
			"ID" => "content",
			"NAME" => Loc::getMessage('GNVS_FAVORITE_DESCRIPTION_PATH_NAME'),
			"SORT" => 10,
		),
    ),
);

