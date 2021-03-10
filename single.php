<?php


namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();
global $post;

$image = null;
if (has_post_thumbnail()) {
    $images = wp_get_attachment_image_src(get_post_thumbnail_id(), 'original');
    if ($images) {
        $image = $images[0];
    }
}
$wpRepository = new WpRepository();
$currentCategory = get_category_by_slug(get_query_var('category_name'));
$urlBack = get_category_link($currentCategory);

$tags = $wpRepository->getTags($post->ID);
$relations = $wpRepository->getRelations($post->ID);
$next = null;
if (count($relations) > 0) {
    $next = $relations[0];
}
$content = get_the_content(null, null, $post);
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

$imagesCat = ['baiser.png', 'eglise.png', 'sacdos.png', 'statue.png', 'tambour.png',];
$rand_keys = array_rand($imagesCat, 1);
$imgcat = $imagesCat[$rand_keys];

Twig::rendPage(
    'article/show.html.twig',
    [
        'post' => $post,
        'currentCategory' => $currentCategory,
        'tags' => $tags,
        'image' => $image,
        'title' => $post->post_title,
        'relations' => $relations,
        'urlBack' => $urlBack,
        'content' => $content,
        'next' => $next,
        'imgcat' => $imgcat,
    ]
);
get_footer();
