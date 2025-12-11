<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class is_pro_seo_meta extends CModule
{

	public function __construct()
	{
		$arModuleVersion = [];
		$arModuleCfg     = [];
		if (file_exists(__DIR__ . "/version.php")) {
			include(__DIR__ . "/version.php");
		}
		;
		if (file_exists(__DIR__ . "/module.cfg.php")) {
			include(__DIR__ . "/module.cfg.php");
		}
		;
		$this->MODULE_ID           = $arModuleCfg['MODULE_ID'];
		$this->MODULE_VERSION      = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME         = Loc::getMessage("ISPRO_SEO_META_NAME");
		$this->MODULE_DESCRIPTION  = Loc::getMessage("ISPRO_SEO_META_DESC");
		$this->PARTNER_NAME        = Loc::getMessage("ISPRO_SEO_PARTNER_NAME");
		$this->PARTNER_URI         = Loc::getMessage("ISPRO_SEO_PARTNER_URI");
	}


	public function DoInstall()
	{
		global $DB, $APPLICATION, $step;
		$this->InstallHlBl();
		$this->InstallEvents();
		ModuleManager::registerModule($this->MODULE_ID);
		return true;
	}

	public function DoUninstall()
	{
		global $DB, $APPLICATION, $step;
		$this->UnInstallHlBl();
		$this->UnInstallEvents();
		ModuleManager::unRegisterModule($this->MODULE_ID);
		return true;
	}

	public function InstallHlBl()
	{
		//$obSeoMetatags = new \IS_PRO\SEO_metatags\MainClass(true);
		if ($obSeoMetatags) {
			return true;
		} else {
			return false;
		}
	}

	public function UnInstallHlBl()
	{
		$obSeoMetatags = new \IS_PRO\SEO_metatags\MainClass;
		if ($obSeoMetatags) {
			$obSeoMetatags->RemoveHL();
			return true;
		} else {
			return false;
		}
	}

	public function InstallEvents()
	{
		RegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "\IS_PRO\SEO_metatags\MainFunctions", "setMetatags");
		return false;
	}

	public function UnInstallEvents()
	{
		UnRegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "\IS_PRO\SEO_metatags\MainFunctions", "setMetatags");
		return false;
	}
}
