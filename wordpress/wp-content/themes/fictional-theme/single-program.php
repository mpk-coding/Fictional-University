<?php

get_header();

while (have_posts()) {
    the_post(); ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo the_title(); ?></h1>
            <div class="page-banner__intro">
                <p>Insert custom field here.</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Programs</a>
                <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time('j-n-Y'); ?> in <?php echo get_the_category_list(', '); ?></span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        $relatedProfessors = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'professor',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            ),
        ));
        if ($relatedProfessors->have_posts()) { ?>

            <hr class="section-break">
            <h2 class="headline headline--small-plus t-center"><?php the_title(); ?> professors</h2>
            <ul class='professor-cards'>
                <?php
                $today = date('Ymd');
                while ($relatedProfessors->have_posts()) {
                    $relatedProfessors->the_post();
                    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'thumbnail'); ?>
                    <li class='professor-card__list-item'>
                        <a class='professor-card' href="<?php the_permalink(); ?>">
                            <?php if ($thumbnail_url) { ?>
                                <img class='professor-card__image' src='<?php echo $thumbnail_url ?>' alt='professor-thumbnail'>
                            <?php } else { ?>
                                <img class='professor-card__image' src="<?php echo get_theme_file_uri('/images/placeholder-portrait.jpg') ?>" alt='professor-thumbnail'>
                            <?php }
                            ?>
                            <span class='professor-card__name'><?php the_title(); ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <hr class="section-break">
            <h2 class="headline headline--small-plus t-center">No teachers for that course</h2>
        <?php
        }
        //  reset variables after each custom query
        wp_reset_postdata();
        ?>

        <?php
        $relatedEvents = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ),
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            ),
        ));

        if ($relatedEvents->have_posts()) { ?>

            <hr class="section-break">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
            <?php
            $today = date('Ymd');
            while ($relatedEvents->have_posts()) {
                $relatedEvents->the_post(); ?>

                <div class="event-summary">
                    <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month">
                            <?php
                            $eventDate = new DateTime(get_field('event_date'));
                            echo $eventDate->format('M');
                            ?>
                        </span>
                        <span class="event-summary__day">
                            <?php
                            $eventDate = new DateTime(get_field('event_date'));
                            echo $eventDate->format('d');
                            ?>
                        </span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <p><?php if (has_excerpt()) {
                                echo get_the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 18);
                            } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
                    </div>
                </div>

            <?php }
        } else { ?>
            <hr class="section-break">
            <h2 class="headline headline--small-plus t-center">No upcoming events</h2>
        <?php
        } ?>

    </div>

<?php }

get_footer();

?>