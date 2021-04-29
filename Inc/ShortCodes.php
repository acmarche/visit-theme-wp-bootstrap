<?php

namespace VisitMarche\Theme\Inc;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use VisitMarche\Theme\Lib\Twig;

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
            return '<p>Nom de fichier manquant syntax  = [gpx_viewer file=VTTBleu]</p>';
        }

        $args = array(
            'post_type' => 'attachment',
            'name' => trim($file),
        );

        $attachments = get_posts($args);
        if (!$attachments || count($attachments) === 0) {
            return '<p>Gpx  non trouvé : '.$file.'</p>';
        }

        $attachment = $attachments[0];
        $link = $attachment->guid;

        $twig = Twig::LoadTwig();
        $post = get_post();
        $title = $post ? $post->post_title : '';

        return $twig->render(
            'map/_gpx_viewer.html.twig',
            [
                'title' => $title,
                'latitude' => 50.2268,
                'longitude' => 5.3442,
                'file' => $link,
            ]
        );
    }
}
