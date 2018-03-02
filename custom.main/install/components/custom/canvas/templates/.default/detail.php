<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>

<?$APPLICATION->IncludeComponent(
    "custom:canvas.editor","",
    array(
        'ELEMENT_ID' => $arResult["VARIABLES"]["ID"]
    ),
    Array()
);?>

<p><a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["list"]?>">back</a></p>
