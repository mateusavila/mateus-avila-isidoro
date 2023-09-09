<?php
/**
* Plugin Name: Mateus Ávila Isidoro
* Plugin URI: https://www.mateusavila.com.br/
* Description: This is a test by me, Mateus Ávila Isidoro, to demonstrate my suitability for a role at Awesome Motive.
* Version: 0.1
* Author: mateusavila
* Author URI: https://www.mateusavila.com.br/
* Domain Path: /languages
**/

include 'functions.php';

if (!defined('ABSPATH')) {
  exit;
}

define('MAIN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MAIN_PLUGIN_URL', plugin_dir_url(__FILE__));

add_action('init', 'wpdocs_load_textdomain');
function wpdocs_load_textdomain() {
  load_plugin_textdomain('mateus-avila-isidoro', false, dirname(plugin_basename(__FILE__)).'/languages');
}

add_action('admin_enqueue_scripts', function() {
  // avoid applying this script in whole WP admin
  global $pagenow;
  include 'languages.php';
  if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'mateus-avila-isidoro') {
    wp_enqueue_script('mateus-avila-isidoro-js', MAIN_PLUGIN_URL.'mateus-avila-isidoro/dist/assets/js/index.js', array(), '1.0', true);
    wp_enqueue_style('mateus-avila-isidoro-css', MAIN_PLUGIN_URL.'mateus-avila-isidoro/dist/assets/css/index.css', array(), '1.0', 'all');
    wp_localize_script('mateus-avila-isidoro-js', 'wpRestNonce', [wp_create_nonce('wp_rest')]);
    wp_localize_script('mateus-avila-isidoro-js', 'languages', [$languages_list]);
  }
});

add_filter('script_loader_tag', function($tag, $handle, $src) {
  if ('mateus-avila-isidoro-js' !== $handle) {
    return $tag;
  }
  $tag = '<script type="module" src="'.esc_url($src).'"></script>';
  return $tag;
}, 10, 3);

add_action('the_content', 'load_page_vue');

function load_page_vue($content) {
  // provide a message to language english and portuguese, just to test the translation.
  $message = __('Please activate JavaScript for an enhanced experience.', 'mateus-avila-isidoro');

  // provide a fix to lateral padding
  $style = <<<STYLE
    <style>
      #wpcontent { position: relative; padding-left: 0 !important; }
    </style>
  STYLE;

  // this code is necessary to fix the submenu without style.
  $script = <<<HERED
<script>
  function setActiveLink() {
    const currentURL = window.location.href;
    const submenuLinks = document.querySelectorAll('.wp-submenu a');
    submenuLinks.forEach((link) => {
      link.classList.remove('current');
      link.parentElement.classList.remove('current');
      if (currentURL === link.href) {
        link.classList.add('current');
        link.parentElement.classList.add('current');
      }
    });
  }
  setActiveLink();
  window.addEventListener('popstate', setActiveLink);
</script>
HERED;
  $current_user = wp_get_current_user();
  $nonceBox = "<script>
    const globalURL = \"".get_rest_url()."mateus-avila-isidoro\";
    const emailUser = \"".$current_user->user_email."\";
  </script>";

  echo $content .= "
    <div class=\"main-content\" role=\"main\">
      <noscript>
        <div style=\"display: flex; height: calc(100vh - 80px); justify-content: center; align-items: center; flex-wrap: wrap; align-content: center\">
          <span class=\"dashicons dashicons-warning\" style=\"margin: 10px auto 30px; font-size: 60px; display: block\"></span>
          <h1 style=\"text-align: center; width: 100%\">{$message}</h1>
        </div>
      </noscript>
      <div id=\"app\"></div>
    </div>
    {$style}
    {$nonceBox}
    {$script}";
}

add_action('the_content', 'load_page_vue');
function menu_page_name() {
  add_menu_page('Mateus Ávila Isidoro', 'Mateus Ávila Isidoro', 'manage_options', 'mateus-avila-isidoro', 'load_page_vue', 'dashicons-awards');
  add_submenu_page('mateus-avila-isidoro', __('Graphic'), __('Graphic'), 'manage_options', 'mateus-avila-isidoro#/graphic', 'mateus-avila-isidoro-graph');
  add_submenu_page('mateus-avila-isidoro', __('Settings'), __('Settings'), 'manage_options', 'mateus-avila-isidoro#/settings', 'mateus-avila-isidoro-form');
}
add_action('admin_menu', 'menu_page_name');