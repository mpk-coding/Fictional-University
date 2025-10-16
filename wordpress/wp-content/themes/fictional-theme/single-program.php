<?php

get_header();

while (have_posts()) {
    the_post();
    pageBanner(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link(get_post_type()); ?>">
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
                $relatedEvents->the_post();
                get_template_part('template-parts/event');
            }
        } else { ?>
            <hr class="section-break">
            <h2 class="headline headline--small-plus t-center">No upcoming events</h2>
        <?php
        }
        wp_reset_postdata();

        $relatedCampus = get_field('related_campus');

        if ($relatedCampus) { ?>
            <hr class="section-break">
            <h2 class="headline headline--small-plus t-center">Available at</h2>
            <ul class='link-list min-list'>
                <?php
                foreach ($relatedCampus as $campus) { ?>
                    <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li>
                <?php } ?>
            </ul>
        <?php
        }
        ?>

    </div>

<?php }

get_footer();

?>