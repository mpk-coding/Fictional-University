<?php

function universityRegisterSearch()
{
    register_rest_route('university/v1', '/search', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'universitySearchResults'
    ), true);
}

add_action('rest_api_init', "universityRegisterSearch");

function universitySearchResults($data)
{
    // get data
    $professors = new WP_Query(array(
        'post_type' => 'professor',
        'posts_per_page' => -1,
        's' => sanitize_text_field($data['term'])
    ));


    $professorResults = array();

    // loop through data
    while ($professors->have_posts()) {
        $professors->the_post();
        // populate custom data array
        array_push($professorResults, array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'permalink' => get_the_permalink()

        ));
    }

    return $professorResults;
}
