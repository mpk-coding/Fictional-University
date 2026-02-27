<?php
/*

    Plugin name: Fictional Plugin
    Description: Very descriptive text.
    Version: 1.0
    Author: mpk.coding
    Author URI: mpk-coding.github.io

*/

class WordCountAndTimePlugin
{
    function __construct()
    {
        add_action('admin_menu', array(
            $this,
            'admin_page'
        ));

        add_action('admin_init', array(
            $this,
            'settings'
        ));
    }

    function settings()
    {
        add_settings_section(
            'wcp_first_section',
            null,
            null,
            'word-count-settings'
        );

        // LOCATION
        add_settings_field(
            'wcp_location',
            'Display location',
            array(
                $this,
                'location_html'
            ),
            'word-count-settings',
            'wcp_first_section'
        );
        // LOCATION
        register_setting(
            'WordCountPlugin',
            'wcp_location',
            array(
                'sanitize_callback' => array($this, 'sanitize_input'),
                'default' => 0
            )
        );
        // HEADLINE
        add_settings_field(
            'wcp_headline',
            'Headline Text',
            array(
                $this,
                'headline_html'
            ),
            'word-count-settings',
            'wcp_first_section'
        );
        // HEADLINE
        register_setting(
            'WordCountPlugin',
            'wcp_headline',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => 'Post Statistics'
            )
        );
        // WORD COUNT
        add_settings_field(
            'wcp_wordcount',
            'Word Count',
            array(
                $this,
                'wordcount_html'
            ),
            'word-count-settings',
            'wcp_first_section'
        );
        // WORD COUNT
        register_setting(
            'WordCountPlugin',
            'wcp_wordcount',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => 1
            )
        );
        // CHARACTER COUNT
        add_settings_field(
            'wcp_charcount',
            'Character Count',
            array(
                $this,
                'charcount_html'
            ),
            'word-count-settings',
            'wcp_first_section'
        );
        // CHARACTER COUNT
        register_setting(
            'WordCountPlugin',
            'wcp_charcount',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => 0
            )
        );
        // READ TIME
        add_settings_field(
            'wcp_readtime',
            'Read Time',
            array(
                $this,
                'readtime_html'
            ),
            'word-count-settings',
            'wcp_first_section'
        );
        // READ TIME
        register_setting(
            'WordCountPlugin',
            'wcp_readtime',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => 1
            )
        );
    }

    function sanitize_input($input)
    {
        if ($input != '0' && $input != '1') {
            add_settings_error('wcp_location', 'wcp_location_error', 'Location value must be either 1 or 0.');
            return get_option('wcp_location');
        }

        return $input;
    }

    function admin_page()
    {
        add_options_page(
            'Word Count Settings',
            'Word Count',
            'manage_options',
            'word-count-settings',
            array(
                $this,
                'render_admin_page'
            )
        );
    }

    function render_admin_page()
    { ?>
        <div class='wrapper'>
            <h1>Word Count Settings</h1>
            <form action="options.php" method="POST">
                <?php
                settings_fields('WordCountPlugin');
                do_settings_sections('word-count-settings');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    function location_html()
    { ?>
        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location', '0')); ?>>Beginning of Post</option>
            <option value="1" <?php selected(get_option('wcp_location', '1')); ?>>End of Post</option>
        </select>
    <?php }

    function headline_html()
    { ?>
        <input type="text" name='wcp_headline' value="<?php echo esc_attr(get_option('wcp_headline')); ?>">
    <?php }

    function wordcount_html()
    { ?>
        <input type="checkbox" name='wcp_wordcount' value="1" <?php echo esc_attr(checked(get_option('wcp_wordcount'), 1)) ?>>
    <?php }

    function charcount_html()
    { ?>
        <input type="checkbox" name='wcp_charcount' value="1" <?php echo esc_attr(checked(get_option('wcp_charcount'), 1)) ?>>
    <?php }

    function readtime_html()
    { ?>
        <input type="checkbox" name='wcp_readtime' value="1" <?php echo esc_attr(checked(get_option('wcp_readtime'), 1)) ?>>
<?php }
}

$wordAndCountTimePlugin = new WordCountAndTimePlugin();





?>