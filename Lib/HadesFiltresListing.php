<?php

namespace VisitMarche\Theme\Lib;

use AcMarche\Pivot\Repository\HadesRepository;
use AcMarche\Pivot\Utils\Cache;
use Symfony\Contracts\Cache\CacheInterface;

class HadesFiltresListing
{
    public array|object|null $filtres;
    private HadesRepository $hadesRepository;
    private CacheInterface $cache;

    public function __construct()
    {
        $this->hadesRepository = new HadesRepository();
        $this->cache = Cache::instance();
    }

    public function setCounts(array $filtres): void
    {
        $this->cache->get(
            'visit_filtres',
            function () use ($filtres) {
                foreach ($filtres as $lvl1) {
                    $this->doCount($lvl1);
                    foreach ($lvl1->children as $lvl2) {
                        $this->doCount($lvl2);
                        foreach ($lvl2->children as $lvl3) {
                            $this->doCount($lvl3);
                            foreach ($lvl3->children as $lvl4) {
                                $this->doCount($lvl4);
                            }
                        }
                    }
                }
            }
        );
    }

    public function getFiltresNotEmpty(array $filters)
    {
        foreach ($filters as $lvl1) {
            $this->doEmpty($lvl1);
            foreach ($lvl1->children as $lvl2) {
                $this->doEmpty($lvl2);
                foreach ($lvl2->children as $lvl3) {
                    $this->doEmpty($lvl3);
                    foreach ($lvl3->children as $lvl4) {
                        $this->doEmpty($lvl4);
                    }
                }
            }
        }
    }

    private function doEmpty(\stdClass $category)
    {
        if($category->keyword) {
            if (isset($category->count) && $category->count == 0) {
                $category->display = false;
            }
        }
    }

    private function doCount(\stdClass $category)
    {
        $category->count = 0;
        if ($category->keyword) {
            $count = $this->hadesRepository->countOffres($category->keyword);
            $category->count = $count;
        }
    }
}
