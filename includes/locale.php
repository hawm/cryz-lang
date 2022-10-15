<?php

add_filter('language_attributes', 'cryz_lang_post_locale');
function cryz_lang_post_locale($output)
{
    if (!(is_single() || is_page())) return $output;

    $terms = get_the_terms(get_the_ID(), 'cryz_lang');
    
    if (!$terms || is_wp_error($terms)) return $output;
    
    $term = $terms[0];
    $locale = get_term_meta($term->term_id, 'locale', true);
    $lang = esc_attr(str_replace('_', '-', $locale));
    $lang_regex = '/lang="([a-zA-Z-_]+)"/';
    $output = preg_replace($lang_regex, 'lang="' . $lang . '"', $output);

    return $output;
}
