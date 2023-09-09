<?php
if(!defined('WP_UNINSTALL_PLUGIN')){
  die;
}

// removing the options
delete_option('mateus_avila_isidoro_human_date');
delete_option('mateus_avila_isidoro_rows');
delete_option('mateus_avila_isidoro_emails');

// removing the transients
delete_transient('mateus-avila-isidoro-transient');