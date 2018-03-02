<?

namespace Custom\Main;

const BASE_DIR = __DIR__;

$event = new \Bitrix\Main\Event('custom.main', 'onModuleInclude');
$event->send();
