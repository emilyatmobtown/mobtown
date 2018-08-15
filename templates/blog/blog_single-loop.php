<?php
global $eltd_options;

$headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');
//get correct heading value
$title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : 'h5';

$blog_author_info="no";
if (isset($eltd_options['blog_author_info'])) {
    $blog_author_info = $eltd_options['blog_author_info'];
}


?>
<?php get_template_part('templates/blog/blog_single/blog_standard_type_single', 'loop'); ?>