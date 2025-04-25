<?php
/*
Template Name: Booking Page
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_booking'])) {
    $booking_date = sanitize_text_field($_POST['booking_date']);
    $movie_name = sanitize_text_field($_POST['movie_name']);
    $movie_ticket = intval($_POST['movie_ticket']);
    $movie_email = sanitize_email($_POST['movie_email']);
    global $wpdb;
    $table_name = $wpdb->prefix . 'booking';
    $wpdb->insert(
        $table_name,
        [
            'date' => $booking_date,
            'movie_name' => $movie_name,
            'no_of_tickets' => $movie_ticket,
            'email_id' => $movie_email,
        ]
    );

    echo"data inserted";
}
?>
<h1>Booking Form</h1>

<form method="POST" id="booking-form">
    <p>
        <label for="booking_date">Select Date:</label>
        <input type="date" id="booking_date" name="booking_date" >
    </p>
    <p>
        <label for="movie_name">Select Movie:</label>
        <select id="movie_name" name="movie_name" >
            <option value="">--Select Movie--</option>
            <!-- <option value="movie">movie</option> -->
        </select>
    </p>
    <p>
        <label for="movie_ticket">Select ticket:</label>
        <input type="number" id="movie_ticket" name="movie_ticket" min="" max="">
    </p>
    <p>
        <label for="movie_email">Email:</label>
        <input type="email" id="movie_email" name="movie_email" >
    </p>
    <p>
        <input type="submit" id="submit_booking" name="submit_booking" > 
    </p>
</form>

<script>
document.getElementById('booking_date').addEventListener('change', function() {
    var selectedDate = this.value;

    var formattedDate = selectedDate.replace(/-/g, '');

    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: new URLSearchParams({
            'action': 'get_movies_by_date',
            'selected_date': formattedDate
        })
    })
    .then(response => response.json())
    .then(movies => {
        console.log(movies);
        var movieDropdown = document.getElementById('movie_name');
        movieDropdown.innerHTML = '<option value="">--Select Movie--</option>';
        if (movies.length > 0) {
            movies.forEach(movie => {
                var option = document.createElement('option');
                option.value = movie.title;
                option.textContent = movie.title;
                movieDropdown.appendChild(option);
            });
        } else {
            var option = document.createElement('option');
            option.value = '';
            option.textContent = 'No movies';
            movieDropdown.appendChild(option);
        }
    })
});

document.getElementById('movie_name').addEventListener('change', function() {
    var selectedname = this.value;
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: new URLSearchParams({
            'action': 'get_ticket_by_name',
            'selected_name': selectedname
        })
    })
    .then(response => response.json())
    .then(result => {
        console.log(result);
        
    })
});

</script>