<?php


namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Common\WpRepository;
use AcMarche\Theme\Inc\Router;
use AcMarche\Theme\Inc\Theme;

get_header();
global $post;

$image = null;
if (has_post_thumbnail()) {
    $images = wp_get_attachment_image_src(get_post_thumbnail_id(), 'original');
    if ($images) {
        $image = $images[0];
    }
}

$urlBack      =  Router::getCurrentUrl();

$blodId = get_current_blog_id();

$path     = Theme::getPathBlog($blodId);
$blogName = Theme::getTitleBlog($blodId);
$color    = Theme::getColorBlog($blodId);

$tags      = WpRepository::getTags($post->ID);
$relations = WpRepository::getRelations($post->ID);

$content = get_the_content(null, null, $post);
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

Twig::rendPage(
    'article/show.html.twig',
    [
        'post'        => $post,
        'tags'        => $tags,
        'image'       => $image,
        'title'       => $post->post_title,
        'blogName'    => $blogName,
        'color'       => $color,
        'path'        => $path,
        'relations'   => $relations,
        'urlBack'    => $urlBack,
        'content'     => $content,
        'readspeaker' => true,
    ]
);
get_footer();
