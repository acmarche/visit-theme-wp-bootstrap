<?php

namespace VisitMarche\Theme\Lib;

use AcMarche\Pivot\Repository\HadesRepository;
use AcMarche\Pivot\Utils\Cache;
use Symfony\Contracts\Cache\CacheInterface;

class HadesFiltresTheme
{
    public array|object|null $filtres;
    private HadesRepository $hadesRepository;
    private CacheInterface $cache;

    public function __construct()
    {
        $this->hadesRepository = new HadesRepository();
        $this->filtres = $this->hadesRepository->getAllFiltersFromDb();
        $this->cache = Cache::instance();
    }

    public function setCounts(): void
    {
        $this->cache->get(
            'visit_filtres',
            function () {
                foreach ($this->filtres as $category) {
                    $category->count = 0;
                    if ($category->category_id) {
                        $count = $this->hadesRepository->countOffres($category->category_id);
                        $category->count = $count;
                    }
                }
            }
        );
    }

    public function getFiltresNotEmpty(): array
    {
        $notEmpty = [];
        foreach ($this->filtres as $category) {
            if ($category->category_id) {
                if (property_exists($category, 'count') && null !== $category->count && $category->count > 0) {
                    $notEmpty[] = $category;
                }
            } else {
                $notEmpty[] = $category;
            }
        }

        return $notEmpty;
    }
}
