<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CANVAS_EDITOR_NAME"),
	"DESCRIPTION" => GetMessage("CANVAS_EDITOR_DESCRIPTION"),
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "custom",
        "NAME" => GetMessage("CUSTOM_NAMESPACE"),
        "SORT" => 90
	),
);

?>