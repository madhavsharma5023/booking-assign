<?php
/**
 * Plugin Name: Movie Listings Pro
 * Description: movie assignment.
 * Version: 1.0
 * Author: madhav
 */

if (!defined('ABSPATH')) exit;

// Register  Post Type
function mlp_register_movie_post_type() {
    register_post_type('movie', [
        'labels' => [
            'name' => 'Movies',
            'singular_name' => 'Movie',
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-video',
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'movies'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'mlp_register_movie_post_type');

// Register  Taxonomy
function register_genre_taxonomy() {
    register_taxonomy('genre', 'movie', [
        'label' => 'Genre',
        'hierarchical' => true,
        'rewrite' => ['slug' => 'genre'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'register_genre_taxonomy');

