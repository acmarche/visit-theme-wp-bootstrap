<?php

namespace VisitMarche\Theme\Inc;

use VisitMarche\Theme\Lib\Twig;

class ShortCodes
{
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
        $fileName = $args['file'] ?? null;
        $fileName2 = $args['file2'] ?? null;

        if (!$fileName) {
            return '<p>Nom de fichier manquant syntax  = [gpx_viewer file=VTTBleu]</p>';
        }

        try {
            $attachment = $this->getFile($fileName);
            $file = $attachment->guid;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $file2 = null;
        if ($fileName2) {
            try {
                $attachment2 = $this->getFile($fileName2);
                $file2 = $attachment2->guid;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        $twig = Twig::LoadTwig();
        $post = get_post();
        $title = $post ? $post->post_title : '';

        return $twig->render(
            'map/_gpx_viewer.html.twig',
            [
                'title' => $title,
                'latitude' => 50.2268,
                'longitude' => 5.3442,
                'file' => $file,
                'file2' => $file2,
            ]
        );
    }

    /**
     * @param string $fileName
     * @return \WP_Post
     * @throws \Exception
     */
    private function getFile(string $fileName): \WP_Post
    {
        $args = array(
            'post_type' => 'attachment',
            'name' => trim($fileName),
        );

        $attachments = get_posts($args);
        if (!$attachments || count($attachments) === 0) {
            throw new \Exception('<p>Gpx  non trouvé : '.$fileName.'</p>');
        }

        return $attachments[0];
    }
}
