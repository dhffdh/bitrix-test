<?
/** @global CMain $APPLICATION */
define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main;
use Bitrix\Main\Context;
use Custom\Main\CanvasTable;

$obReq = Context::getCurrent()->getRequest();
$arReq = $obReq->toArray();

if (isset($arReq['AJAX']) && $arReq['AJAX'] == 'Y')
{

    $action = $arReq['action'];
    $bDelete = $arReq['delete'] == 'Y';
    $arRes = array();

    switch ($action){
        case 'save':
            {
                $img = $obReq->getPost('base64');
                $pass = (string) $obReq->getPost('pass');
                $id = (int) $obReq->getPost('id');


                if(empty($img)){
                    $arRes['ERROR'] = true;
                    $arRes['ERROR_MESSAGE'] = 'Image empty';
                    break;
                }

                if(empty($pass) || strlen($pass) < 5){
                    $arRes['ERROR'] = true;
                    $arRes['ERROR_MESSAGE'] = 'Password consists of at least 5 characters';
                    break;
                }


                if($id>0){

                    $arItem = CanvasTable::getById($id)->fetch();
                    $arRes['$arItem'] = $arItem;

                    if(empty($arItem)){
                        if($arItem['PASS_HASH'] !== md5($pass)){
                            $arRes['ERROR'] = true;
                            $arRes['ERROR_MESSAGE'] = 'Item not found';
                            break;
                        }
                    }

                    if($arItem['PASS_HASH'] !== md5($pass)){
                        $arRes['ERROR'] = true;
                        $arRes['ERROR_MESSAGE'] = 'The password is incorrect';
                        break;
                    }

                    if(!$bDelete){

                        $newFileId = CanvasTable::saveCanvasFile($img);
                        CanvasTable::updateImage($arItem['ID'],$arItem['IMAGE_ID'],$newFileId);

                        $addedId = $arItem['ID'];

                    }else{

                        CanvasTable::removeItem($arItem['ID']);

                        $addedId = false;
                        $arRes['SUCCESS'] = true;
                        $arRes['SUCCESS_MESSAGE'] = 'Picture deleted';

                    }

                }else{

                    $fileId = CanvasTable::saveCanvasFile($img);
                    if($fileId > 0){
                        try{
                            $addedId = CanvasTable::add(array(
                                'IMAGE_ID' => $fileId,
                                'PASS_HASH' => md5($pass),
                            ));
                            $addedId = $addedId->getId();
                        }catch(\Exception $exception){

                        }
                    }
                }


            }
            break;
    }

    if($addedId>0){
        $arRes['SUCCESS'] = true;
        $arRes['SUCCESS_MESSAGE'] = 'Picture successfully saved';
    }


    $arRes['ID'] = $addedId;

    echo json_encode($arRes);

	die();
}