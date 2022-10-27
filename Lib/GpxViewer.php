<?php

namespace VisitMarche\Theme\Lib;

use AcMarche\Pivot\Entities\Specification\Gpx;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class GpxViewer
{
    public function gpxViewer(Gpx $gpx): string
    {
        $twig = Twig::LoadTwig();
        $post = get_post();
        $title = $post ? $post->post_title : '';

        try {
            $this->writeTmpFile($gpx);
        } catch (\Exception $exception) {

        }

        return $twig->render(
            'map/_gpx_viewer.html.twig',
            [
                'title' => $title,
                'latitude' => 50.2268,
                'longitude' => 5.3442,
                // 'file' => 'https://visit.marche.be/output/gpx.xml',
                'file' => $gpx->url,
                'file2' => null,
            ]
        );
    }

    public function writeTmpFile(Gpx $gpx): string
    {
        try {
            $filesystem = new Filesystem();
            $filesystem->dumpFile(sys_get_temp_dir().'/'.'file.gpx', file_get_contents($gpx->url));
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }

        return sys_get_temp_dir().'/'.'file.gpx';
    }
}