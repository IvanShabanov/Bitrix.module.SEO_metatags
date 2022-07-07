<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
include(__DIR__.'/../../classes/main.class.php');
$SEOmetatags = new \IS_PRO\SEO_metatags\MainClass();
$arResult =  $SEOmetatags->getMeta($_REQUEST['url']);
?>
<style>
    .SEO_metatags_form {
        box-sizing: border-box;
    }
    .SEO_metatags_form *{
        box-sizing: border-box;
    }
    .SEO_metatags_form label {
        color: #000;
        display: block;
        margin-top: 10px;
    }
    .SEO_metatags_form input {
        width: 100%;
    }
    .SEO_metatags_form button {
        padding: 10px 20px;
        margin-top: 10px;
    }
</style>
<div class="SEO_metatags_form">
    <?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_TEXT')?>
    <form action="?save_seo_meta=Y" method="post">
        <label><?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_LABEL-TITLE')?></label>
        <input type="text" name="title" value="<?=$arResult['UF_TITLE']?>">

        <label><?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_LABEL-DESCRIPTION')?></label>
        <input type="text" name="description" value="<?=$arResult['UF_DESCRIPTION']?>">

        <label><?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_LABEL-KEYWORDS')?></label>
        <input type="text" name="keywords" value="<?=$arResult['UF_KEYWORDS']?>">

        <label><?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_LABEL-H1')?></label>
        <input type="text" name="h1" value="<?=$arResult['UF_H1']?>">

        <label><?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_LABEL-CANONICAL')?></label>
        <input type="text" name="canonical" value="<?=$arResult['UF_CANONICAL']?>">

        <label><?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_LABEL-ROBOTS')?></label>
        <input type="text" name="robots" value="<?=$arResult['UF_ROBOTS']?>">

        <button type="submit"><?=Loc::getMessage('ISPRO_SEO_METATAGS_FORM_BUTTON_SAVE')?></button>
    </form>
</div>