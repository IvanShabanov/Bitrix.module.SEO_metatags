<?

namespace IS_PRO\SEO_metatags;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (class_exists('\IS_PRO\SEO_metatags\MainClass')) {
    return;
}

class MainClass
{
    const NAME = 'SEOmetatags';
    const TABLE_NAME = 'seo_metatags';

    public function __construct($create = false)
    {
        global $APPLICATION;
        if (!Loader::IncludeModule('highloadblock')) {
            return false;
        };


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
            $this->arHLBlock = $hlblock['ID'];
            if ($this->arHLBlock) {
                $obEntity = HL\HighloadBlockTable::compileEntity($this->arHLBlock);
                $this->strEntityDataClass = $obEntity->getDataClass();
            }
        };
    }

    public function CreateHL()
    {
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
                    'FIELD_NAME' => 'UF_KEYWORDS',
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
                ),
                'UF_CANONICAL' => array(
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_CANONICAL',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => array('ru' => 'CANONICAL', 'en' => 'CANONICAL'),
                    "LIST_COLUMN_LABEL" => array('ru' => 'CANONICAL', 'en' => 'CANONICAL'),
                    "LIST_FILTER_LABEL" => array('ru' => 'CANONICAL', 'en' => 'CANONICAL'),
                    "ERROR_MESSAGE" => array('ru' => '', 'en' => ''),
                    "HELP_MESSAGE" => array('ru' => '', 'en' => ''),
                ),
                'UF_ROBOTS' => array(
                    'ENTITY_ID' => $UFObject,
                    'FIELD_NAME' => 'UF_ROBOTS',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'N',
                    "EDIT_FORM_LABEL" => array('ru' => 'ROBOTS', 'en' => 'ROBOTS'),
                    "LIST_COLUMN_LABEL" => array('ru' => 'ROBOTS', 'en' => 'ROBOTS'),
                    "LIST_FILTER_LABEL" => array('ru' => 'ROBOTS', 'en' => 'ROBOTS'),
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

    public function RemoveHL()
    {
        if ($this->arHLBlock) {
            HL\HighloadBlockTable::delete($this->arHLBlock);
        }
    }

    public function saveMeta($arFields)
    {
        $arQuery['filter'] = array('=UF_URL' => $arFields['UF_URL']);
        $dbData = $this->strEntityDataClass::getList($arQuery);
        $arMeta = $dbData->fetch();
        if ($arMeta['ID'] != '') {
            $result = $this->strEntityDataClass::update($arMeta['ID'], $arFields);
        } else {
            $result = $this->strEntityDataClass::add($arFields);
        }
    }

    public function getMeta($url)
    {
        $arQuery = array();
        $arQuery['filter'] = array('=UF_URL' => $url);
        $dbData = $this->strEntityDataClass::getList($arQuery);
        $arMeta = $dbData->fetch();
        return $arMeta;
    }
}
