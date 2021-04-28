<?php

namespace VisitMarche\Theme\Inc;


use AcMarche\Common\Cache;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use VisitMarche\Theme\Lib\Twig;

class ShortCodes
{
    /**
     * @var \Symfony\Contracts\Cache\CacheInterface
     */
    private $cache;
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct()
    {
        add_action('init', [$this, 'registerShortcodes']);
        $this->cache = Cache::instance();
    }

    function registerShortcodes()
    {
        add_shortcode('gpx_viewer', [new ShortCodes(), 'gpxViewer']);
    }

    public function gpxViewer($args): string
    {
        add_action('wp_enqueue_scripts', [$this, 'visitmarcheLeaft']);
        $file = $args['file'] ?? null;
        if (!$file) {
            return 'Nom de fichier manquant';
        }
        $twig = Twig::LoadTwig();
        $post = get_post();
        $title = $post ? $post->post_title : '';
        $file = '/wp-content/themes/visitmarche/assets/elevation/VTTBleu.gpx';

        $t = $twig->render(
            'map/_gpw_viewer.html.twig',
            [
                'title' => '456',
                'latitude' => 50,
                'longitude' => 5.2,
                'file' => $file,
            ]
        );

        //     $t = preg_replace("#\n#", "", $t);//bug avec raw de twig

        return $t;
    }


}
