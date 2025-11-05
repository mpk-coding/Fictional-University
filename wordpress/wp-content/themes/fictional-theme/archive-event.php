<?php

get_header();
pageBanner(array(
    'title' => get_the_archive_title(),
    'subtitle' => 'A list of all of our Events, past and future!'
)); ?>

<div class="container container--narrow page-section">
    <?php
    //  start the loop
    while (have_posts()) {
        //  setup global data for post
        the_post();
        get_template_part('template-parts/content', 'event');
    }
    //  add pagination
    echo paginate_links();
    ?>

    <hr class="section-break">

    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events') ?>">Check out our past events archive.</a></p>

    <?php
    get_footer();
