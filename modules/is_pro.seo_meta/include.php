<?php

if (file_exists(__DIR__ . "/install/module.cfg.php")) {
	include(__DIR__ . "/install/module.cfg.php");
}

$arClasses = [
	/* Библиотеки и классы для авто загрузки */
	'IS_PRO\SEO_metatags\MainClass'	  => 'classes/MainClass.php',
	'IS_PRO\SEO_metatags\MainFunctions' => 'classes/MainFunctions.php',
];

\Bitrix\Main\Loader::registerAutoLoadClasses($arModuleCfg['MODULE_ID'], $arClasses);

if (class_exists('IS_PRO\SEO_metatags\MainFunctions')) {
    file_put_contents(__DIR__ . '/log.log', 'IS_PRO\SEO_metatags\MainFunctions class' . "\n", FILE_APPEND);
} else {
    file_put_contents(__DIR__ . '/log.log', '----' . "\n", FILE_APPEND);
}