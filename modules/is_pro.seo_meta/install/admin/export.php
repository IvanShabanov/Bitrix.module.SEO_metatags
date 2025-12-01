<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

\Bitrix\Main\Loader::includeModule('is_pro.seo_meta');

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $APPLICATION;

$SEOmetatags = new \IS_PRO\SEO_metatags\MainClass();
$arResult    = $SEOmetatags->getAllMeta();
$request     = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$convert     = $request->getQuery('convert');
$host        = $request->getHttpHost();
$APPLICATION->RestartBuffer();

@header('Content-Disposition: attachment; filename="seo_metatags_' . $host . '.csv"');
echo '"' . implode('";"', array_keys($arResult[0])) . '"' . "\n";
foreach ($arResult as $arItem) {
	if (!empty($convert)) {
		$arItem = mb_convert_encoding($arItem, $convert, "utf-8");
	}
	echo '"' . implode('";"', $arItem) . '"' . "\n";
}
die();