<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
global $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($request->getpost('import') == 'Y') {
    include(__DIR__.'/../../classes/main.class.php');
    $SEOmetatags = new \IS_PRO\SEO_metatags\MainClass();
    if ($request->getpost('clearall') == 'Y') {
        $SEOmetatags->clearAllMeta();
        echo CAdminMessage::ShowMessage(Loc::getMessage('ISPRO_SEO_METATAGS_CLEARED'));
    };

    if (!empty($_FILES["IMPORT_CSV"])) {
        $tmp_name = $_FILES["IMPORT_CSV"]["tmp_name"];
        $filename = __DIR__.'/seo_metagats_import.csv';
        $isloaded = true;
        if (!move_uploaded_file($tmp_name, $filename)) {
            if (!copy($tmp_name, $filename)) {
                $isloaded = false;
            }
        }
        if ($isloaded) {
            $importData = @file($filename);
            if (is_array($importData)) {
                foreach ($importData as $key=>$line) {
                    if ($key == 0) {
                        $arKeys = explode(';', $line);
                        foreach ($arKeys as &$strKey) {
                            $strKey = trim($strKey, '"');
                        }
                    } else {
                        $arValue = explode(';', $line);
                        $arFields = [];
                        foreach ($arValue as $key=>$strVal) {
                            $strVal = trim($strVal, '"');
                            $strVal = mb_convert_encoding($strVal, "utf-8", "windows-1251");
                            if ($arKeys[$key] != 'ID') {
                                $arFields[$arKeys[$key]] = $strVal;
                            };
                        };
                        $SEOmetatags->saveMeta($arFields);
                    }
                }
            }
        }
    }
}
