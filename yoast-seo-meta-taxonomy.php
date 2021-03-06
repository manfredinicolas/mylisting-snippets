<?php

add_filter( 'pre_get_document_title', function( $title ) {

    $taxonomies = [
        ['tax' => 'region',                  'query_var' => 'explore_region',   'name_filter' => 'single_term_title'],
        ['tax' => 'job_listing_category',    'query_var' => 'explore_category', 'name_filter' => 'single_cat_title'],
        ['tax' => 'case27_job_listing_tags', 'query_var' => 'explore_tag',      'name_filter' => 'single_tag_title'],
    ];

    foreach ( $taxonomies as $tax ) {

        if ( get_query_var( $tax['query_var'] ) && ( $term = get_term_by( 'slug', sanitize_title( get_query_var( $tax['query_var'] ) ), $tax['tax'] ) ) ) {
            $default_title = apply_filters( 'single_term_title', $term->name );

            $title = wpseo_replace_vars( WPSEO_Taxonomy_Meta::get_term_meta( $term->term_id, $term->taxonomy, 'title' ), $term );
            
            if ( $title )  {
                return $title;
            }
            
            return $default_title;
        }
    }

    return $title;
}, 10e4 );

add_action( 'wpseo_opengraph', 'remove_yoast_test_og_tags' );
add_action( 'wp_head', 'filter_page_tags_title', 5 );

function remove_yoast_test_og_tags() {

    $taxonomies = [
        ['tax' => 'region',                  'query_var' => 'explore_region',   'name_filter' => 'single_term_title'],
        ['tax' => 'job_listing_category',    'query_var' => 'explore_category', 'name_filter' => 'single_cat_title'],
        ['tax' => 'case27_job_listing_tags', 'query_var' => 'explore_tag',      'name_filter' => 'single_tag_title'],
    ];

    foreach ( $taxonomies as $tax ) {

        if ( get_query_var( $tax['query_var'] ) && ( $term = get_term_by( 'slug', sanitize_title( get_query_var( $tax['query_var'] ) ), $tax['tax'] ) ) ) {

            add_filter( 'wpseo_og_og_title',       '__return_false', 50 );
            add_filter( 'wpseo_og_og_description', '__return_false', 50 );
            add_filter( 'wpseo_og_og_url', '__return_false', 50 );
        }
    }
}

function filter_page_tags_title( $title ) {
    $taxonomies = [
        ['tax' => 'region',                  'query_var' => 'explore_region',   'name_filter' => 'single_term_title'],
        ['tax' => 'job_listing_category',    'query_var' => 'explore_category', 'name_filter' => 'single_cat_title'],
        ['tax' => 'case27_job_listing_tags', 'query_var' => 'explore_tag',      'name_filter' => 'single_tag_title'],
    ];

    foreach ( $taxonomies as $tax ) {
        if ( get_query_var( $tax['query_var'] ) && ( $term = get_term_by( 'slug', sanitize_title( get_query_var( $tax['query_var'] ) ), $tax['tax'] ) ) ) {

            $url = get_term_link( $term->term_id, $term->taxonomy );

            $meta = get_option( 'wpseo_taxonomy_meta' );

            $description = '';

            if ( isset( $meta[$term->taxonomy][$term->term_id]['wpseo_desc'] ) ) {
                $description = $meta[$term->taxonomy][$term->term_id]['wpseo_desc'];
            }
            
            $default_title = apply_filters( 'single_term_title', $term->name );

            $title = wpseo_replace_vars( WPSEO_Taxonomy_Meta::get_term_meta( $term->term_id, $term->taxonomy, 'title' ), $term );
            
            if ( $title )  {
                printf( "<meta property=\"og:title\" content=\"%s\" />\n", esc_attr( $title ) );
            } else {
                printf( "<meta property=\"og:title\" content=\"%s\" />\n", esc_attr( $default_title ) );
            }
            
            printf( "<meta property=\"og:description\" content=\"%s\" />\n", esc_attr( $description ) );
            printf( "<meta property=\"og:url\" content=\"%s\" />\n", esc_url( $url ) );

        }
    }
}
