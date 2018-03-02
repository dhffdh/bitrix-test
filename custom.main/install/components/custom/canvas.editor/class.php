<?
use Bitrix\Main,
	Bitrix\Main\Loader,
    Bitrix\Main\Page\Asset,
	Bitrix\Main\Localization\Loc;

use Custom\Main\CanvasTable;


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

class CanvasEditor extends CBitrixComponent
{


	public function onPrepareComponentParams($params)
	{
        $params['ELEMENT_ID'] = (int) $params['ELEMENT_ID'];
		return $params;
	}


	protected function getResult(){

	    if($this->arParams['ELEMENT_ID']>0){
            $arItem = CanvasTable::getById($this->arParams['ELEMENT_ID'])->fetch();
            if(!empty($arItem)){
                $this->arResult['ID'] = $arItem['ID'];
                $this->arResult['IMAGE'] = CFile::GetPath($arItem['IMAGE_ID']);
            }
        }

    }

    public function executeComponent()
    {

        CJSCore::Init(array("jquery"));
        $obAsset = Asset::getInstance();
        $obAsset->addJs($this->__path.'/js/fabric.min.js');

        try
        {
            $this->getResult();
            $this->includeComponentTemplate();

        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }


}