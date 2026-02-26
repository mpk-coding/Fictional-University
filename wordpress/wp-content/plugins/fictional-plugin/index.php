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
        </div>
<?php }
}

$wordAndCountTimePlugin = new WordCountAndTimePlugin();





?>