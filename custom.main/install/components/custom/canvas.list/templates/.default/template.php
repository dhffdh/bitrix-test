<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

?>
<div>

    <div>
        <a href="<?=$arResult['ADD_URL'];?>">Add new</a>
    </div>

    <div class="b-canvas-items">
        <?
        foreach ($arResult['ITEMS'] as $arItem){
            ?>
            <div class="b-canvas-item">
                <img src="<?=$arItem['IMAGE'];?>" alt="">
                <div>
                    <a href="<?=$arItem['URL'];?>">Edit</a>
                </div>
            </div>
            <?
        }
        ?>
    </div>

    <?
    $APPLICATION->IncludeComponent(
        "bitrix:main.pagenavigation",
        "",
        array(
            "NAV_OBJECT" => $arResult['NAV'],
            "SEF_MODE" => "N",
        ),
        false
    );
    ?>
</div>