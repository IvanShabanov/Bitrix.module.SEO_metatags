<?
$MESS['ISPRO_SEO_METATAGS_OPTIONS_NOTE'] = '
<h3>Помощник установки метатегов на страницы</h3>
<p>Что делает:</p>
<p>Уставвливает на страницу мета теги</p>
    <ol>
        <li>Title</li>
        <li>Keywords</li>
        <li>Description</li>
        <li>H1</li>
        <li>canonical</li>
        <li>robots</li>
    </ol>

<p>В администратовной панели <br>
    <img src="#MODULE_PATH#/install/images/admin_panel.jpg">
</p>
<p>После выставления модулем метатегов их можно обработать по событию</p>
<pre style="padding: 15px; background: #fff;">
// скрипт в файле /bitrix/php_interface/init.php
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
';

$MESS['ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_SET'] = 'SEO Metatags';
$MESS['ISPRO_SEO_METATAGS_OPTIONS_MAIN_TAB_TITLE_SET'] = '';