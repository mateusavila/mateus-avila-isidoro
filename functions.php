<?php 
function add_cors_http_header() {
  header("Access-Control-Allow-Origin: *");
}
add_action('init','add_cors_http_header');

add_action('rest_api_init', function() {
  register_rest_route('mateus-avila-isidoro', '/settings', array(
    'methods' => 'GET',
    'callback' => 'mateus_avila_isidoro_settings',
    'permission_callback' => 'mateus_avila_isidoro_permissions'),
  );
  register_rest_route('mateus-avila-isidoro', '/settings', array(
    'methods' => 'PUT',
    'callback' => 'mateus_avila_isidoro_settings_update',
    'permission_callback' => 'mateus_avila_isidoro_permissions'),
  );
  register_rest_route('mateus-avila-isidoro', '/data', array(
    'methods' => 'GET',
    'callback' => 'mateus_avila_isidoro_data',
    'permission_callback' => 'mateus_avila_isidoro_permissions'),
  );
});

function mateus_avila_isidoro_permissions() {
  return current_user_can('edit_others_posts');
}

function create_and_return($data) {
  return wp_send_json(array(
    'data' => $data
  ), 200);
}

function mateus_avila_isidoro_data(WP_REST_Request $request) {
  $transient_key = 'mateus-avila-isidoro-transient';
  $cached_data = get_transient($transient_key);
  
  if ($cached_data) {
    return $cached_data;
  }

  $resource = wp_remote_get('https://miusage.com/v1/challenge/2/static/');
  $data = json_decode(wp_remote_retrieve_body($resource), true);

  // convert graph to be using array instead of object
  $graph = $data['graph'];
  $graph_array = [];
  foreach ($graph as $item) {
    $graph_array[] = $item;
  }
  $data['graph'] = $graph_array;

  // this small piece of code adjust the values to make the URL and the cached code to be exact the same.
  $arr = array(
    "data" => $data
  );

  set_transient($transient_key, $arr, 3600);
  create_and_return($data);
}

function mateus_avila_isidoro_settings(WP_REST_Request $request) {
  $current_user = wp_get_current_user();
  $emails[] = array("email" => $current_user->user_email);
  $data = array(
    "human_date_format" => (bool) get_option('mateus_avila_isidoro_human_date', true),
    "rows" => (int) get_option('mateus_avila_isidoro_rows', 5),
    "emails" => get_option('mateus_avila_isidoro_emails', $emails),
  );
  create_and_return($data);
}

function validate_emails($emails) {
  $unique_emails = array_unique(array_column($emails, 'email'));
  if (count($unique_emails) !== count($emails)) {
    return false;
  }
  foreach ($unique_emails as $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
    }
  }
  return true;
}

function mateus_avila_isidoro_settings_update(WP_REST_Request $request) {
  $get = file_get_contents('php://input');
  $data = json_decode($get, true);

  if (gettype($data['human_date_format']) !== "boolean") {
    return wp_send_json(array(
      'error' => __("The 'human_date_format' field must be a boolean.", 'mateus-avila-isidoro')
    ), 422);
  }

  if ($data['rows'] < 1 || $data['rows'] > 5) {
    return wp_send_json(array(
      'error' => __("The 'rows' field must be between 1 and 5.", 'mateus-avila-isidoro')
    ), 422);
  }

  if (sizeof($data['emails']) === 0) {
    return wp_send_json(array(
      'error' => __('At least one email address is required.', 'mateus-avila-isidoro')
    ), 422);
  }

  if (!validate_emails($data['emails'])) {
    return wp_send_json(array(
      'error' => __('Invalid email(s) found in the list.', 'mateus-avila-isidoro')
    ), 422);
  }
  
  update_option('mateus_avila_isidoro_human_date', (bool) $data['human_date_format']);
  update_option('mateus_avila_isidoro_rows', (int) $data['rows']);
  update_option('mateus_avila_isidoro_emails', $data['emails']);
  return wp_send_json(array(
    'message' => __('The form has been successfully updated.', 'mateus-avila-isidoro')
  ), 200);
}