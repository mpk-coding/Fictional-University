<?php

// register custom API Route
function universityRegisterSearch()
{
    register_rest_route('university/v1', '/search', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'universitySearchResults'
    ), true);
}
add_action('rest_api_init', "universityRegisterSearch");

// custom API callback
function universitySearchResults($data)
{
    // get all data
    $mainQuery = new WP_Query(array(
        'post_type' => array(
            'post',
            'page',
            'professor',
            'campus',
            'event',
            'program'
        ),
        'posts_per_page' => -1,
        // search parameter; from the passed parameters, one called 'term'
        's' => sanitize_text_field($data['term'])
    ));

    // build response object
    $mainQueryResults = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array(),
    );

    // loop through data
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        // populate custom data object
        if (get_post_type() == 'post' || get_post_type() == 'page') {
            array_push($mainQueryResults['generalInfo'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type(),
                'author' => get_the_author()

            ));
        }

        // cases
        if (get_post_type() == 'professor') {
            array_push($mainQueryResults['professors'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(0, 'medium')

            ));
        }

        if (get_post_type() == 'program') {
            array_push($mainQueryResults['programs'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink()

            ));
        }

        if (get_post_type() == 'campus') {
            array_push($mainQueryResults['campuses'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink()

            ));
        }

        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));


            array_push($mainQueryResults['events'], array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'contentShort' => wp_trim_words(get_the_content(), 8),
                'excerpt' => wp_trim_words(get_the_excerpt(), 8),

            ));
        }
    }

    if ($mainQueryResults['programs']) {
        // init
        $programRelationshipMeta = array(
            'relation' => 'OR',
        );
        // populate array
        foreach ($mainQueryResults['programs'] as $program) {
            array_push($programRelationshipMeta, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $program['id'] . '"'
            ));
        }
        //  query for relationship with professor type
        $programRelationShipQuery = new WP_Query(array(
            'post_type' => 'professor',
            // flexible meta query for an x number of programs
            'meta_query' => $programRelationshipMeta
        ));

        while ($programRelationShipQuery->have_posts()) {
            $programRelationShipQuery->the_post();

            if (get_post_type() == 'professor') {
                array_push($mainQueryResults['professors'], array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'thumbnail' => get_the_post_thumbnail_url(0, 'medium')

                ));
            }
        }
    }

    // remove any and all duplicates
    // array_values removes added keys to the result
    // array unique removes duplicates from the array
    $mainQueryResults['professors'] = array_values(array_unique($mainQueryResults['professors'], SORT_REGULAR));

    return $mainQueryResults;
}
