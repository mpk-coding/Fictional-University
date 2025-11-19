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
    if (is_user_logged_in()) {
        $params = $request->get_json_params();
        $professor_id = sanitize_text_field($params['professor_id']);

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'likes',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professor_id
                )
            )
        ));

        if ($existQuery->found_posts == 0 && get_post_type($professor_id) == 'professor') {
            return wp_insert_post(array(
                'post_type' => 'likes',
                'post_status' => 'publish',
                'post_title' => 'Create Post Test',
                'post_content' => 'Hello 123',
                'meta_input' => array(
                    'liked_professor_id' => $professor_id,
                ),
            ));
        } else {
            die('Invalid professor id');
        };
    } else {
        die('Only logged in users can create a Like');
    }
}

function deleteLike(WP_REST_Request $request)
{
    $params = $request->get_json_params();
    $like_id = sanitize_text_field($params['like_id']);

    if (get_current_user_id() == get_post_field('post_author', $like_id) && get_post_type($like_id) == 'likes') {
        wp_delete_post($like_id, true);
        return 'Like removed.';
    } else {
        die('You do not have permission to delete that');
    }
}
