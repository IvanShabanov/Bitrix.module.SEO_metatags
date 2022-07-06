<?

namespace IS_PRO\SEO_metatags;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (class_exists('\IS_PRO\SEO_metatags\Main')) {
    return;
}
class Main
{
    const NAME = 'SEOmetatags';
    const TABLE_NAME = 'seo_metatags';


    public function setMetatags()
    {
        global $USER;
        global $APPLICATION;
        Loader::IncludeModule('highloadblock');
        $arHLBlock = self::getHLblock();
        if ($arHLBlock) {
            $obEntity = HL\HighloadBlockTable::compileEntity($arHLBlock);
            $strEntityDataClass = $obEntity->getDataClass();
            $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
            $url = $request->getRequestUri();
            list($url, $get) = explode('?', $url);

            $arQuery = array();
            $arQuery['filter'] = array('=UF_URL' => $url);
            $dbData = $strEntityDataClass::getList($arQuery);
            $arMeta = $dbData->fetch();
            if ($USER->IsAdmin()) {
                if ($get == 'save_seo_meta=Y') {
                    $arFields = array(
                        'UF_URL' => $url,
                        'UF_TITLE' => $request->getpost('title'),
                        'UF_DESCRIPTION' => $request->getpost('description'),
                        'UF_KEYWORDS' => $request->getpost('keywords'),
                        'UF_H1' => $request->getpost('h1'),
                    );
                    if ($arMeta['ID'] != '') {
                        $result = $strEntityDataClass::update($arMeta['ID'], $arFields);
                    } else {
                        $result = $strEntityDataClass::add($arFields);
                    }
                    LocalRedirect($url . '?clear_cache=Y');
                }
            }
            if (trim($arMeta['UF_TITLE']) != '') {
                $APPLICATION->SetPageProperty('title', $arMeta['UF_TITLE']);
            };
            if (trim($arMeta['UF_DESCRIPTION']) != '') {
                $APPLICATION->SetPageProperty(description, $arMeta['UF_DESCRIPTION']);
            };
            if (trim($arMeta['UF_KEYWORDS']) != '') {
                $APPLICATION->SetPageProperty(keywords, $arMeta['UF_KEYWORDS']);
            };
            if (trim($arMeta['UF_H1']) != '') {
                $APPLICATION->SetTitle($arMeta['UF_H1']);
            };
            if ($USER->IsAdmin()) {
                $doc_root = \Bitrix\Main\Application::getDocumentRoot();
                $url_cur = str_replace($doc_root, '', __DIR__);
                $APPLICATION->AddPanelButton(
                    array(
                        "ID" => "BUTTON_" . self::NAME . '_ID', //определяет уникальность кнопки
                        "TEXT" => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_TEXT'),
                        "TYPE" => "", //BIG - большая кнопка, иначе маленькая
                        "MAIN_SORT" => 10000, //индекс сортировки для групп кнопок
                        "SORT" => 10, //сортировка внутри группы
                        "HREF" => "javascript:(new BX.CDialog({'content_url':'".$url_cur."/install/form.php?".
                            "UF_TITLE=".$arMeta['UF_TITLE']."&amp;".
                            "UF_DESCRIPTION=".$arMeta['UF_DESCRIPTION']."&amp;".
                            "UF_KEYWORDS=".$arMeta['UF_KEYWORDS']."&amp;".
                            "UF_H1=".$arMeta['UF_H1']."&amp;".
                            "','width':'','height':'','min_width':'450','min_height':'250'})).Show();BX.removeClass(this.parentNode.parentNode, 'bx-panel-button-icon-active');",

                        //"HREF" => $url_cur . "/install/form.php", //или javascript:MyJSFunction())

                        /*
                        (new BX.CDialog({'content_url':'/bitrix/admin/public_folder_edit.php?lang=ru&amp;site=s1&amp;path=%2F&amp;back_url=%2F&amp;siteTemplateId=s1_main_sphinx_sergeland','width':'','height':'','min_width':'450','min_height':'250'})).Show();BX.removeClass(this.parentNode.parentNode, 'bx-panel-button-icon-active');
                        */
                        "ICON" => "icon-class", //название CSS-класса с иконкой кнопки
                        "SRC" => $url_cur . "/install/images/icon.png",
                        "ALT" => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_ALT'), //старый вариант
                        "HINT" => array( //тултип кнопки
                            "TITLE" => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_HINT_TITLE'),
                            "TEXT" => Loc::getMessage('ISPRO_SEO_METATAGS_PANEL_BUTTON_HINT_TEXT'),
                        ),
                    ),
                    $bReplace = false //заменить существующую кнопку?
                );
            }
        }
    }

    public function getHLblock($create = false)
    {
        global $APPLICATION;
        Loader::IncludeModule('highloadblock');
        $result = false;
        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => self::NAME]
        ])->fetch();
        if (!$hlblock) {
            if ($create) {
                $this->CreateHL();
            } else {
                $APPLICATION->throwException(Loc::getMessage('ISPRO_SEO_METATAGS_NOT_EXIST_HLBL'));
            }
        } else {
            $result = $hlblock['ID'];
        };
        return $result;
    }

    private function GetEntityDataClass($HlBlockId)
    {
        if (empty($HlBlockId) || $HlBlockId < 1) {
            return false;
        }
        $hlblock = HL\HighloadBlockTable::getById($HlBlockId)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }


    function CreateHL()
    {
        Loader::IncludeModule('highloadblock');
        $id = false;

        $arLangs = array(
            'ru' => 'SEO метатеги',
            'en' => 'SEO metatags'
        );

        //создание HL-блока
        $result = HL\HighloadBlockTable::add(array(
            'NAME' => self::NAME,
            'TABLE_NAME' => self::TABLE_NAME,
        ));
        if ($result->isSuccess()) {
            $id = $result->getId();
            foreach ($arLangs as $lang_key => $lang_val) {
                HL\HighloadBlockLangTable::add(array(
                    'ID' => $id,
                    'LID' => $lang_key,
                    'NAME' => $lang_val
                ));
            }
            $UFObject = 'HLBLOCK_' . $id;

            $arMyFields = array(
                'UF_URL' => array(
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_URL',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'Y',
                    "EDIT_FORM_LABEL" => array('ru' => 'URL', 'en' => 'URL'),
                    "LIST_COLUMN_LABEL" => array('ru' => 'URL', 'en' => 'URL'),
                    "LIST_FILTER_LABEL" => array('ru' => 'URL', 'en' => 'URL'),
                    "ERROR_MESSAGE" => array('ru' => '', 'en' => ''),
                    "HELP_MESSAGE" => array('ru' => '', 'en' => ''),
                ),
                'UF_TITLE' => array(
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_TITLE',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => array('ru' => 'TITLE', 'en' => 'TITLE'),
                    "LIST_COLUMN_LABEL" => array('ru' => 'TITLE', 'en' => 'TITLE'),
                    "LIST_FILTER_LABEL" => array('ru' => 'TITLE', 'en' => 'TITLE'),
                    "ERROR_MESSAGE" => array('ru' => '', 'en' => ''),
                    "HELP_MESSAGE" => array('ru' => '', 'en' => ''),
                ),
                'UF_DESCRIPTION' => array(
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_DESCRIPTION',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => array('ru' => 'DESCRIPTION', 'en' => 'DESCRIPTION'),
                    "LIST_COLUMN_LABEL" => array('ru' => 'DESCRIPTION', 'en' => 'DESCRIPTION'),
                    "LIST_FILTER_LABEL" => array('ru' => 'DESCRIPTION', 'en' => 'DESCRIPTION'),
                    "ERROR_MESSAGE" => array('ru' => '', 'en' => ''),
                    "HELP_MESSAGE" => array('ru' => '', 'en' => ''),
                ),
                'UF_KEYWORDS' => array(
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_DKEYWORDS',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => array('ru' => 'KEYWORDS', 'en' => 'KEYWORDS'),
                    "LIST_COLUMN_LABEL" => array('ru' => 'KEYWORDS', 'en' => 'KEYWORDS'),
                    "LIST_FILTER_LABEL" => array('ru' => 'KEYWORDS', 'en' => 'KEYWORDS'),
                    "ERROR_MESSAGE" => array('ru' => '', 'en' => ''),
                    "HELP_MESSAGE" => array('ru' => '', 'en' => ''),
                ),
                'UF_H1' => array(
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_H1',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => array('ru' => 'H1', 'en' => 'H1'),
                    "LIST_COLUMN_LABEL" => array('ru' => 'H1', 'en' => 'H1'),
                    "LIST_FILTER_LABEL" => array('ru' => 'H1', 'en' => 'H1'),
                    "ERROR_MESSAGE" => array('ru' => '', 'en' => ''),
                    "HELP_MESSAGE" => array('ru' => '', 'en' => ''),
                )
            );

            foreach ($arMyFields as $arMyField) {
                $obUserField  = new \CUserTypeEntity;
                $obUserField->Add($arMyField);
                unset($obUserField);
            }
        } else {
            $errors = $result->getErrorMessages();
            $id = false;
        }
        return $id;
    }
}
