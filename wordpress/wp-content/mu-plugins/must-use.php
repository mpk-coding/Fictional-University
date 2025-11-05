<?php

//  Registering custom post types
function post_types()
{
    // wp fn
    // post type name, array of props
    register_post_type('event', array(
        // user permision / members plugin integration
        'capability_type' => 'event',
        'map_meta_cap' => true,
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
        'supports' => array('title'),
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

    //
    register_post_type('professor', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        // 'rewrite' => array(
        //     'slug' => 'professors'
        // ),  no need to rewrite the archive slug
        'has_archive' => false, // false is the default, can be omitted
        'public' => true,
        'menu_icon' => 'dashicons-admin-users',
        'show_in_rest' => true, //  enable block editor
        'labels' => array(
            'name' => "Professor",
            'add_new_item' => 'Add New Professor',
            'all_items' => 'All professors',
            'singular_name' => 'Professor',
        )
    ));

    //
    register_post_type('campus', array(
        'capability_type' => 'campus',
        'map_meta_cap' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array(
            'slug' => 'campuses'
        ),
        'has_archive' => true,
        'public' => true,
        'menu_icon' => 'dashicons-location-alt',
        'show_in_rest' => true, //  enable block editor
        'labels' => array(
            'name' => "Campus",
            'add_new_item' => 'Add New Campus',
            'all_items' => 'All campuses',
            'singular_name' => 'Campus',
        )
    ));
}

add_action('init', 'post_types');
