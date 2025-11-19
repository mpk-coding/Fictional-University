<?php

get_header();

while (have_posts()) {
    the_post();
    pageBanner(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link(get_post_type()); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Professors</a>
                <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time('j-n-Y'); ?> in <?php echo get_the_category_list(', '); ?></span>

            </p>

        </div>


        <div class="generic-content">
            <?php
            $likeCount = new WP_Query(array(
                'post_type' => 'likes',
                'meta_query' => array(
                    array(
                        'key' => 'liked_professor_id',
                        'compare' => '=',
                        'value' => get_the_ID()
                    )
                )
            ));

            $existStatus = 'no';
            if (is_user_logged_in()) {
                $existQuery = new WP_Query(array(
                    'author' => get_current_user_id(),
                    'post_type' => 'likes',
                    'meta_query' => array(
                        array(
                            'key' => 'liked_professor_id',
                            'compare' => '=',
                            'value' => get_the_ID()
                        )
                    )
                ));
                if ($existQuery->found_posts) {
                    $existStatus = 'yes';
                }
            }

            ?>
            <span class="like-box" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>" data-id='<?php the_id() ?>' data-exists='<?php echo $existStatus ?>'>
                <i class="fa fa-heart-o" aria-hidden="true"></i>
                <i class="fa fa-heart" aria-hidden="true"></i>
                <span class="like-count"><?php echo $likeCount->post_count ?></span>
            </span>
            <?php the_content(); ?>
        </div>

        <?php
        $related_programs = get_field('related_programs');
        if ($related_programs) { ?>

            <hr class="section-break">
            <h2 class="headline headline--medium">Subjects taught</h2>
            <ul class="link-list min-list">

                <?php
                foreach ($related_programs as $program) { ?>
                    <li>
                        <a href="<?php the_permalink($program) ?>"><?php echo $program->post_title ?></a>
                    </li>
                <?php } ?>

            </ul>

        <?php } ?>


    </div>

<?php }

get_footer();

?>