<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
<?php $custom_loop = new WP_Query(array( 'post_type' => 'event', 'posts_per_page' => 20, 'category_name' => 'chamber', 'meta_key' => 'event_begin', 'orderby' => 'meta_value_num', 'order' => 'DESC' )); ?>
<?php while ( $custom_loop->have_posts() ) : $custom_loop->the_post(); ?>
<div class="event_item media-container-row pt-5 pb-3">
    <div class="mbr-figure" style="width: 40%;">
        <a href=""><img src=""></a>
    </div>
    <div class="align-left aside-content">
        <h2 class="mbr-title mbr-fonts-style display-3"><a href=""><?php the_title(); ?></a></h2>
        <div class="mbr-section-text">
            <p class="mbr-text mt-3 mbr-light mbr-fonts-style display-5 fixed-height"></p>
            <p class="mbr-text mbr-light mbr-fonts-style display-5 view-more">...<a href="">了解更多</a></p>
        </div>
    </div>
</div>
<?php endwhile; ?>
// <?php if (function_exists("ccca_pagination")) { ccca_pagination($custom_loop->max_num_pages); } ?>
<?php wp_reset_postdata(); ?>
