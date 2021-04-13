<?php


namespace VisitMarche\Theme\Lib;


use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocaleHelper
{
    public static function getSelectedLanguage(): string
    {
        if (ICL_LANGUAGE_CODE) {
            return ICL_LANGUAGE_CODE;
        }

        return 'fr';
    }

    public static function iniTranslator(): TranslatorInterface
    {
        $yamlLoader = new YamlFileLoader();

        $translator = new Translator(self::getSelectedLanguage());
        $translator->addLoader('yaml', $yamlLoader);
        $translator->addResource('yaml', get_template_directory().'/translations/messages.fr.yaml', 'fr');
        $translator->addResource('yaml', get_template_directory().'/translations/messages.en.yaml', 'en');
        $translator->addResource('yaml', get_template_directory().'/translations/messages.nl.yaml', 'nl');

        //$xmlLoader = new XliffFileLoader();
        //$translator->addLoader('xml', $xmlLoader);
        //$translator->addResource('xml', get_template_directory().'/translations/messages.fr.xml', 'fr');

        return $translator;
    }

}
