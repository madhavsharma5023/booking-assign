<?php
// Your code to enqueue parent theme styles
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );


function create_booking_table_on_child_theme() {
   global $wpdb;

   $table_name = $wpdb->prefix . 'booking';
   $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        date date NOT NULL,
        movie_name varchar(100) NOT NULL,
        no_of_tickets int(11) NOT NULL,
        email_id varchar(100) NOT NULL,
        PRIMARY KEY (id)
   ) $charset_collate;";

   require_once ABSPATH . 'wp-admin/includes/upgrade.php';
   dbDelta($sql);
}

function check_and_create_booking_table() {
   global $wpdb;
   $table_name = $wpdb->prefix . 'booking';

   if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
      create_booking_table_on_child_theme();
   }
}

add_action('after_setup_theme', 'check_and_create_booking_table');

add_action('wp_ajax_get_movies_by_date', 'get_movies_by_date');
add_action('wp_ajax_nopriv_get_movies_by_date', 'get_movies_by_date');

function get_movies_by_date() {
    if (isset($_POST['selected_date'])) {
        $selected_date = sanitize_text_field($_POST['selected_date']);

        global $wpdb;
        $query = "
            SELECT p.ID, p.post_title
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE pm.meta_key = 'release_date' 
            AND pm.meta_value = %s
            AND p.post_status = 'publish'
        ";
        
        $movies = $wpdb->get_results($wpdb->prepare($query, $selected_date));
        $result = [];
        if ($movies) {
            foreach ($movies as $movie) {
                $result[] = [
                    'id' => $movie->ID,
                    'title' => $movie->post_title
                ];
            }
        }
        echo json_encode($result);
    }

    wp_die(); 
}

add_action('wp_ajax_get_movies_by_date', 'get_ticket_by_name');
add_action('wp_ajax_nopriv_get_movies_by_date', 'get_ticket_by_name');

function get_ticket_by_name() {

    if (isset($_POST['selected_name'])) {
        $selected_name = sanitize_text_field($_POST['selected_name']);
        global $wpdb;
        $query = "
            SELECT pm.meta_value
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_title = %s
            AND pm.meta_key = 'no_of_tickets'
        ";
        $result = $wpdb->get_results($wpdb->prepare($query, $selected_name));
        // echo json_encode($result);

        // echo "$result";
        wp_send_json(['no_of_tickets' => $result]);
    }
    wp_die(); 
}

?>

