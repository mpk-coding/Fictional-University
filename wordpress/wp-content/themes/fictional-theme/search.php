<?php

get_header();
pageBanner(array(
    'title' => 'Search results',
    'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query(false)) . '&rdquo;'
)); ?>

<div class="container container--narrow page-section">
    <?php
    //  start the loop
    if (have_posts()) {
        while (have_posts()) {
            //  setup global data for post
            the_post();
            get_template_part('template-parts/content', get_post_type());
        }
    } else {
        echo "<h2 class='headline headline--small'>No results that match the search.</h2>";
    }
    //  add pagination
    echo paginate_links();
    get_search_form();


    ?>
</div>

<?php
get_footer();
