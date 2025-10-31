<?php

get_header();

while (have_posts()) {
  the_post();
  pageBanner(); ?>

  <div class="container container--narrow page-section">

    <?php
    //  for breadcrumbs
    $parent = wp_get_post_parent_id(get_the_ID());
    $parentUrl = get_permalink($parent);
    $parentTitle = get_the_title($parent);

    if ($parent) { ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo $parentUrl; ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo $parentTitle; ?></a> <span class="metabox__main"><?php echo the_title(); ?></span>
        </p>
      </div>
    <?php }
    ?>

    <?php
    // see if post has children
    $hasChildren = get_pages(array(
      'child_of' => get_the_ID()
    ));
    if ($parent || $hasChildren) { ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo $parentUrl; ?>"><?php echo $parentTitle; ?></a></h2>
        <ul class="min-list">
          <?php
          // for list of child pages
          if ($parent) {
            // siblings
            $child_of = $parent;
          } else {
            // children
            $child_of = get_the_ID();
          }

          wp_list_pages(array(
            'title_li' => NULL,
            'child_of' => $child_of,
            'sort_column' => 'menu_order'
          ));
          ?>
          <!-- <li class="current_page_item"><a href="#">Our History</a></li>
        <li><a href="#">Our Goals</a></li> -->
        </ul>
      </div>
    <?php } ?>
    <div class="generic-content">
      <?php the_content(); ?>
    </div>
  </div>
<?php }

get_footer();

?>