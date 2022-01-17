<?php

namespace VisitMarche\Theme\Inc;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use VisitMarche\Bottin\Repository\BottinRepository;

class Carto
{
    private HttpClientInterface $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    public function filtres(): array
    {
        return [
            'environnement' => [
                'name' => 'Environnement',
                'icone' => 'fas fa-leaf',
                'elements' => [
                    'bulles_verres' => [
                        'name' => 'Bulles à verres',
                        'source' => 'bottin',
                        'id' => 677,
                    ],
                    'bulles_vetements' => [
                        'name' => 'Bulles à vêtements',
                        'source' => 'bottin',
                        'id' => 678,
                        
                    ],
                ],
            ],
            'mobilite' => [
                'name' => 'Mobilité',
                'icone' => 'fas fa-bus',
                'elements' => [
                    'travaux' => [
                        'name' => 'Travaux',
                        'source' => 'kml',
                        'id' => 'travaux',
                    ],
                    'parking' => [
                        'name' => 'Parking',
                        'source' => 'kml',
                        'id' => 'parkings',
                    ],
                    'pistes_cyclo' => [
                        'name' => 'Pistes cyclables',
                        'source' => 'kml',
                        'id' => 'cyclos',
                    ],
                    'balades' => [
                        'name' => 'Balades',
                        'source' => 'kml',
                        'id' => 'balades',
                    ],
                    'velos_stationnement' => [
                        'name' => 'Vélos stationnement',
                        'source' => 'kml',
                        'id' => 'velos_stationnement',
                    ],
                ],
            ],
            'enfance' => [
                'name' => 'Enfance',
                'icone' => 'fas fa-baby-carriage',
                'elements' => [
                    'accueillantes' => [
                        'name' => 'Accueillantes',
                        'source' => 'bottin',
                        'id' => 672,
                    ],
                    'creches' => [
                        'name' => 'Crèches',
                        'source' => 'bottin',
                        'id' => 495,
                    ],
                    'jeux' => [
                        'name' => 'Aires de jeux, parcs',
                        'source' => 'kml',
                        'id' =>  'jeux',
                        
                    ],
                ],
            ],
            'infrastructures' => [
                'name' => 'Infrastructures',
                'icone' => 'fas fa-briefcase',
                'elements' => [
                    'wifi' => [
                        'name' => 'Wifi',
                        'source' => 'kml',
                        'id' => 'wifi',
                    ],
                    'salles_commu' => [
                        'name' => 'Salles communales',
                        'source' => 'bottin',
                        'id' => 0,
                    ],
                    'cimetieres' => [
                        'name' => 'Cimetières',
                        'source' => 'bottin',
                        'id' => 0,
                    ],
                    'services_communaux' => [
                        'name' => 'Services communaux',
                        'source' => 'bottin',
                        'id' => 665,
                    ],
                    'infra' => [
                        'name' => 'Infrastructures sportives',
                        'source' => 'bottin',
                        
                        'id'
                         => 0,
                    ],
                ],
            ],
            'culture' => [
                'name' => 'Culture',
                'icone' => 'fas fa-book',
                'elements' => [
                    'musees' => [
                        'name' => 'Musées',
                        'source' => 'bottin',
                        'id' => 673,
                    ],
                    'biblio' => [
                        'name' => 'Bibliothèques',
                        'source' => 'bottin',
                        'id' => 674,
                    ],
                    'cinema' => [
                        'name' => 'Cinéma',
                        'source' => 'bottin',
                        'id' => 675,
                    ],
                    'statues' => [
                        'name' => 'Statues',
                        'source' => 'kml',
                        'id'
                         =>  'statues',
                    ],
                ],
            ],
            'sante' => [
                'name' => 'Santé',
                'icone' => 'far fa-hospital',
                'elements' => [
                    'medecins' => [
                        'name' => 'Médecine générale',
                        'source' => 'bottin',
                        'id' => 370,
                    ],
                    'pharmacies' => [
                        'name' => 'Pharmacies',
                        'source' => 'bottin',
                        'id' => 390,
                    ],
                    'mutuelles' => [
                        'name' => 'Mutuelles',
                        'source' => 'bottin',
                        'id' =>  411,
                        
                    ],
                ],
            ],
            'horeca' => [
                'name' => 'Horéca',
                'icone' => 'fas fa-cookie',
                'elements' => [
                    'brasseries' => [
                        'name' => 'Brasseries-Bar',
                        'source' => 'bottin',
                        'id' => 522,
                    ],
                    'friteries' => [
                        'name' => 'Friterie - Snack - sandwicherie',
                        'source' => 'bottin',
                        'id' => 523,
                    ],
                    'restaurants' => [
                        'name' => 'Restaurants',
                        'source' => 'bottin',
                        'id' => 521,
                    ],
                    'glaciers' => [
                        'name' => 'Glaciers - Tea room',
                        'source' => 'bottin',
                        'id' => 524,
                    ],
                    'hotels' => [
                        'name' => 'Hôtels',
                        'source' => 'bottin',
                        'id' => 649,
                    ],
                    'gites' => [
                        'name' => 'Gîtes et meublés de tourisme',
                        'source' => 'bottin',
                        'id' => 650,
                    ],
                    'chambres' => [
                        'name' => 'Chambres d\'hôtes',
                        'source' => 'bottin',
                        'id' => 651,
                    ],
                    'camping' => [
                        'name' => 'Camping',
                        'source' =>  'bottin',
                        'id' => 652,
                    ],
                ],
            ],
        ];
    }

    public function fetchKml(string $url): string
    {
        $request = $this->httpClient->request(
            'GET',
            $url,
            [
            ]
        );

        return $request->getContent();
    }

    public function foundSource(string $keySearch): array
    {
        foreach ($this->filtres() as $filtre) {
            foreach ($filtre['elements'] as $key => $element) {
                if ($keySearch === $key) {
                    return $element;
                }
            }
        }

        return [];
    }

    public function loadKml(string $keyword): string
    {
        $url = match ($keyword) {
            'seniors' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1M3CBWAF0BQ7BqLB33xFr3tu10o0',
            'statues' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1Za10EtAUa8zrOqdw2eSdUWL0nVU',
            'jeux' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1TwhxZiIAnzdvAEeUZp08BEQlU88',
            'wifi' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1NABWReYEqCBUaOjd3x5TmyTQEZw6PIfp',
            'travaux' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1kfhp1xhZcusuTMBxkDK5agYS5cQKAlrL',
            'parkings' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1-509jyExlQqn7c1ijeYxrkLVOa8',
            'cyclos' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1-509jyExlQqn7c1ijeYxrkLVOa8',
            'balades' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1eC0t63jFfVhLAjGuWTkIkfHHYqc',
            'velos_stationnement' => 'https://www.google.com/maps/d/u/1/kml?forcekml=1&mid=1A403qynTGRgt3FigLEqpIcRL4CGtazUJ',
            default => false,
        };

        if ($url) {
            return $this->fetchKml($url);
        }

        return '';
    }

    public function getFichesBottin(int $id): array
    {
        $bottinRepository = new BottinRepository();
        $data = [];
        $fiches = $bottinRepository->getFichesByCategories([$id]);
        foreach ($fiches as $fiche) {
            $data[] = $this->formatSocieteData($fiche);
        }

        return $data;
    }

    public function formatSocieteData($object): array
    {
        return [
            'nom' => $object->societe,
            'latitude' => $object->latitude,
            'longitude' => $object->longitude,
            'telephone' => $object->telephone.' '.$object->gsm,
            'email' => $object->email,
            'rue' => $object->rue.', '.$object->numero,
            'localite' => $object->cp.' '.$object->localite,
            'url' => Router::getUrlFicheBottin($object),
        ];
    }
}
