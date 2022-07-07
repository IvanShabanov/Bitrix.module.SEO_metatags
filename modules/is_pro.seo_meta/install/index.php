<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
Loc::loadMessages(__FILE__);

Class is_pro_seo_meta extends CModule
{
    public function __construct()
    {
        if(file_exists(__DIR__."/version.php")){
            $arModuleVersion = array();
            include(__DIR__."/version.php");
            $this->MODULE_ID 		   = 'is_pro.seo_meta';
            $this->MODULE_VERSION  	   = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME 		   = Loc::getMessage("ISPRO_SEO_META_NAME");
            $this->MODULE_DESCRIPTION  = Loc::getMessage("ISPRO_SEO_META_DESC");
            $this->PARTNER_NAME 	   = Loc::getMessage("ISPRO_SEO_PARTNER_NAME");
            $this->PARTNER_URI  	   = Loc::getMessage("ISPRO_SEO_PARTNER_URI");
        }
        return false;
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
        include(__DIR__ . '/../include.php');
        $obSeoMetatags = new \IS_PRO\SEO_metatags\Main;
        $obSeoMetatags->getHLblock(true);
        return true;
    }

    public function UnInstallHlBl()
    {
        include(__DIR__ . '/../include.php');
        $obSeoMetatags = new \IS_PRO\SEO_metatags\Main;
        $obSeoMetatags->RemoveHL();
        return true;
    }

    public function InstallEvents()
    {
        RegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "IS_PRO\SEO_metatags\Main", "setMetatags");
        return false;
    }

    public function UnInstallEvents()
    {
        UnRegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "IS_PRO\SEO_metatags\Main", "setMetatags");
        return false;
    }

}
