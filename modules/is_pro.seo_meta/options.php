<?
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

if (!$USER->IsAdmin()) {
    return;
}

if (file_exists(__DIR__ . "/install/module.cfg.php")) {
    include(__DIR__ . "/install/module.cfg.php");
};

if (!Loader::includeModule($arModuleCfg['MODULE_ID'])) {
    return;
}

Loc::loadMessages(__FILE__);

$currentUrl = $APPLICATION->GetCurPage() . '?mid=' . urlencode($mid) . '&amp;lang=' . LANGUAGE_ID;

$doc_root = \Bitrix\Main\Application::getDocumentRoot();
$url_cur = str_replace($doc_root, '', __DIR__);

include(__DIR__.'/install/admin/import.php');

$tabList = array(
    array(
		'DIV' => 'edit1',
		'TAB' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_SET_1'),
		'ICON' => 'ib_settings',
		'TITLE' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_TITLE_SET_1')
	),

	array(
		'DIV' => 'edit2',
		'TAB' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_SET_2'),
		'ICON' => 'ib_settings',
		'TITLE' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_TITLE_SET_2')
	)
);


$tabControl = new CAdminTabControl(str_replace('.', '_', $arModuleCfg['MODULE_ID']) . '_options', $tabList);
?>

<?
$tabControl->Begin();
?>
<form method="POST" action="<?= $currentUrl; ?>"  enctype="multipart/form-data">
    <?= bitrix_sessid_post(); ?>

    <?
    $tabControl->BeginNextTab();
    ?>
    <?=BeginNote();?>
        <?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_NOTE', array('#MODULE_PATH#'=>$url_cur)); ?>
    <?=EndNote();?>

    <?=BeginNote();?>
    <?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_DEVELOPER'); ?>
<pre>
AddEventHandler("is_pro.seo_meta", "OnISProSeoMatatagsSet", Array("MyClass", "OnISProSeoMatatagsSet"));
class MyClass
{
    function OnISProSeoMatatagsSet(&$arFields)
    {
        $arFields["UF_TITLE"] .= " - ООО Ромашка";
        $arFields["UF_DESCRIPTION"] .= " - ООО Ромашка";
        if ($arFields["UF_URL"] == "/test/") {
            $arFields["UF_ROBOTS"] = "noindex, nofollow";
        }
    }
}
</pre>
<?=EndNote();?>


    <?
    $tabControl->BeginNextTab();
    ?>

    <tr class="">
        <td colspan="2">
        <?=BeginNote();?>
            <?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_EXPORT', array('#MODULE_PATH#'=>$url_cur)); ?>
        <?=EndNote();?>
        </td>
    </tr>

    <tr>
        <td  colspan="2">
            <span><?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_IMPORT'); ?></span>
            </td>
    </tr>

    <tr>
        <td  colspan="2">
            <?
            echo CFile::InputFile(
                    "IMPORT_CSV",
                    20,
                    0,
                    '/upload/',
                    0,
                    "csv",
                    "",
                    0,
                    "class=typeinput",
                    "",
                    false,
                    false
                   )
        ?>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="padding: 20px 3px;">
            <input type="checkbox" name="clearall" value="Y" />
            <?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_CLEAR'); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" class="adm-btn-save" name="update" value="<? echo Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_BTN_SAVE'); ?>">
            <input type="hidden" name="import" value="Y">
        </td>
    </tr>

    <?$tabControl->Buttons();?>
</form>
<?$tabControl->End();
?>





