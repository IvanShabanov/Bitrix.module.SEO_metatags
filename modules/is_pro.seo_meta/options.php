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

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$url = $request->getRequestUri();

$list_options = [];

$options = array();
foreach ($list_options as $name) {
    $options[$name] = (string)Main\Config\Option::get($arModuleCfg['MODULE_ID'], $name);
}
/*
$tabList = array(
	array(
		'DIV' => 'edit1',
		'TAB' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_SET'),
		'ICON' => 'ib_settings',
		'TITLE' => Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_TITLE_SET')
	)
);


$tabControl = new CAdminTabControl(str_replace('.', '_', $arModuleCfg['MODULE_ID']) . '_options', $tabList);
*/
?>
<?=BeginNote();?>
    <?= Loc::getMessage('ISPRO_SEO_METATAGS_OPTIONS_NOTE', array('#MODULE_PATH#'=>$url_cur)); ?>
<?=EndNote();?>
<?/*
$tabControl->Begin();
?>
<form method="POST" action="<?= $currentUrl; ?>">
    <?= bitrix_sessid_post(); ?>

    <?
    $tabControl->BeginNextTab();
    ?>

    <tr class="">
        <td colspan="2">

        </td>
    </tr>

    <?$tabControl->Buttons();?>
    <input type="submit" class="adm-btn-save" name="update" value="<? echo Loc::getMessage('ASD_IBLOCK_OPTIONS_BTN_SAVE'); ?>">
    <input type="hidden" name="update" value="Y">
</form>
<?$tabControl->End();
*/
?>