<?php

namespace AcMarche\Theme;

use AcMarche\Common\Router;
use AcMarche\Pivot\Entities\Offre;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Parser\OffreParser;
use AcMarche\Pivot\Repository\HadesRemoteRepository;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Lib\Twig;

get_header();
$hadesRemoteRepository = new HadesRemoteRepository();
$hadesRepository = new HadesRepository();
$xmlString = $hadesRemoteRepository->getOffres(array_keys(Hades::EVENEMENTS));

$domdoc = $hadesRepository->loadXml($xmlString);
$xpath = new \DOMXPath($domdoc);
$offres = $xpath->query("/root/offres/offre");
$offres = $xpath->query("/root/offres/offre");
$i = 0;
$events = [];
/**
 * @var $offreDom \DOMElement
 */
foreach ($offres as $offreDom) {
    $titles = [];
    $id =$offreDom->getAttribute('id');
    dump($offreDom->nodeName, $id);
    //$titlesd = $xpath->query("/root/offres/offre[$i]/titre", $offreDom);
    $titlesd = $xpath->query("titre", $offreDom);

    foreach ($titlesd as $title) {
        $language = $title->getAttributeNode('lg');
      //  dump($language->nodeValue, $title->nodeValue);
        $titles[] = $title->nodeValue;
    }
    if(count($titles)==0) {
    //    dump($offreDom->nodeName, $i);
    }
    dump($titles);
    $i++;
    $events[$i]['titles'] = $titles;
    $events[$i]['id'] = $id;
   // dump($offreDom);
  //  $datas = $xpath->query("/root/offres/offre/modif_date");
   // dump($datas->item(0)->nodeValue);
}
dump($events);
$url = Router::getCurrentUrl();
Twig::rendPage(
    'errors/404.html.twig',
    [
        'title' => '404',
        'posts' => [],
        'url' => $url,
    ]
);

get_footer();
