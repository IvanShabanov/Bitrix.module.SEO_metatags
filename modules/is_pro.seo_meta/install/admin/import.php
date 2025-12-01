<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

\Bitrix\Main\Loader::includeModule('is_pro.seo_meta');

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($request->getpost('import') != '') {
	$files       = $request->getFile("IMPORT_CSV");
	$charset     = $request->getpost('charsetfile');
	$SEOmetatags = new \IS_PRO\SEO_metatags\MainClass();
	if ($request->getpost('clearall') == 'Y') {
		$SEOmetatags->clearAllMeta();
		$message = new \CAdminMessage(array(
			'MESSAGE' => Loc::getMessage('ISPRO_SEO_METATAGS_CLEARED'),
			'TYPE'    => 'OK'
		));
		echo $message->Show();
	}
	;

	if (!empty($files)) {
		$tmp_name = $files["tmp_name"];
		$filename = __DIR__ . '/seo_metagats_import.csv';
		$isloaded = true;
		if (!move_uploaded_file($tmp_name, $filename)) {
			if (!copy($tmp_name, $filename)) {
				$isloaded = false;
			}
		}
		if ($isloaded) {
			$importData = @file($filename);
			$fileError  = false;
			if (is_array($importData)) {
				foreach ($importData as $key => $line) {
					if ($key == 0) {
						$arKeys = explode(';', trim($line));
						if (is_array($arKeys)) {
							foreach ($arKeys as &$strKey) {
								$strKey = trim($strKey, '"');
							}
						} else {
							$fileError = true;
							break;
						}
					} else {
						$arValue = explode(';', trim($line));
						if (is_array($arValue)) {
							$arFields = [];
							foreach ($arValue as $key => $strVal) {
								if ($arKeys[$key] != 'ID') {
									$strVal = trim($strVal, '"');
									if ($charset != 'utf-8') {
										$strVal = mb_convert_encoding($strVal, "utf-8", $charset);
									}
									$arFields[$arKeys[$key]] = $strVal;
								}
								;
							}
							;
							$SEOmetatags->saveMeta($arFields);
						} else {
							$fileError = true;
							break;
						}
					}
				}
			} else {
				$fileError = true;
			}
			if ($fileError) {
				$message = new \CAdminMessage(array(
					'MESSAGE' => Loc::getMessage('ISPRO_SEO_METATAGS_IMPORT_ERROR_FILE'),
					'TYPE'    => 'ERROR'
				));
				echo $message->Show();
			} else {
				$message = new \CAdminMessage(array(
					'MESSAGE' => Loc::getMessage('ISPRO_SEO_METATAGS_IMPORTED'),
					'TYPE'    => 'OK'
				));
				echo $message->Show();
			}
			@unlink($filename);
		} else {
			$message = new \CAdminMessage(array(
				'MESSAGE' => Loc::getMessage('ISPRO_SEO_METATAGS_IMPORT_ERROR'),
				'TYPE'    => 'ERROR'
			));
			echo $message->Show();
		}
	}
}
