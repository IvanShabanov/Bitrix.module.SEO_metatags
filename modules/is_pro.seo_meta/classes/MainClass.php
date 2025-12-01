<?php

namespace IS_PRO\SEO_metatags;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (class_exists('IS_PRO\SEO_metatags\MainClass')) {
    return;
}

class MainClass
{
    const NAME       = 'SEOmetatags';
    const TABLE_NAME = 'seo_metatags';
    var $strEntityDataClass;

    public function __construct($create = false)
    {
        $this->init();
    }

    function init()
    {
        global $APPLICATION;
        if (!Loader::IncludeModule('highloadblock')) {
            return false;
        }

        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => self::NAME]
        ])->fetch();
        if (!$hlblock) {
            if (!$this->CreateHL()) {
                $APPLICATION->throwException(Loc::getMessage('ISPRO_SEO_METATAGS_NOT_EXIST_HLBL'));
            }
        } else {
            $this->arHLBlock = $hlblock['ID'];
            if ($this->arHLBlock) {
                $obEntity                 = HL\HighloadBlockTable::compileEntity($this->arHLBlock);
                $this->strEntityDataClass = $obEntity->getDataClass();
            }
        }
    }

    public function CreateHL()
    {
        $id = false;

        $arLangs = [
            'ru' => 'SEO метатеги',
            'en' => 'SEO metatags'
        ];

        //создание HL-блока
        $result = HL\HighloadBlockTable::add([
            'NAME'       => self::NAME,
            'TABLE_NAME' => self::TABLE_NAME,
        ]);
        if ($result->isSuccess()) {
            $id = $result->getId();
            foreach ($arLangs as $lang_key => $lang_val) {
                HL\HighloadBlockLangTable::add([
                    'ID'   => $id,
                    'LID'  => $lang_key,
                    'NAME' => $lang_val
                ]);
            }
            $UFObject = 'HLBLOCK_' . $id;

            $arMyFields = [
                'UF_URL'         => [
                    'ENTITY_ID'         => $UFObject,
                    'FIELD_NAME'        => 'UF_URL',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'Y',
                    "EDIT_FORM_LABEL"   => ['ru' => 'URL', 'en' => 'URL'],
                    "LIST_COLUMN_LABEL" => ['ru' => 'URL', 'en' => 'URL'],
                    "LIST_FILTER_LABEL" => ['ru' => 'URL', 'en' => 'URL'],
                    "ERROR_MESSAGE"     => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE"      => ['ru' => '', 'en' => ''],
                ],
                'UF_TITLE'       => [
                    'ENTITY_ID'         => $UFObject,
                    'FIELD_NAME'        => 'UF_TITLE',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    "EDIT_FORM_LABEL"   => ['ru' => 'TITLE', 'en' => 'TITLE'],
                    "LIST_COLUMN_LABEL" => ['ru' => 'TITLE', 'en' => 'TITLE'],
                    "LIST_FILTER_LABEL" => ['ru' => 'TITLE', 'en' => 'TITLE'],
                    "ERROR_MESSAGE"     => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE"      => ['ru' => '', 'en' => ''],
                ],
                'UF_DESCRIPTION' => [
                    'ENTITY_ID'         => $UFObject,
                    'FIELD_NAME'        => 'UF_DESCRIPTION',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    "EDIT_FORM_LABEL"   => ['ru' => 'DESCRIPTION', 'en' => 'DESCRIPTION'],
                    "LIST_COLUMN_LABEL" => ['ru' => 'DESCRIPTION', 'en' => 'DESCRIPTION'],
                    "LIST_FILTER_LABEL" => ['ru' => 'DESCRIPTION', 'en' => 'DESCRIPTION'],
                    "ERROR_MESSAGE"     => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE"      => ['ru' => '', 'en' => ''],
                ],
                'UF_KEYWORDS'    => [
                    'ENTITY_ID'         => $UFObject,
                    'FIELD_NAME'        => 'UF_KEYWORDS',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    "EDIT_FORM_LABEL"   => ['ru' => 'KEYWORDS', 'en' => 'KEYWORDS'],
                    "LIST_COLUMN_LABEL" => ['ru' => 'KEYWORDS', 'en' => 'KEYWORDS'],
                    "LIST_FILTER_LABEL" => ['ru' => 'KEYWORDS', 'en' => 'KEYWORDS'],
                    "ERROR_MESSAGE"     => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE"      => ['ru' => '', 'en' => ''],
                ],
                'UF_H1'          => [
                    'ENTITY_ID'         => $UFObject,
                    'FIELD_NAME'        => 'UF_H1',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    "EDIT_FORM_LABEL"   => ['ru' => 'H1', 'en' => 'H1'],
                    "LIST_COLUMN_LABEL" => ['ru' => 'H1', 'en' => 'H1'],
                    "LIST_FILTER_LABEL" => ['ru' => 'H1', 'en' => 'H1'],
                    "ERROR_MESSAGE"     => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE"      => ['ru' => '', 'en' => ''],
                ],
                'UF_CANONICAL'   => [
                    'ENTITY_ID'         => $UFObject,
                    'FIELD_NAME'        => 'UF_CANONICAL',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    "EDIT_FORM_LABEL"   => ['ru' => 'CANONICAL', 'en' => 'CANONICAL'],
                    "LIST_COLUMN_LABEL" => ['ru' => 'CANONICAL', 'en' => 'CANONICAL'],
                    "LIST_FILTER_LABEL" => ['ru' => 'CANONICAL', 'en' => 'CANONICAL'],
                    "ERROR_MESSAGE"     => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE"      => ['ru' => '', 'en' => ''],
                ],
                'UF_ROBOTS'      => [
                    'ENTITY_ID'         => $UFObject,
                    'FIELD_NAME'        => 'UF_ROBOTS',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    "EDIT_FORM_LABEL"   => ['ru' => 'ROBOTS', 'en' => 'ROBOTS'],
                    "LIST_COLUMN_LABEL" => ['ru' => 'ROBOTS', 'en' => 'ROBOTS'],
                    "LIST_FILTER_LABEL" => ['ru' => 'ROBOTS', 'en' => 'ROBOTS'],
                    "ERROR_MESSAGE"     => ['ru' => '', 'en' => ''],
                    "HELP_MESSAGE"      => ['ru' => '', 'en' => ''],
                ]
            ];

            foreach ($arMyFields as $arMyField) {
                $obUserField = new \CUserTypeEntity;
                $obUserField->Add($arMyField);
                unset($obUserField);
            }
        } else {
            $errors = $result->getErrorMessages();
            $id     = false;
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
        $this->init();
        if (empty($this->strEntityDataClass)) {
            return false;
        }
        $arQuery['filter'] = ['=UF_URL' => $arFields['UF_URL']];
        $arQuery['select'] = ['ID'];
        $dbData            = $this->strEntityDataClass::getList($arQuery);
        $arMeta            = $dbData->fetch();
        if (!empty($arMeta['ID'])) {
            $result = $this->strEntityDataClass::update($arMeta['ID'], $arFields);
        } else {
            $result = $this->strEntityDataClass::add($arFields);
        }
        return $result;
    }

    public function getMeta(string $url)
    {
        $this->init();
        if (empty($this->strEntityDataClass)) {
            return false;
        }
        if (empty($url)) {
            return false;
        }
        $arQuery           = [];
        $arQuery['filter'] = ['=UF_URL' => $url];
        $dbData            = $this->strEntityDataClass::getList($arQuery);
        $arMeta            = $dbData->fetch();
        return $arMeta;
    }

    public function getAllMeta()
    {
        $this->init();
        if (empty($this->strEntityDataClass)) {
            return false;
        }
        $arItems = [];
        $rsData  = $this->strEntityDataClass::getList([
            'order' => ['ID' => 'ASC'],
        ]);
        while ($arItem = $rsData->Fetch()) {
            $arItems[] = $arItem;
        }
        return $arItems;
    }

    public function clearAllMeta()
    {
        $this->init();
        if (empty($this->strEntityDataClass)) {
            return false;
        }
        $arItems = $this->getAllMeta();
        foreach ($arItems as $arItem) {
            $this->strEntityDataClass::delete($arItem['ID']);
        }
    }
}
