<?php
$args = array(
    'post_status' => 'publish',
    'post_type' => 'event',
    'meta_query' => array(
        array(
            'key'     => 'event_end',
            'value'   => (int)strtotime("now"),
            'compare' => '<',
            'type'    => 'NUMERIC',
            ),
        ),
        'meta_key' => 'event_begin',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    );
$custom_query = new WP_Query( $args );
if ( $custom_query->have_posts() ) {
    while ( $custom_query->have_posts() ) {
        $custom_query->the_post();
        $event_start_date = get_post_meta( get_the_ID(), 'event_begin', true );
        if ( (int)$event_start_date >= (int)strtotime("now") ) {
            echo $event_start_date . ' ' . strtotime("now");
        }
        $event_end_date = get_post_meta( get_the_ID(), 'event_end', true );
        $event_venue = get_post_meta( get_the_ID(), 'event-venue', true );
        $event_rsvp = get_post_meta( get_the_ID(), 'event-rsvp', true );
?>
        <div class="media-container-row pt-5 pb-3 mt-3 mb-3" id="post-<?php the_ID(); ?>">
            <div class="mbr-figure" style="width: 40%;">
                <?php if ( has_post_thumbnail() ) { ?>
			        <?php the_post_thumbnail(); ?>
                <?php } else { ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mbr-1-1200x800.jpg" alt="<?php the_title(); ?>" title="">
                <?php } ?>
            </div>
            <div class="align-left aside-content">
                <h2 class="mbr-title pt-2 mbr-fonts-style display-3"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <div class="mbr-section-text">
                    <p class="mbr-text mb-5 pt-3 mbr-light mbr-fonts-style display-5">
                        <span class="date"><?php echo __('Start Date: ', 'max-event' ) . date( get_option('date_format'), $event_start_date ); ?></span><br>
                        <span class="date"><?php echo __('End Date: ', 'max-event' ) . date( get_option('date_format'), $event_end_date ); ?></span><br>
                    </p>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php wp_reset_postdata(); ?>
<?php } else { ?>
    <article>
        <h2><?php _e( 'Exciting events coming soon!', 'max-event' ); ?></h2>
    </article>
<?php } ?>
<?php // get_template_part('pagination'); ?>
