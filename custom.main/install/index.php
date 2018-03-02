<?

use Bitrix\Main\EventManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\InvalidPathException;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;
use Bitrix\Main\Entity\Base;

Loc::loadMessages(__FILE__);

if (class_exists('custom_main')) {
    return;
}


Class custom_main extends CModule
{
    var $exclusionAdminFiles;

    protected $eventHandlers = array();

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");

        $this->MODULE_ID = 'custom.main';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("CUSTOM_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("CUSTOM_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage("CUSTOM_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("CUSTOM_PARTNER_URI");

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = "Y";

        $this->eventHandlers = array(
            array(
                'main',
                'OnPageStart',
                '\Custom\Main\Module',
                'onPageStart',
            ),
        );

    }

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot = false)
    {
        if( $notDocumentRoot ) {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }

    //Проверяем что система поддерживает D7
    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }
    





    /**
     * Установка файлов модуля
     *
     * @param array $arParams
     *
     * @return bool
     * @throws InvalidPathException
     */
    function InstallFiles($arParams = array())
    {
        $componentsPath = $this->GetPath() . "/install/components";
        $docRoot = Application::getInstance()->getDocumentRoot();

        if (Directory::isDirectoryExists($componentsPath)) {
            CopyDirFiles($componentsPath, $docRoot . "/local/components", true, true);
        }

        return true;
    }


    /**
     * Удаление файлов модуля
     *
     * @return bool
     */
    public function UnInstallFiles()
    {

        $docRoot = Application::getInstance()->getDocumentRoot();

        return true;
    }

    public function InstallDB()
    {

        if(Loader::includeModule($this->MODULE_ID)){

            if(!Application::getConnection(\Custom\Main\CanvasTable::getConnectionName())->isTableExists(
                Base::getInstance('\Custom\Main\CanvasTable')->getDBTableName()
            )){
                Base::getInstance('\Custom\Main\CanvasTable')->createDbTable();
            }
        }


        return true;
    }

    public function UnInstallDB()
    {
        global $DB;

        if(Loader::includeModule($this->MODULE_ID)) {
            $DB->Query('DROP TABLE if exists ' . Base::getInstance('\Custom\Main\CanvasTable')->getDBTableName(),true);
        }

        return true;
    }

    /**
     * Установка модуля
     * @throws InvalidPathException
     */
    function DoInstall()
    {
        global $APPLICATION;

        if ($this->isVersionD7()) {
            $this->InstallFiles();
            $this->InstallEvents();

            ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();

        } else {
            $APPLICATION->ThrowException(Loc::getMessage("CUSTOM_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("CUSTOM_INSTALL_TITLE"), $this->GetPath() . "/install/step.php");
    }


    /**
     * Удаление модуля
     */
    function DoUninstall()
    {
        global $APPLICATION;

        $obContext = Application::getInstance()->getContext();
        $arRequest = $obContext->getRequest();

        if ($arRequest["step"] < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("CUSTOM_UNINSTALL_COUNT"), $this->GetPath() . "/install/unstep1.php");
        } elseif ($arRequest["step"] == 2) {
            $this->UnInstallFiles();
            if ($arRequest["savedata"] != "Y") {
                $this->UnInstallDB();
            }
            $this->UnInstallEvents();

            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(Loc::getMessage("CUSTOM_UNINSTALL_COUNT"), $this->GetPath() . "/install/unstep2.php");
        }
    }

    /**
     * Регистрируем обработчики событий
     *
     * @return bool
     */
    public function InstallEvents()
    {

        $eventManager = EventManager::getInstance();

        foreach ($this->eventHandlers as $handler) {

            $eventManager->registerEventHandler(
                $handler[0],
                $handler[1],
                $this->MODULE_ID,
                $handler[2],
                $handler[3]
            );

        }

        return true;
    }



    /**
     * Удаляем обработчики событий
     * @return bool
     */
    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        foreach ($this->eventHandlers as $handler) {
            $eventManager->unRegisterEventHandler(
                $handler[0],
                $handler[1],
                $this->MODULE_ID,
                $handler[2],
                $handler[3]
            );

        }

        return true;
    }

}