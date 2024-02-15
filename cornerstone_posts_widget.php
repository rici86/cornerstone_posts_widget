<?php
/*
Plugin Name: Cornerstone Posts Dashboard Widget
Description: Adds a custom dashboard widget to display Yoast cornerstone posts.
*/
function cornerstone_posts_widget() {
    wp_add_dashboard_widget(
        'cornerstone_posts_widget',         
        'Cornerstone Posts',               
        'render_cornerstone_posts_widget'   
    );
}
add_action('wp_dashboard_setup', 'cornerstone_posts_widget');

// Render the custom dashboard widget
function render_cornerstone_posts_widget() {
    // Query to retrieve cornerstone posts
    $cornerstone_posts = new WP_Query(array(
        'meta_key' => '_yoast_wpseo_is_cornerstone',
        'meta_value' => '1',
        'post_type' => 'post',
        'posts_per_page' => -1,
    ));
    // Display table if cornerstone posts exist
    if ($cornerstone_posts->have_posts()) {
        echo '<table class="widefat striped" style="border: none; margin-bottom:1rem;">';
        echo '<thead><tr>';
        echo '<th>Title</th>';
        echo '<th>Categories</th>';
        echo '<th>Date</th>';
        echo '<th><span class="vers comment-grey-bubble" title="Comments"></span></th>';
        echo '<th>View Post</th>';
        echo '</tr></thead><tbody>';

        // Loop through cornerstone posts
        while ($cornerstone_posts->have_posts()) {
            $cornerstone_posts->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $post_categories = get_the_category();
            $post_date = get_the_date('d/m/Y');
            $post_comments_count = wp_count_comments($post_id)->approved;

            // Format categories as comma-separated string
            $categories_list = implode(', ', wp_list_pluck($post_categories, 'name'));

            echo '<tr>';
            // Post Title column with edit link
            echo '<td><a href="' . get_edit_post_link($post_id) . '">' . $post_title . '</a></td>';
            // Categories column
            echo '<td>' . $categories_list . '</td>';
            // Date Published column
            echo '<td>' . $post_date . '</td>';
            // Number of Comments column
            echo '<td>' . $post_comments_count . '</td>';
            // View Post column
            echo '<td><a href="' . get_permalink($post_id) . '" target="_blank" class="button">View Post</a></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo 'No cornerstone posts found.';
    }

    // Display the footer 
    echo '<div style="margin-top: 0;margin-bottom: 0;padding: 12px 0;border-top: 1px solid #f0f0f1;font-size: 0.75rem;color: #50575e;text-align: right;">';
    echo '<span>Powered by <a href="https://www.rici86.com" target="_blank">Rici86.com</a></span>';
    echo '</div>';

    // Reset post data
    wp_reset_postdata();
}
?>
