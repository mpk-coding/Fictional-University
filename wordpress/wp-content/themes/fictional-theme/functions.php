<?php
require(get_theme_file_path('/includes/search-route.php'));
require(get_theme_file_path('/includes/nonce.php'));

function pageBanner($args = [])
{
  if (!isset($args['title'])) {
    $args['title'] = get_the_title();
  }

  if (!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_subtitle');
  }

  if (!isset($args['bg']) && get_field('page_banner')['sizes']['pageBanner']) {
    $args['bg'] = get_field('page_banner')['sizes']['pageBanner'];
  } else if (!isset($args['bg']) && !get_field('page_banner')['sizes']['pageBanner']) {
    $args['bg'] = get_theme_file_uri('/images/ocean.jpg');
  } ?>

  <div class="page-banner">
    <div class="page-banner__bg-image"
      style="background-image: url(<?php echo $args['bg'] ?>)">
    </div>

    <div class="page-banner__content container container--narrow">
      <div class="page-banner__thumb">
        <?php
        $thumbnail__url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
        if (has_post_thumbnail()) { ?>
          <img src="<?php echo $thumbnail__url ?>" alt="professor-thumbnail" class="professor__thumbnail">
        <?php } elseif ($thumbnail__url) { ?>
          <img src="<?php echo get_theme_file_uri('/images/placeholder-portrait.jpg') ?>" alt="professor-thumbnail" class="professor__thumbnail">
        <?php }
        ?>
      </div>
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle'] ?></p>
      </div>
    </div>
  </div>

<?php
}

// add fields to v2 REST API
function universityCustomRest()
{
  // add a custom property to the returned object in rest
  register_rest_field('post', 'authorName', array(
    'get_callback' => function () {
      return get_the_author();
    }
  ));

  register_rest_field('page', 'authorName', array(
    'get_callback' => function () {
      return get_the_author();
    }
  ));
}
add_action('rest_api_init', 'universityCustomRest');

function university_files()
{
  wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY, NULL, '1.0', true);
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}
add_action('wp_enqueue_scripts', 'university_files');

//  adding google maps api key
function universityMapKey($api)
{
  $api['key'] = GOOGLE_MAPS_API_KEY;
  return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');

function university_features()
{
  // add menus to wp
  register_nav_menus(array(
    'header' => __('Header Menu', 'fictional-theme'),
    'header_logged_in' => _('Header logged in Menu', 'fictional-theme'),
    'footer' => __('Footer Menu', 'fictional-theme'),
    'footer-secondary' => __('Footer Menu Secondary', 'fictional-theme'),
    'footer-tertiary' => __('Footer Menu Tertiary', 'fictional-theme')
  ));
  // add support for title tag in browser tab
  add_theme_support("title-tag");
  // add post thumbnail support
  add_theme_support('post-thumbnails');

  // custom image sizes
  add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'university_features');

//  modify queries for archives
function adjust_queries($query)
{
  if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) {
    $query->set('posts_per_page', -1);
    $query->set('orderby', 'title');
    $query->set('order', 'asc');
  }

  if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('posts_per_page', 10);
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'asc');
    $query->set('meta_query', array(
      'key' => 'event_date',
      'compare' => '>=',
      'value' => $today,
      'type' => 'numeric'
    ));
  }

  if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) {
    $query->set('posts_per_page', -1);
  }

  if ($query->is_search() && $query->is_main_query()) {
    $query->set('posts_per_page', 10);
  }
}

add_action('pre_get_posts', 'adjust_queries');

// redirect subscriber out of admin panel and onto the homepage

add_action('admin_init', 'redirect_subs');

function redirect_subs()
{
  $currentUser = wp_get_current_user();

  if (count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;
  }
}

add_action('wp_loaded', 'no_subs_admin_bar');

function no_subs_admin_bar()
{
  $currentUser = wp_get_current_user();
  if (count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}

// Customize login screen
add_filter('login_headerurl', 'login_header_url');

function login_header_url()
{
  return esc_url(site_url('/'));
}


// custom login css
add_action('login_enqueue_scripts', 'login_css');
add_filter('login_headertitle', 'login_title');

function login_title()
{
  return  get_bloginfo();
}

function login_css()
{
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}
