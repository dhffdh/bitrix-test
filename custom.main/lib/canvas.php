<?php
namespace Custom\Main;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Context;
use Bitrix\Main\IO\File;
use Bitrix\Main\Application;
use \CFile;

/**
 * ОРМ для изображений
 *
 * Class CanvasTable
 * @package Custom\Main
 */
class CanvasTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_custom_canvas_list';
    }


    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            'TIMESTAMP_X' => new Entity\DatetimeField('TIMESTAMP_X', array(
                'default_value' => new Type\DateTime
            )),
            'IMAGE_ID' => new Entity\IntegerField('IMAGE', array(
                'required' => true
            )),
            'PASS_HASH' => new Entity\StringField('PASS',array(

            ))
        );
    }


    /**
     * Обновление рисунка,с удалением с диска старого
     *
     * @param $id
     * @param $oldImage
     * @param $newImageId
     */
    public static function updateImage($id,$oldImage,$newImageId){

        try{
            self::update($id,array(
                'IMAGE_ID' => $newImageId
            ));
            File::deleteFile($oldImage);
        }catch (\Exception $exception){

        }

    }

    /**
     * Перобразование base64 строки в файл и сохранение в БД файлов
     *
     * @param $base64str
     * @return bool|int|mixed|string
     */
    public static function saveCanvasFile($base64str){

        $fileId = false;
        $docRoot = Application::getInstance()->getDocumentRoot();

        $base64str = str_replace('data:image/png;base64,', '', $base64str);
        $base64str = str_replace(' ', '+', $base64str);
        $fileData = base64_decode($base64str);

        $fileName = $docRoot.'/upload/base64_tmp.png';
        File::putFileContents($fileName, $fileData);

        $arFile = CFile::MakeFileArray($fileName);
        $arFile["MODULE_ID"] = Module::$strModuleId;

        $check = CFile::CheckImageFile($arFile,5*1024*1024,1920,1920);
        if( !strlen($check) ){
            $fileId = CFile::SaveFile($arFile,'canvas');
        }

        File::deleteFile($fileName);

        return $fileId;
    }


    /**
     * Удаление рисунка, а так же его картинки из диска
     *
     * @param $id
     */
    public static function removeItem($id){

        try{

            $arItem = self::getById($id)->fetch();
            if($arItem['IMAGE_ID'])
                File::deleteFile($arItem['IMAGE_ID']);

            CanvasTable::delete($id);

        }catch (\Exception $exception){}


    }
}