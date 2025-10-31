<?php $thumbnail_url = get_the_post_thumbnail_url($post_id, 'thumbnail'); ?>
<div class="post-item">
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
</div>