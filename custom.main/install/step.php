<?

use Bitrix\Main\Config\Configuration;
use \Bitrix\Main\Localization\Loc;

if( !check_bitrix_sessid() ) {
	return;
}

$arInstalls = Configuration::getInstance()->get('custom.main');


if ($ex = $APPLICATION->GetException()){
	CAdminMessage::ShowMessage(array(
		"TYPE" => "ERROR",
		"MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));
}
else{
	echo CAdminMessage::ShowNote(Loc::getMessage("CUSTOM_MOD_INST_OK"));
}

?>
<form action="<?echo $APPLICATION->GetCurPage(); ?>">
	<input type="hidden" name="lang" value="<?echo LANGUAGE_ID ?>">
	<input type="submit" name="" value="<?echo Loc::getMessage("MOD_BACK"); ?>">
<form>