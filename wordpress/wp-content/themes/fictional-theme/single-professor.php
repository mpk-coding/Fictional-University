<?php

get_header();

while (have_posts()) {
    the_post(); ?>

    <div class="page-banner">
        <?php
        if (get_field('page_banner')['sizes']['pageBanner']) { ?>
            <div class="page-banner__bg-image" style="background-image: url(
        <?php echo get_field('page_banner')['sizes']['pageBanner'] ?>
        )">
            </div>
        <?php } else {
        ?>
            <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>)"></div>
        <?php
        } ?>

        <div class="page-banner__content container container--narrow">
            <div class="page-banner__thumb">
                <?php
                $thumbnail__url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                if (has_post_thumbnail()) { ?>
                    <img src="<?php echo $thumbnail__url ?>" alt="professor-thumbnail" class="professor__thumbnail">
                <?php } else { ?>
                    <img src="<?php echo get_theme_file_uri('/images/placeholder-portrait.jpg') ?>" alt="professor-thumbnail" class="professor__thumbnail">
                <?php }
                ?>
            </div>
            <h1 class="page-banner__title"><?php echo the_title(); ?></h1>
            <div class="page-banner__intro">
                <p><?php the_field('page_subtitle') ?></p>
                <!-- <?php
                        $related_programs = get_field('related_programs');
                        if ($related_programs) {
                            foreach ($related_programs as $program) {
                                // create an array of related programs
                                $program_titles[] = $program->post_title;
                            }
                            // add commas in-between titles
                            $subjectsTaught = implode(', ', $program_titles);
                        ?>
                    <p><?php echo $subjectsTaught . ' professor.' ?></p>
                <?php }
                ?> -->
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Professors</a>
                <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time('j-n-Y'); ?> in <?php echo get_the_category_list(', '); ?></span>
            </p>
        </div>

        <div class="generic-content">
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