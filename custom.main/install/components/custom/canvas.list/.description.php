<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CANVAS_LIST_NAME"),
	"DESCRIPTION" => GetMessage("CANVAS_LIST_DESCRIPTION"),
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"SORT" => 20,
	"PATH" => array(
		"ID" => "custom",
        "NAME" => GetMessage("CUSTOM_NAMESPACE"),
        "SORT" => 90
	),
);

?>