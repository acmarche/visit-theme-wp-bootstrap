<?php


namespace VisitMarche\Theme\Lib;


class LocaleHelper
{
    public static function getSelectedLanguage(): string
    {
        if (ICL_LANGUAGE_CODE) {
            return ICL_LANGUAGE_CODE;
        }

        return 'fr';
    }
}
