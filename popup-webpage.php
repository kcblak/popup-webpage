<?php
/*
Plugin Name: Popup Webpage
Plugin URI: https://www.linkedin.com/in/kingsley-james-hart-93679b184/?originalSubdomain=ng
Description: A plugin to create a shortcode for opening a webpage in a popup window.
Version: 1.0
Author: James-Hart Kingsley
Author URI: https://www.linkedin.com/in/kingsley-james-hart-93679b184/?originalSubdomain=ng
*/

// Enqueue necessary scripts and styles
function popup_webpage_enqueue_scripts() {
    wp_enqueue_script('popup-webpage-js', plugin_dir_url(__FILE__) . 'popup-webpage.js', array('jquery'), '1.0', true);
    wp_enqueue_style('popup-webpage-css', plugin_dir_url(__FILE__) . 'popup-webpage.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'popup_webpage_enqueue_scripts');

// Register the custom post type for popups
function popup_webpage_custom_post_type() {
    $labels = array(
        'name'               => 'Popups',
        'singular_name'      => 'Popup',
        'menu_name'          => 'Popups',
        'name_admin_bar'     => 'Popup',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Popup',
        'new_item'           => 'New Popup',
        'edit_item'          => 'Edit Popup',
        'view_item'          => 'View Popup',
        'all_items'          => 'All Popups',
        'search_items'       => 'Search Popups',
        'parent_item_colon'  => 'Parent Popups:',
        'not_found'          => 'No popups found.',
        'not_found_in_trash' => 'No popups found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'popup'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title'),
    );

    register_post_type('popup', $args);
}
add_action('init', 'popup_webpage_custom_post_type');

// Shortcode function
function popup_webpage_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'url' => '',
            'button_text' => 'Open Popup',
            'button_class' => 'popup-webpage-button',
        ), $atts, 'popup_webpage'
    );

    if (!$atts['url']) {
        return 'No URL provided.';
    }

    ob_start();
    ?>
    <button class="<?php echo esc_attr($atts['button_class']); ?>" data-url="<?php echo esc_url($atts['url']); ?>">
        <?php echo esc_html($atts['button_text']); ?>
    </button>
    <?php
    return ob_get_clean();
}
add_shortcode('popup_webpage', 'popup_webpage_shortcode');

// Add meta box for popup URL and generated shortcode
function popup_webpage_add_meta_box() {
    add_meta_box(
        'popup_webpage_meta_box',
        'Popup Settings',
        'popup_webpage_meta_box_callback',
        'popup',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'popup_webpage_add_meta_box');

function popup_webpage_meta_box_callback($post) {
    wp_nonce_field('popup_webpage_save_meta_box_data', 'popup_webpage_meta_box_nonce');

    $url_value = get_post_meta($post->ID, 'popup_url', true);
    $shortcode = '[popup_webpage url="' . esc_url($url_value) . '" button_text="Open Popup" button_class="popup-webpage-button"]';

    echo '<label for="popup_webpage_url">Popup URL:</label>';
    echo '<input type="text" id="popup_webpage_url" name="popup_webpage_url" value="' . esc_attr($url_value) . '" size="25" />';
    echo '<br><br>';
    echo '<label for="popup_webpage_shortcode">Generated Shortcode:</label>';
    echo '<input type="text" id="popup_webpage_shortcode" name="popup_webpage_shortcode" value="' . esc_attr($shortcode) . '" size="60" readonly />';
}

function popup_webpage_save_meta_box_data($post_id) {
    if (!isset($_POST['popup_webpage_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['popup_webpage_meta_box_nonce'], 'popup_webpage_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['post_type']) && 'popup' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    if (!isset($_POST['popup_webpage_url'])) {
        return;
    }

    $url = sanitize_text_field($_POST['popup_webpage_url']);
    update_post_meta($post_id, 'popup_url', $url);
}
add_action('save_post', 'popup_webpage_save_meta_box_data');

// Handle the Ajax request for awarding GamiPress points
function award_gamipress_points() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        gamipress_award_points_to_user($user_id, 'your_points_type_slug', 1);
        wp_send_json_success('Points awarded');
    } else {
        wp_send_json_error('User not logged in');
    }
}
add_action('wp_ajax_award_gamipress_points', 'award_gamipress_points');
add_action('wp_ajax_nopriv_award_gamipress_points', 'award_gamipress_points');
