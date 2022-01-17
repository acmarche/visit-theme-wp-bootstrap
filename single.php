<?php

namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\Elasticsearch\Searcher;
use VisitMarche\Theme\Lib\PostUtils;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();
global $post;

$slugs = explode('/', get_query_var('category_name'));
$image = PostUtils::getImage($post);

$wpRepository = new WpRepository();
$currentCategory = get_category_by_slug($slugs[array_key_last($slugs)]);
$urlBack = get_category_link($currentCategory);

$tags = $wpRepository->getTags($post->ID);
$recommandations = $wpRepository->getRelations($post->ID);
$next = null;
if (0 === \count($recommandations)) {
    $searcher = new Searcher();
    global $wp_query;
    $recommandations = $searcher->searchRecommandations($wp_query);
}
if ([] !== $recommandations) {
    $next = $recommandations[0];
}

$content = get_the_content(null, null, $post);
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

Twig::rendPage(
    'article/show.html.twig',
    [
        'post' => $post,
        'currentCategory' => $currentCategory,
        'tags' => $tags,
        'image' => $image,
        'title' => $post->post_title,
        'recommandations' => $recommandations,
        'urlBack' => $urlBack,
        'content' => $content,
        'next' => $next,
    ]
);
get_footer();
