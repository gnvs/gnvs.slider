<?php

use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\TypeTable;
use Bitrix\Main\Context;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO\Directory as BitrixDirectory;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class gnvs_slider extends CModule
{
   public $MODULE_ID = "gnvs.slider";
   public $MODULE_NAME;
   public $MODULE_VERSION;
   public $MODULE_VERSION_DATE;
   public $MODULE_DESCRIPTION;
   public $PARTNER_NAME;
   public $PARTNER_URI;
   public $MODULE_GROUP_RIGHTS = "Y";

    public function __construct()
    {
        include __DIR__ . '/version.php';

        $this->PARTNER_NAME = Loc::getMessage('MODULE_GNVS_SLIDER_PARTHER_NAME');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->PARTNER_URI = 'https://gnvs.ru';

        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
    }

    function DoInstall()
    {
    	global $APPLICATION;
    	$request = Context::getCurrent()->getRequest();
    	
    	$step = (int) $request->get('step');
    	if ($step < 2) {
    		$APPLICATION->IncludeAdminFile(Loc::getMessage('GNVS_SLIDER_HELLO'), $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/" . $this->MODULE_ID . "/install/step1.php");
    	}
    	if ($step === 2) {
    		ModuleManager::registerModule($this->MODULE_ID);
    		if (Loader::includeModule($this->MODULE_ID)) {
                if ($request->get('iblock') === 'Y') {
                    $this->createModuleIblock();
                }
    			CopyDirFiles(
    				__DIR__ . '/components',
    				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/components',
    				true,
    				true
				);
    			$APPLICATION->IncludeAdminFile(Loc::getMessage('MODULE_GNVS_COMPLETE_INSTALL'), $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/" . $this->MODULE_ID . "/install/step2.php");
    		}
    	}
    }

    function DoUninstall()
    {
        global $APPLICATION;
        $request = Context::getCurrent()->getRequest();
        $step = (int) $request->get('step');

        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage('GNVS_SLIDER_HELLO'), $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/" . $this->MODULE_ID . "/install/unstep1.php");
        }
        if ($step === 2) {
            if ($request->get('savedata') === null) {
                $this->deleteModuleIblock();
            }
            BitrixDirectory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/gnvs/slider');
            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(Loc::getMessage('MODULE_GNVS_COMPLETE_UNINSTALL'), $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/" . $this->MODULE_ID . "/install/unstep2.php");
        }
    }

    function createModuleIblock()
    {
        global $DB;
        $DB->StartTransaction();
        if (!Loader::includeModule('iblock')) {
            return;
        }
        $type = new CIBlockType();
        $iblockType = $type->Add([
            'ID'       => 'gnvsslider',
            'SECTIONS' => 'Y',
            'IN_RSS'   => 'N',
            'SORT'     => 100,
            'LANG'     => [
                'ru' => [
                    'NAME' => Loc::getMessage('GNVS_IBLOCK_NAME'),
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements',
                ],
                'en' => [
                    'NAME' => 'Gnvs:Slider',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements',
                ],
            ],
        ]);
        if (empty($iblockType)) {
            $DB->Rollback();
            return;
        }

        $newIblockId = (new CIBlock())->Add([
            'ACTIVE'           => 'Y',
            'NAME'             => 'Gnvs slider',
            'CODE'             => 'gnvs.slider',
        	'API_CODE'		   => 'gnvsSlider',
            'LIST_PAGE_URL'    => '',
            'DETAIL_PAGE_URL'  => '',
            'SECTION_PAGE_URL' => '',
            'IBLOCK_TYPE_ID'   => 'gnvsslider',
            'LID'              => ['s1'],
            'SORT'             => 500,
            'GROUP_ID'         => ['2' => 'R'],
            'VERSION'          => 1,
            'BIZPROC'          => 'N',
            'WORKFLOW'         => 'N',
            'INDEX_ELEMENT'    => 'N',
            'INDEX_SECTION'    => 'N',
            'XML_ID'           => 'gnvs.slider',
            'ELEMENTS_NAME'    => Loc::getMessage('IBLOCK_ELEMENTS_NAME'),
            'ELEMENT_NAME'     => Loc::getMessage('IBLOCK_ELEMENT_NAME'),
            'SECTION_NAME'     => Loc::getMessage('IBLOCK_SECTION_NAME'),
            'SECTIONS_NAME'    => Loc::getMessage('IBLOCK_SECTIONS_NAME'),
            'LIST_MODE'        => 'S',
            'SECTION_PROPERTY' => 'Y',
            'PROPERTY_INDEX'   => 'N',
        ]);

        if ($newIblockId === false) {
            $DB->Rollback();
            return;
        }

        // Беру картинки для установки в элемент инфоблока
        $elements = [
            ['NAME' => Loc::getMessage('GNVS_ELEMENT_1'), 'PICTURE' => __DIR__ . '/images/slide1.jpg'],
            ['NAME' => Loc::getMessage('GNVS_ELEMENT_1'), 'PICTURE' => __DIR__ . '/images/slide2.jpg'],
        ];

        $el = new CIBlockElement;

        foreach ($elements as $element) {            
            $el->Add([
                'NAME' => $element['NAME'],
                'PREVIEW_PICTURE' => CFile::MakeFileArray($element['PICTURE']),
                'DETAIL_PICTURE' => CFile::MakeFileArray($element['PICTURE']),
                'PREVIEW_TEXT' => '<a href="https://gnvs.ru">' . Loc::getMessage('GNVS_SLIDER_MORE') . '</a>',
                'PREVIEW_TEXT_TYPE' => 'html',
                'DETAIL_TEXT' => '<a href="https://gnvs.ru">' . Loc::getMessage('GNVS_SLIDER_MORE') . '</a>',
                'DETAIL_TEXT_TYPE' => 'html',
                'IBLOCK_ID' => $newIblockId,
            ]);
        }

        $DB->Commit();
    }

    function deleteModuleIblock()
    {
        global $DB;
        if (!Loader::includeModule('iblock')) {
            return;
        }
        $DB->StartTransaction();
        $iblock = IblockTable::getList(
            [
                'filter' => ['CODE' => 'gnvs.slider',],
                'select' => ['ID'],
                'limit' => 1
            ]
        )->fetch();
        if (empty($iblock)) {
            $DB->Rollback();
            return;
        }
        $iblockDeleteResult = CIblock::Delete($iblock['ID']);
        if (!$iblockDeleteResult) {
            $DB->Rollback();
            return;
        }
        $iblockTypeDeleteResult = TypeTable::delete('gnvsslider');
        if (!$iblockTypeDeleteResult->isSuccess()) {
            $DB->Rollback();
            return;
        }
        $DB->Commit();
    }
}