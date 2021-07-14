<?php


namespace VisitMarche\Theme\Lib;

use AcMarche\Pivot\Entities\OffreInterface;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Twig
{
    public static function LoadTwig(?string $path = null): Environment
    {
        //todo get instance
        if (!$path) {
            $path = get_template_directory().'/templates';
        }

        $loader = new FilesystemLoader($path);

        $environment = new Environment(
            $loader,
            [
                'cache' => ABSPATH.'var/cache',
                'debug' => WP_DEBUG,
                'strict_variables' => WP_DEBUG,
            ]
        );

        // wp_get_environment_type();
        if (WP_DEBUG) {
            $environment->addExtension(new DebugExtension());
        }

        $translator = LocaleHelper::iniTranslator();
        $environment->addExtension(new TranslationExtension($translator));
        $environment->addExtension(new StringExtension());

        $environment->addGlobal('template_directory', get_template_directory_uri());
        $environment->addGlobal('locale', LocaleHelper::getSelectedLanguage());
        $environment->addFilter(self::categoryLink());
        $environment->addFilter(self::translation());
        $environment->addFilter(self::autoLink());
        $environment->addFunction(self::showTemplate());
        $environment->addFunction(self::currentUrl());
        $environment->addFunction(self::isExternalUrl());

        return $environment;
    }

    public static function rendPage(string $templatePath, array $variables = [])
    {
        $twig = self::LoadTwig();
        try {
            echo $twig->render(
                $templatePath,
                $variables,
            );
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo $twig->render(
                'errors/500.html.twig',
                [
                    'message' => $e->getMessage(),
                    'title' => "La page n'a pas pu être chargée",
                    'tags' => [],
                    'relations' => [],
                ]
            );
            $url = Router::getCurrentUrl();
            Mailer::sendError("Error page: ".$templatePath, $url.' \n '.$e->getMessage());
        }

    }

    protected static function categoryLink(): TwigFilter
    {
        return new TwigFilter(
            'category_link',
            function (int $categoryId): ?string {
                return get_category_link($categoryId);
            }
        );
    }

    protected static function translation(): TwigFilter
    {
        return new TwigFilter(
            'translationjf',
            function ($x, OffreInterface $offre, string $property): ?string {
                $selectedLanguage = LocaleHelper::getSelectedLanguage();

                return $offre->$property->languages[$selectedLanguage];
            }
        );
    }

    protected static function showTemplate(): TwigFunction
    {
        return new TwigFunction(
            'showTemplate',
            function (): string {
                if (true === WP_DEBUG) {
                    global $template;

                    return 'template: '.$template;
                }

                return '';
            }
        );
    }

    /**
     * For sharing pages
     * @return TwigFunction
     */
    public static function currentUrl(): TwigFunction
    {
        return new TwigFunction(
            'currentUrl',
            function (): string {
                return Router::getCurrentUrl();
            }
        );
    }

    protected static function isExternalUrl(): TwigFunction
    {
        return new TwigFunction(
            'isExternalUrl',
            function (string $url): bool {
                if (preg_match("#http#", $url)) {
                    if (!preg_match("#https://visitmarche.be#", $url)) {
                        return true;
                    }

                    return false;
                }

                return false;
            }
        );
    }

    private static function autoLink()
    {
        return new TwigFilter(
            'auto_link',
            function (string $text, string $type): string {
                switch ($type) {
                    case 'url':
                        return '<a href="'.$text.'">'.$text.'</a>';
                    case 'mail':
                        return '<a href="mailto:'.$text.'">'.$text.'</a>';
                    case 'tel':
                        return '<a href="tel:'.$text.'">'.$text.'</a>';
                    default:
                        return $text;
                }
            }
        );
    }
}
