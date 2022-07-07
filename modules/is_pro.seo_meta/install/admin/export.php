<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
global $APPLICATION;
include(__DIR__.'/../../classes/main.class.php');
$SEOmetatags = new \IS_PRO\SEO_metatags\MainClass();
$arResult = $SEOmetatags->getAllMeta();

$APPLICATION->RestartBuffer();
@header('Content-Disposition: attachment; filename="seo_metatags.csv"');
echo '"'.implode('";"', array_keys($arResult[0])).'"'."\n";
foreach ($arResult as $arItem) {
    echo '"'.implode('";"', $arItem).'"'."\n";
}
die();
