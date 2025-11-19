<?php
function universityLikeRoutes()
{
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike',
    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike',
    ));
}

add_action('rest_api_init', 'universityLikeRoutes');

function createLike(WP_REST_Request $request)
{
    $params = $request->get_json_params();
    $professor_id = sanitize_text_field($params['professor_id']);

    wp_insert_post(array(
        'post_type' => 'likes',
        'post_status' => 'publish',
        'post_title' => 'Create Post Test',
        'post_content' => 'Hello 123',
        'meta_input' => array(
            'liked_professor_id' => $professor_id,
        ),
    ));

    return 'Adding like succesful';
}

function deleteLike()
{
    return 'Thanks for trying to delete a Like';
}
