<?
use Bitrix\Main,
	Bitrix\Main\Loader,
    Bitrix\Main\Page\Asset,
    \Bitrix\Main\UI\PageNavigation,
	Bitrix\Main\Localization\Loc;

use Custom\Main\CanvasTable;


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

class CanvasList extends CBitrixComponent
{


	public function onPrepareComponentParams($params)
	{

		return $params;
	}


	protected function getResult(){

	    global $APPLICATION;

        $nav = new PageNavigation("canvas");
        $nav->allowAllRecords(true)
            ->setPageSize(6)
            ->initFromUri();


	    try{
            $canvasList = CanvasTable::getList(array(
                'select' => array('ID','IMAGE_ID'),
                'order' => array('ID' => 'ASC'),
                "count_total" => true,
                "offset" => $nav->getOffset(),
                "limit" => $nav->getLimit(),
            ));

            $nav->setRecordCount($canvasList->getCount());

            $this->arResult['ITEMS'] = array();
            while($arItem = $canvasList->fetch())
            {
                $img = CFile::ResizeImageGet( $arItem['IMAGE_ID'], array("width"=>250,"height" => 200) );
                if($img)
                    $arItem['IMAGE'] = $img['src'];

                $arItem['URL'] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?ID=".$arItem['ID']);
                $this->arResult['ITEMS'][] = $arItem;
            }

        }catch (\Exception $exception){

        }

        $this->arResult['ADD_URL'] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?NEW=Y");

        $this->arResult['NAV'] = $nav;


    }

    public function executeComponent()
    {

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