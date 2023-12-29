<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\SectionTable;

CModule::IncludeModule('iblock');

$res = CIBlock::GetList();

$iblocks = [];
while ($dbRes = $res->Fetch()) {
    $iblocks[$dbRes['ID']] = $dbRes['NAME'];
}

$arParams = [
    'IBLOCK_ID' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('IBLOCK_NAME'),
        'DEFAULT' => 'image',
        'VALUES' => $iblocks,
        'REFRESH' => 'Y',
        'TYPE' => 'LIST',
        'ADDITIONAL_VALUES' => 'N',
    ],

	'SLIDER_TYPE' => [
            'PARENT' => 'IMAGE_SLIDER',
            'NAME' => Loc::getMessage('SLIDER_TYPE_NAME'),
            'DEFAULT' => 'image',
            'VALUES' => [
            	'image' => Loc::getMessage('SLIDER_TYPE_IMAGE_TEXT'),
            	'text' => Loc::getMessage('SLIDER_TYPE_TEXT_TEXT'),
            ],
			'REFRESH' => 'Y',
            'TYPE' => 'LIST',
            'ADDITIONAL_VALUES' => 'N',
    ],

	'AUTOSCROLL' => [
		'PARENT' => 'IMAGE_SLIDER',
		'NAME' => Loc::getMessage('AUTOSCROLL_TIP'),
		'TYPE' => 'STRING',
		'DEFAULT' => '6',
	],

    'LIMIT' => [
        'PARENT' => 'IMAGE_SLIDER',
        'NAME' => Loc::getMessage('LIMIT_NAME'),
        'DEFAULT' => 0,
        'TYPE' => 'STRING',
    ],

	'SHOW_ARROWS' => [
		'PARENT' => 'IMAGE_SLIDER',
		'NAME' => Loc::getMessage('SHOW_ARROWS_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	],

	'SHOW_DOTS' => [
		'PARENT' => 'IMAGE_SLIDER',
		'NAME' => Loc::getMessage('SHOW_DOTS_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	],

    'POSITION_TEXT' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('POSITION_TEXT'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'up' => Loc::getMessage('POSITION_UP_TEXT'),
            'down' => Loc::getMessage('POSITION_DOWN_TEXT'),
            'center' => Loc::getMessage('POSITION_CENTER_TEXT'),
        ],
        'DEFAULT' => 'down',
    ],

    'CACHE_TIME' => [],
];


$arDynamicParam = [
    'SHOW_TEXT' => [
        'PARENT' => 'IMAGE_SLIDER',
        'NAME' => Loc::getMessage('SHOW_TEXT_NAME'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'T',
    ],

    'TEXT_SOURCE' => [
        'PARENT' => 'IMAGE_SLIDER',
        'NAME' => Loc::getMessage('TEXT_SOURCE_NAME'),
        'TYPE' => 'LIST',
        'DEFAULT' => 'PREVIEW_TEXT',
        'VALUES' => [
            'DETAIL_TEXT' => Loc::getMessage('FIELD_DETAIL_TEXT'),
            'PREVIEW_TEXT' => Loc::getMessage('FIELD_PREVIEW_TEXT'),
        ],
    ],

    'PICTURE_SOURCE' => [
        'PARENT' => 'IMAGE_SLIDER',
        'NAME' => Loc::getMessage('PICTURE_SOURCE_NAME'),
        'DEFAULT' => 'DETAIL_PICTURE',
        'TYPE' => 'LIST',
        'VALUES' => [
            'DETAIL_PICTURE' => Loc::getMessage('DETAIL_PICTURE_TEXT'),
            'PREVIEW_PICTURE' => Loc::getMessage('PREVIEW_PICTURE_TEXT'),
        ],
    ],
];

$arParams = array_merge($arParams, $arDynamicParam);

$arComponentParameters = [
    'GROUPS' => [
        'SLIDER_TYPE' => [
            'NAME' => Loc::getMessage('SLIDER_TYPE_GROUP_NAME'),
			'SORT' => 150
        ],

        'IMAGE_SLIDER' => [
            'NAME' => Loc::getMessage('IMAGE_SLIDER_TYPE_NAME'),
			'SORT' => 160
        ],

        'TEXT_SLIDER' => [
            'NAME' => Loc::getMessage('TEXT_SLIDER_TYPE_NAME'),
			'SORT' => 170
        ],

        'ADDITIONAL' => [
            'NAME' => Loc::getMessage('ADDITIONAL_SETTINGS'),
			'SORT' => 180
        ],

    ],
    
    'PARAMETERS' => $arParams,
];