<?php

use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Result;
use Bitrix\Main\Error as BitrixError;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use \Bitrix\Main\Engine\Response\Converter;

class SimpleSliderComponent extends CBitrixComponent
{
    private const DEFAULT_TYPE = 'image';

    private const DEFAULT_SOURCE = 'DETAIL_PICTURE';

    private const DEFAULT_LINK_TEXT_SOURCE = 'SLIDER_LINK_TEXT';

    private const IBLOCK_CODE = 'gnvs.slider';

    private const DEFAULT_LIMIT = 0;

    /**
     * @param $component
     * @throws LoaderException
     */
    public function __construct($component = null)
    {
    	$this->result = new Result();

        if (!Loader::includeModule('gnvs.slider')) {
            $this->result->addError(
                new BitrixError(Loc::getMessage('MODULE_INCLUDE_ERROR', ['#MODULE_NAME#' => 'gnvs.slider']), 500)
            );
        }

        if (!Loader::includeModule('iblock')) {
            $this->result->addError(
                new BitrixError(Loc::getMessage('MODULE_INCLUDE_ERROR', ['#MODULE_NAME#' => 'iblock']), 500)
            );
        }
        parent::__construct($component);
    }

    /**
     *
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['SLIDER_TYPE'] = $arParams['SLIDER_TYPE'] ?? self::DEFAULT_TYPE;

        $arParams['LIMIT'] = $arParams['LIMIT'] ?? self::DEFAULT_LIMIT;

        $arParams['AUTOSCROLL'] = intval ($arParams['AUTOSCROLL']);
        
        $arParams['SHOW_DOTS'] = ($arParams['SHOW_DOTS'] == 'Y'?true:false);
        $arParams['SHOW_ARROWS'] = ($arParams['SHOW_ARROWS'] == 'Y'?true:false);

        if ($arParams['SLIDER_TYPE'] === self::DEFAULT_TYPE) {

            if (empty($arParams['PICTURE_SOURCE'])) {
                $arParams['PICTURE_SOURCE'] = self::DEFAULT_SOURCE;
            }
        }

        if ($arParams['SLIDER_TYPE'] === 'text' && empty($arParams['TEXT_SOURCE'])) {
            $this->result->addError(new BitrixError(
                Loc::getMessage('PARAM_NOT_FOUND_ERROR', ['#PARAM_NAME#' => 'TEXT_SOURCE'], 400)
            ));
        }

        if ($arParams['TEXT_ALLOW_LINK'] === 'Y') {
            $arParams['SELECTED_PROPERTIES'][] = $arParams['LINK_TEXT_SOURCE'] = $arParams['LINK_TEXT_SOURCE'] ?: self::DEFAULT_LINK_TEXT_SOURCE;
        }

        if ($arParams['PICTURE_SOURCE'] !== self::DEFAULT_SOURCE && !in_array($arParams['PICTURE_SOURCE'], $this->getDefaultFieldSelect())) {
                $arParams['SELECTED_PROPERTIES'][] = $arParams['PICTURE_SOURCE'];
        }

        if (!empty($arParams['TEXT_IMAGE_SOURCE']) && !in_array($arParams['PICTURE_SOURCE'], $this->getDefaultFieldSelect())) {
            $arParams['SELECTED_PROPERTIES'][] = $arParams['PICTURE_SOURCE'];
        }

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     *
     *
     * @return mixed|null
     */
    public function executeComponent()
    {
        try {
        	if ($this->result->getErrorCollection()->isEmpty()) {

                if ($this->startResultCache((int)$this->arParams['CACHE_TIME'] ?: 3600 * 24)) {

                	$elements = $this->getElementsBySection($this->arParams['SECTION_ID'], $this->arParams['LIMIT']);
                    
                    $this->result->setData([
                    	'ELEMENTS' => $elements, 
					]);
                    

                    $this->arResult = $this->result->getData();
                    $this->arResult['ERRORS'] = $this->result->getErrorCollection();
                    
                    $this->includeComponentTemplate();

                    return parent::executeComponent();
                }

            }
            else {
            	$this->__showError($this->result->getErrorCollection()->getValues()[0]->getMessage());
            }
        } catch (Exception $exception) {
            $this->result->addError(new BitrixError($exception->getMessage()), 500);
            foreach ($this->result->getErrorCollection()->getValues() as $error) {
                ShowError($error->getMessage());
            }
        }
    }

    /**
     * @param string $section
     * @param int $limit
     * @return array
     */
    public function getElementsBySection($section, int $limit): ?array
    {
        try {
            $iblockApiCode = \CIBlock::GetList([], ['ID' => $this->arParams['IBLOCK_ID'], false])->Fetch()['API_CODE'];
            $entityDataClass = "\Bitrix\Iblock\Elements\Element{$iblockApiCode}Table";

            if (!class_exists($entityDataClass)) {
                throw new \Exception(Loc::getMessage('ERROR_NOT_FOUND_IBLOCK'));
            }

            $select = $this->getDefaultFieldSelect();
            $elements = $entityDataClass::getList([
                    'select' => $select,
                    'limit' => $limit,
            ])->fetchCollection();

            $result = [];
            foreach ($elements as $element) {
                $el = [];

                foreach ($this->getDefaultFieldSelect() as $field) {
                    $el[$field] = $element->get($field);
                }

                //Get edit link
                //TODO:Set function
                $buttons = CIBlock::GetPanelButtons(
                    $element["IBLOCK_ID"],
                    $element["ID"],
                    0,
                    ["SECTION_BUTTONS" => false, "SESSID" => false]
                );
                $el["EDIT_LINK"] = $buttons["edit"]["edit_element"]["ACTION_URL"];

                //Get image path
                switch ($this->arParams['SLIDER_TYPE']) {
                    case 'text':
                        if ($this->arParams['TEXT_ALLOW_IMAGE'] === 'Y') {
                            $el['IMG'] =  $this->getImagePath($element->get($this->arParams['PICTURE_SOURCE']));
                        }
                    break;

                    default:
                        $el['IMG'] =  $this->getImagePath($element->get($this->arParams['PICTURE_SOURCE']));
                    break;
                }

                $result[$element->getId()] = $el;
            }

            return $result;
        } catch (Exception $exception) {
            $this->result->addError(
                new BitrixError($exception->getMessage(), 500)
            );
        }

        return null;
    }

    /**
     *
     * @param int $id
     * @return string
     */
    private function getImagePath(int $id): string
    {
    	$picture = [];
        $picture['src'] = CFile::GetPath($id);

        return $picture['src'] ?? '';
    }

    /**
     * @return string[]
     */
    private function getDefaultFieldSelect(): array
    {
        return [
            'ID',
            'IBLOCK_ID',
            'NAME',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
        ];
    }
}
