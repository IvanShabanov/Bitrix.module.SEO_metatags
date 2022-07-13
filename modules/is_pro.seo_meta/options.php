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

$options_list = [
    'USE_FULL_URL' => 'checkbox'
];

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

foreach ($options_list as $option_name => $option_type) {
    if ($request->getpost('saveoptions') != '') {
        \Bitrix\Main\Config\Option::set($arModuleCfg['MODULE_ID'], $option_name, $request->getpost('option_'.$option_name));
        $message = new \CAdminMessage(array(
            'MESSAGE' => 'SAVED: '.Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_OPTION_'.$option_name),
            'TYPE' => 'OK'
            ));
        echo $message->Show();
    };
    $option[$option_name] = \Bitrix\Main\Config\Option::get($arModuleCfg['MODULE_ID'], $option_name);
}

$doc_root = \Bitrix\Main\Application::getDocumentRoot();
$url_cur = str_replace($doc_root, '', __DIR__);

if ($request->getpost('import') != '') {
    include(__DIR__.'/install/admin/import.php');
}



$tabList = array(
    array(
		'DIV' => 'edit1',
		'TAB' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_SET_1'),
		'ICON' => 'ib_settings',
		'TITLE' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_TITLE_SET_1')
	),

	array(
		'DIV' => 'edit_settings',
		'TAB' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_SET_SETTINGS'),
		'ICON' => 'ib_settings',
		'TITLE' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_TITLE_SET_SETTINGS')
    ),

	array(
		'DIV' => 'edit_export_import',
		'TAB' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_SET_EXPORT_IMPORT'),
		'ICON' => 'ib_settings',
		'TITLE' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_TITLE_SET_EXPORT_IMPORT')
	)
);


$tabControl = new CAdminTabControl(str_replace('.', '_', $arModuleCfg['MODULE_ID']) . '_options', $tabList);
?>
<form method="POST" action="<?= $currentUrl; ?>"  enctype="multipart/form-data">
    <?= bitrix_sessid_post(); ?>
<?
$tabControl->Begin();
?>

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
    <?foreach ($options_list as $option_name => $option_type) :?>
    <tr>
        <td>
            <?=Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_OPTION_'.$option_name)?>
        </td>
        <td style="width: 70%">
        <?if ($option_type == 'checkbox') :?>
            <input name="option_<?=$option_name?>" type="checkbox" value="Y"
            <?=($option[$option_name]=='Y')?'checked="checked"':''?>
            >
        <?endif?>
        </td>
    </tr>
    <?endforeach?>
    <tr>
        <td>
        </td>
        <td>
            <input type="submit" class="adm-btn-save" name="saveoptions" value="<? echo Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_BTN_SAVE'); ?>">
        </td>
    </tr>

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
        <td>
            <span><?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_IMPORT'); ?></span>
        </td>
        <td style="width: 70%">
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
        <td>
            <?echo Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_FILE_CHARSET')?>
        </td>
        <td>
            <select name="charsetfile">
                <option value="windows-1251">windows-1251</option>
                <option value="utf-8">utf-8</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <span><?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_CLEAR'); ?></span>
        </td>
        <td>
            <input type="checkbox" name="clearall" value="Y" />
        </td>
    </tr>
    <tr>
        <td>
        </td>
        <td>
            <input type="submit" class="adm-btn-save" name="import" value="<? echo Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_BTN_SAVE'); ?>">
        </td>
    </tr>

    <?$tabControl->Buttons();?>
<?$tabControl->End();?>
</form>