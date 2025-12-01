<?php

if (file_exists(__DIR__ . "/install/module.cfg.php")) {
	include(__DIR__ . "/install/module.cfg.php");
}

$arClasses = [
	/* Библиотеки и классы для авто загрузки */
	'IS_PRO\SEO_metatags\MainClass'     => 'classes/MainClass.php',
	'IS_PRO\SEO_metatags\MainFunctions' => 'classes/MainFunctions.php',
];

\Bitrix\Main\Loader::registerAutoLoadClasses($arModuleCfg['MODULE_ID'], $arClasses);