<?php

namespace VisitMarche\Theme\Lib;

use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;

class Cache
{
    public static ?CacheInterface $instanceObject = null;

    public static function instance(): CacheInterface
    {
        if (null !== self::$instanceObject) {
            return self::$instanceObject;
        }

        if (\extension_loaded('apc') && ini_get('apc.enabled')) {
            self::$instanceObject =
                new ApcuAdapter(
                // a string prefixed to the keys of the items stored in this cache
                    $namespace = 'visitmarche',

                    // the default lifetime (in seconds) for cache items that do not define their
                    // own lifetime, with a value 0 causing items to be stored indefinitely (i.e.
                    // until the APCu memory is cleared)
                    $defaultLifetime = 3600,

                    // when set, all keys prefixed by $namespace can be invalidated by changing
                    // this $version string
                    $version = null
                );
        } else {
            self::$instanceObject =
                new FilesystemAdapter(
                    'newmarche2',
                    3600,
                    null
                );
        }

        //return new TagAwareAdapter(self::$instanceObject, self::$instanceObject);
        return self::$instanceObject;
    }

    public static function refresh(string $code): void
    {
        $request = Request::createFromGlobals();
        $refresh = $request->get('refresh', null);

        $cache = self::instance();
        if ($refresh) {
            $cache->delete($code);
        }
    }

    public static function generateCodeBottin(int $blogId, string $slug): string
    {
        return 'bottin-fiche-'.$blogId.'-'.$slug;
    }

    public static function generateCodeArticle(int $blogId, int $postId): string
    {
        return 'post-'.$blogId.'-'.$postId;
    }

    public static function generateCodeCategory(int $blogId, int $categoryId): string
    {
        return 'category-'.$blogId.'-'.$categoryId;
    }
}
