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

        register_setting(
            'WordCountPlugin',
            'wcp_location',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => 0
            )
        );
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
            <option value="0">Beginning of Post</option>
            <option value="1">End of Post</option>
        </select>
<?php }
}

$wordAndCountTimePlugin = new WordCountAndTimePlugin();





?>