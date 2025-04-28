<?php
/*
Template Name: Booking-detail Page
*/

// $movie_id = isset($_GET['movie_name']);
// echo"$movie_id ";
if (!session_id()) {
    session_start();
}

// if (isset($_SESSION['movie_name'])) {
//     echo  $_SESSION['movie_name'];
// } else{
//     echo "nothing";
// }

if (isset($_SESSION['movie_name'])) {
    $movie_title = $_SESSION['movie_name'];

    global $wpdb;

    $movie_post = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT p.ID, p.post_title, pm.meta_value AS director_name, movieinfo.meta_value AS movie_info
                FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'director_name'
                LEFT JOIN {$wpdb->postmeta} movieinfo ON p.ID = movieinfo.post_id AND movieinfo.meta_key = 'movie_info'
                WHERE p.post_title = %s
                AND p.post_type = 'movie'
                AND p.post_status = 'publish'
                LIMIT 1",
            $movie_title
        )
    );
    // print_r($movie_post);

//     echo "$movie_post->ID";
//     $featured_image_url = get_the_post_thumbnail_url($movie_post->ID, 'full');
//   echo $featured_image_url;

  if ($movie_post) {
    $featured_image_url = get_the_post_thumbnail_url($movie_post->ID, 'full');

    echo '<div>';
    echo '<h1>' . esc_html($movie_post->post_title) . '</h1>';

    if ($featured_image_url) {
        echo '<img src="' . esc_url($featured_image_url) . '" alt="' . esc_attr($movie_post->post_title) . '" style="max-width:300px; height:auto;">';
    } 
    echo '<p><strong>Director Name: </strong>' . esc_html($movie_post->director_name) . '</p>';
    echo '<p><strong>Movie Info. : </strong>' . esc_html($movie_post->movie_info) . '</p>';
    echo '</div>';
  }


  $table_name = $wpdb->prefix . 'booking';
  $booking = $wpdb->get_row(
    $wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE movie_name = %s ORDER BY id DESC LIMIT 1",
                $movie_title
            )
    );

    if ($booking) {
        echo '<h3>Booking Info.</h3>';
        echo '<ul>';
        echo '<li><strong>Booking Date:</strong> ' . esc_html($booking->date) . '</li>';
        echo '<li><strong>No. of Tickets:</strong> ' . esc_html($booking->no_of_tickets) . '</li>';
        echo '<li><strong>Email ID:</strong> ' . esc_html($booking->email_id) . '</li>';
        echo '</ul>';
    } 

}

?>