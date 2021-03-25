<?php


namespace VisitMarche\Theme\Lib;

use AcMarche\Common\Mailer;
use AcMarche\Common\Router;
use AcMarche\Pivot\Entities\OffreInterface;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
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
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $propertyAccessor;

    public function __construct()
    {

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

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

        $translator = self::iniTranslator();
        $environment->addExtension(new TranslationExtension($translator));
        $environment->addExtension(new StringExtension());

        $environment->addGlobal('template_directory', get_template_directory_uri());
        $environment->addFilter(self::categoryLink());
        $environment->addFilter(self::translation());
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

                return $offre->$property->languages['fr'];
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
                    if (!preg_match("#https://new.marche.be#", $url)) {
                        return true;
                    }

                    return false;
                }

                return false;
            }
        );
    }

    private static function iniTranslator(): TranslatorInterface
    {
        $translator = new Translator('fr_FR');
        $yamlLoader = new YamlFileLoader();
        $xmlLoader = new XliffFileLoader();
        $translator->addLoader('yaml', $yamlLoader);
        $translator->addLoader('xml', $xmlLoader);
        $translator->addResource('yaml', get_template_directory().'/translations/messages.fr.yaml', 'fr');
        $translator->addResource('xml', get_template_directory().'/translations/messages.fr.xml', 'fr');

        return $translator;
    }
}
