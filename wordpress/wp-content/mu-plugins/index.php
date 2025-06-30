<?php

//  Registering custom post types
function post_types()
{
    // wp fn
    // post type name, array of props
    register_post_type('event', array(
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
}

add_action('init', 'post_types');
