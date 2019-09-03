<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type'         => array( 'event_moment' ),
    'nopaging'          => false,
    'posts_per_page'    => '20',
    'tax_query'         => array(
        'relation' => 'OR',
        array(
            'taxonomy'         => 'branch',
            'terms'            => 'branch',
            'field'            => 'slug',
            'include_children' => true,
        ),
        array(
            'taxonomy'         => 'branch',
            'terms'            => 'committee',
            'field'            => 'slug',
            'include_children' => true,
        ),
    ),
    'orderby' => 'date',
    'order' => 'DESC',
);

$custom_loop = new WP_Query( $args);
while ( $custom_loop->have_posts() ) : $custom_loop->the_post();
?>
<div class="event_item media-container-row pt-5 pb-3">
    <div class="mbr-figure" style="width: 40%;">
        <a href="<?php echo esc_url(get_permalink()); ?>"><img src="<?php if ( has_post_thumbnail() ) { the_post_thumbnail_url('full'); } else { echo get_template_directory_uri() . '/assets/images/1.jpg'; } ?>"></a>
    </div>
    <div class="align-left aside-content">
        <h2 class="mbr-title mbr-fonts-style display-3"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h2>
    </div>
</div>
<?php
endwhile;
if (function_exists("ccca_pagination")) { ccca_pagination($custom_loop->max_num_pages); }
wp_reset_postdata();
?>
