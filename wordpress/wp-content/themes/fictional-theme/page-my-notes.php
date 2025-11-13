<?php
if (is_user_logged_in()) {

    get_header();

    while (have_posts()) {
        the_post();
        pageBanner(); ?>

        <div class="container container--narrow page-section">
            <div class="create-note">
                <h2 class='headline headline--medium'>Create note</h2>
                <input class='new-note-title' type="text" placeholder='Title'>
                <textarea class='new-note-body' name="" id="" placeholder='Your note here'></textarea>
                <span class="submit-note">Create note</span>
                <span class='note-limit-message'>Note limit reached. Delete an existing note.</span>
            </div>
            <ul class="min-list link-list" id="my-notes">
                <?php
                $userNotes = new WP_Query(array(
                    'post_type' => 'note',
                    'posts_per_page' => -1,
                    'author' => get_current_user_id()
                ));

                while ($userNotes->have_posts()) {
                    $userNotes->the_post();
                ?>
                    <li data-id="<?php the_ID() ?>" state='readonly'>
                        <input readonly class='note-title-field' type="text" value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?>">
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"> Edit</i></span>
                        <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"> Delete</i></span>
                        <textarea readonly class='note-body-field'><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?>
                        </textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"> Save</i></span>

                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
<?php }

    get_footer();
} else {
    wp_redirect(esc_url(home_url()));
    exit;
}


?>