<?php

namespace VisitMarche\Theme\Inc;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use VisitMarche\Theme\Lib\Twig;
use WP_Query;

class ShortCodes
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct()
    {
        add_action('init', [$this, 'registerShortcodes']);
    }

    function registerShortcodes()
    {
        add_shortcode('gpx_viewer', [new ShortCodes(), 'gpxViewer']);
    }

    public function gpxViewer($args): string
    {
        $file = $args['file'] ?? null;
        if (!$file) {
            return '<p>Nom de fichier manquant</p>';
        }
        //https://visit.marche.be/wp-content/uploads/2021/04/VTTBleu.gpx
        $args = array(
            // 'posts_per_page' => 1,
            'post_type' => 'attachment',
            'name' => trim($file),
        );

        $attachments = get_posts($args);
        dump($attachments);
        if (!$attachments || !isset($attachments->posts, $attachments->posts[0])) {
            return false;
        }

        dump($attachments[0]);

        $twig = Twig::LoadTwig();
        $post = get_post();
        $title = $post ? $post->post_title : '';
        $file = '/wp-content/themes/visitmarche/assets/elevation/VTTBleu.gpx';

        return $twig->render(
            'map/_gpx_viewer.html.twig',
            [
                'title' => $title,
                'latitude' => 50.2268,
                'longitude' => 5.3442,
                'file' => $file,
            ]
        );
    }


}
