<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
global $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($request->getpost('import') == 'Y') {
    $files = $request->getFile("IMPORT_CSV");
    include(__DIR__.'/../../classes/main.class.php');
    $SEOmetatags = new \IS_PRO\SEO_metatags\MainClass();
    if ($request->getpost('clearall') == 'Y') {
        $SEOmetatags->clearAllMeta();
        $message = new \CAdminMessage(array(
            'MESSAGE' => Loc::getMessage('ISPRO_SEO_METATAGS_CLEARED'),
            'TYPE' => 'OK'
            ));
        echo $message->Show();
    };

    if (!empty($files)) {
        $tmp_name = $files["tmp_name"];
        $filename = __DIR__.'/seo_metagats_import.csv';
        $isloaded = true;
        if (!move_uploaded_file($tmp_name, $filename)) {
            if (!copy($tmp_name, $filename)) {
                $isloaded = false;
                $message = new \CAdminMessage(array(
                    'MESSAGE' => Loc::getMessage('ISPRO_SEO_METATAGS_IMPORT_ERROR'),
                    'TYPE' => 'ERROR'
                    ));
                echo $message->Show();
            }
        }
        if ($isloaded) {
            $importData = @file($filename);
            if (is_array($importData)) {
                foreach ($importData as $key=>$line) {
                    if ($key == 0) {
                        $arKeys = explode(';', trim($line));
                        foreach ($arKeys as &$strKey) {
                            $strKey = trim($strKey, '"');
                        }
                    } else {
                        $arValue = explode(';', trim($line));
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

                $message = new \CAdminMessage(array(
                    'MESSAGE' => Loc::getMessage('ISPRO_SEO_METATAGS_IMPORTED'),
                    'TYPE' => 'OK'
                    ));
                echo $message->Show();
            }
            @unlink($filename);
        }
    }
}
