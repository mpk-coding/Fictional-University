<?php

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'universitySearchResults'
    ), true);
}

add_action('rest_api_init', "universityRegisterSearch");

function universitySearchResults()
{
    return 'custom api route';
}
