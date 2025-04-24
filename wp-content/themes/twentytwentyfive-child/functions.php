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

?>

