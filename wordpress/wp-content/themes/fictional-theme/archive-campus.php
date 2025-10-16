<?php

get_header();
pageBanner(array(
    'title' => 'Our Campuses',
    'subtitle' => 'All of our campuses'
)); ?>

<div class="container container--narrow page-section">
    <div class='acf-map'>
        <?php
        //  start the loop
        while (have_posts()) {
            //  setup global data for post
            the_post();
            $mapLocation = get_field('map_location');
        ?>
            <div
                class='marker'
                data-lat='<?php echo $mapLocation['lat'] ?>'
                data-lng='<?php echo $mapLocation['lng'] ?>'>
                <div>
                    <a href='<?php the_permalink(); ?>'>
                        <h3><?php echo the_title(); ?></h3>
                    </a>
                    <p><?php echo $mapLocation['address'] ?></p>
                </div>
            </div>

        <?php
        }
        //  add pagination
        echo paginate_links();
        ?>
    </div>
    <?php
    get_footer();
