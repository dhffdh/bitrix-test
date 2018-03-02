<?
use Bitrix\Main,
	Bitrix\Main\Loader,
	Bitrix\Main\Application,
    Bitrix\Main\Page\Asset,
    \Bitrix\Main\UI\PageNavigation,
	Bitrix\Main\Localization\Loc;

use Custom\Main\CanvasTable;


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);



class Canvas extends CBitrixComponent
{

	public function onPrepareComponentParams($params)
	{
		return $params;
	}



    public function executeComponent()
    {
        if(!Loader::includeModule('custom.main')){
            ShowError("Module custom.main not installed");
            return;
        }

        global $APPLICATION;


        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(array('ID' => 'ID'), array());
        CComponentEngine::initComponentVariables(false, array("ID","NEW"), $arVariableAliases, $arVariables);

        $componentPage = "";

        if(isset($arVariables["ID"]) && intval($arVariables["ID"]) > 0)
            $componentPage = "detail";
        elseif(isset($arVariables["NEW"]) && $arVariables["NEW"] == 'Y')
            $componentPage = "detail";
        else
            $componentPage = "list";

        $this->arResult['VARIABLES'] = $arVariables;
        $this->arResult['URL_TEMPLATES'] = array(
            "list" => htmlspecialcharsbx($APPLICATION->GetCurPage())
        );

        try
        {
            $this->includeComponentTemplate($componentPage);
        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }


}