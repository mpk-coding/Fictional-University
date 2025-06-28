<?php

function post_types()
{
    // wp fn
    // post type name, array of props
    register_post_type('event', array(
        'public' => true,
        'menu_icon' => 'dashicons-calendar',
        'labels' => array(
            'name' => "Events",
            'add_new_item' => 'Add New Event',
            'all_items' => 'All events',
            'singular_name' => 'Event'
        )
    ));
}

add_action('init', 'post_types');
