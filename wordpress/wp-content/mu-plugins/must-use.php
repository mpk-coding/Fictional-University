<?php

//  Registering custom post types
function post_types()
{
    // wp fn
    // post type name, array of props
    register_post_type('event', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array(
            'slug' => 'events'
        ),
        'has_archive' => true,
        'public' => true,
        'menu_icon' => 'dashicons-calendar',
        'show_in_rest' => true, //  enable block editor
        'labels' => array(
            'name' => "Events",
            'add_new_item' => 'Add New Event',
            'all_items' => 'All events',
            'singular_name' => 'Event',
        )
    ));

    //
    register_post_type('program', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor'),
        'rewrite' => array(
            'slug' => 'programs'
        ),
        'has_archive' => true,
        'public' => true,
        'menu_icon' => 'dashicons-awards',
        'show_in_rest' => true, //  enable block editor
        'labels' => array(
            'name' => "Programs",
            'add_new_item' => 'Add New Program',
            'all_items' => 'All programs',
            'singular_name' => 'Program',
        )
    ));
}

add_action('init', 'post_types');
