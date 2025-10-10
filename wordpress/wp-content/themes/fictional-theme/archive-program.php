<?php

get_header();
pageBanner(array(
    'title' => get_the_archive_title(),
    'subtitle' => 'All of our courses'
)); ?>

<div class="container container--narrow page-section">
    <ul class='link-list min-list'>
        <?php
        //  start the loop
        while (have_posts()) {
            //  setup global data for post
            the_post(); ?>
            <li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>
        <?php
        }
        //  add pagination
        echo paginate_links();
        ?>
    </ul>
    <?php
    get_footer();
