<?php

namespace IS_PRO\SEO_metatags;
use Bitrix\Main\Localization\Loc;
use IS_PRO\SEO_metatags\MainClass;

Loc::loadMessages(__FILE__);

class MainFunctions
{
	public static function setMetatags()
	{
		global $USER;
		global $APPLICATION;
		$SEOmetatags = new MainClass();
		$arModuleCfg = [];
		if (file_exists(__DIR__ . "/../install/module.cfg.php")) {
			include(__DIR__ . "/../install/module.cfg.php");
		}

		$request                = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
		$host                   = $request->getHttpHost();
		$url                    = $request->getRequestUri();
		list($url, $get)        = explode('?', $url);
		$option['USE_FULL_URL'] = \Bitrix\Main\Config\Option::get($arModuleCfg['MODULE_ID'], 'USE_FULL_URL');
		if ($option['USE_FULL_URL'] == 'Y') {
			$url = $host . $url;
		}
		if (!empty($USER) && $USER->IsAdmin()) {
			if ($request->getQuery('save_seo_meta') == 'Y') {
				$arFields = array(
					'UF_URL'         => $url,
					'UF_TITLE'       => $request->getpost('title'),
					'UF_DESCRIPTION' => $request->getpost('description'),
					'UF_KEYWORDS'    => $request->getpost('keywords'),
					'UF_H1'          => $request->getpost('h1'),
					'UF_CANONICAL'   => $request->getpost('canonical'),
					'UF_ROBOTS'      => $request->getpost('robots'),
				);
				$SEOmetatags->saveMeta($arFields);
				$url             = $request->getRequestUri();
				list($url, $get) = explode('?', $url);
				LocalRedirect($url . '?clear_cache=Y');
			}

			$doc_root  = \Bitrix\Main\Application::getDocumentRoot();
			$urlModule = str_replace($doc_root, '', __DIR__ . '/..');
			$APPLICATION->AddPanelButton(
				array(
					"ID"        => "BUTTON_" . $arModuleCfg['MODULE_ID'] . '_ID', //определяет уникальность кнопки
					"TEXT"      => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_TEXT'),
					"TYPE"      => "BIG", //BIG - большая кнопка, иначе маленькая
					"MAIN_SORT" => 10000, //индекс сортировки для групп кнопок
					"SORT"      => 10, //сортировка внутри группы
					"HREF"      => "javascript:(new BX.CDialog({'content_url':'" . $urlModule . "/install/admin/form.php?" .
						"url=" . urlencode($url) . "&" .
						"','width':'','height':'','min_width':'450','min_height':'250'})).Show();BX.removeClass(this.parentNode.parentNode, 'bx-panel-button-icon-active');",

					"ICON"      => "icon-class", //название CSS-класса с иконкой кнопки
					"SRC"       => $urlModule . "/install/images/icon.svg",
					"ALT"       => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_ALT'), //старый вариант
					"HINT"      => array( //тултип кнопки
						"TITLE" => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_HINT_TITLE'),
						"TEXT"  => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_HINT_TEXT'),
					),
				),
				$bReplace = false //заменить существующую кнопку?
			);
		}


		$arMeta = $SEOmetatags->getMeta($url);

		if (trim($arMeta['UF_TITLE']) == '') {
			$arMeta['UF_TITLE'] = $APPLICATION->GetProperty('title');
		}
		if (trim($arMeta['UF_DESCRIPTION']) == '') {
			$arMeta['UF_DESCRIPTION'] = $APPLICATION->GetProperty('description');
		}
		if (trim($arMeta['UF_KEYWORDS']) == '') {
			$arMeta['UF_KEYWORDS'] = $APPLICATION->GetProperty('keywords');
		}
		if (trim($arMeta['UF_H1']) == '') {
			$arMeta['UF_H1'] = $APPLICATION->GetTitle();
		}
		if (trim($arMeta['UF_CANONICAL']) == '') {
			$arMeta['UF_CANONICAL'] = $APPLICATION->GetProperty('canonical');
		}
		if (trim($arMeta['UF_ROBOTS']) == '') {
			$arMeta['UF_ROBOTS'] = $APPLICATION->GetProperty('robots');
		}

		foreach (GetModuleEvents($arModuleCfg['MODULE_ID'], 'OnISProSeoMatatagsSet', true) as $arEvent) {
			ExecuteModuleEventEx($arEvent, [&$arMeta]);
		}

		if (trim($arMeta['UF_TITLE']) != '') {
			$APPLICATION->SetPageProperty('title', $arMeta['UF_TITLE']);
		}
		if (trim($arMeta['UF_DESCRIPTION']) != '') {
			$APPLICATION->SetPageProperty('description', $arMeta['UF_DESCRIPTION']);
		}
		if (trim($arMeta['UF_KEYWORDS']) != '') {
			$APPLICATION->SetPageProperty('keywords', $arMeta['UF_KEYWORDS']);
		}
		if (trim($arMeta['UF_H1']) != '') {
			$APPLICATION->SetTitle($arMeta['UF_H1']);
		}
		if (trim($arMeta['UF_CANONICAL']) != '') {
			$APPLICATION->SetPageProperty('canonical', $arMeta['UF_CANONICAL']);
		}
		if (trim($arMeta['UF_ROBOTS']) != '') {
			$APPLICATION->SetPageProperty('robots', $arMeta['UF_ROBOTS']);
		}
	}
}
