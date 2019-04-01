<?php get_header(); ?>

<style type="text/css">
.cid-qUC8WLfjwn {
		background-image: url("<?php echo get_template_directory_uri(); ?>/assets/images/jumbotron.jpg");
}
</style>

<main role="main">

	<section class="mbr-section content5 cid-qUC8WLfjwn mbr-parallax-background" id="content5-1y">
		<div class="mbr-overlay" style="opacity: 0.4; background-color: rgb(35, 35, 35);"></div>
		<div class="container">
			<div class="media-container-row">
				<div class="title col-12">
					<h2 class="align-center mbr-bold mbr-white mbr-fonts-style display-1"><a href="<?php echo get_post_type_archive_link( 'event' ); ?>" title="<?php _e( 'Events', 'max-event' ); ?>"><?php _e( 'Events', 'max-event' ); ?></a></h2>
				</div>
			</div>
		</div>
	</section>

	<!-- section -->
	<section class="features11 cid-qSShVnZyJK" id="content4-p">
        <div class="container">
            <div class="media-container-row">
                <div class="mbr-text col-12 mbr-fonts-style display-7">
                        <?php $today = date( get_option('date_format') ); ?>
                        <?php echo $today; ?>
						<?php $args = array(
							'post_status' => 'publish',
							'post_type' => 'event',
                            'meta_query'        => array(
                                'meta_key'          => 'event_begin',
                                'type'              => 'NUMERIC',
                                'meta_value'        => strtotime("now"),
                                'meta_compare'      => '>',
                            ),
							'meta_key' => 'event_begin',
							'orderby' => 'meta_value_num',
							'order' => 'DESC',
						); ?>
						<?php $custom_query = new WP_Query( $args ); ?>
						<?php if ( $custom_query->have_posts() ) : while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
							<!-- article -->
							<?php $event_start_date = get_post_meta( get_the_ID(), 'event_begin', true ); ?>
                            <?php if ( (int)$event_start_date >= (int)strtotime("now") ) {
                                echo $event_start_date . ' ' . strtotime("now");
                            } ?>
							<?php $event_end_date = get_post_meta( get_the_ID(), 'event_end', true ); ?>
							<?php $event_venue = get_post_meta( get_the_ID(), 'event-venue', true ); ?>
							<?php $event_rsvp = get_post_meta( get_the_ID(), 'event-rsvp', true ); ?>
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
							<?php endwhile; wp_reset_postdata(); ?>
						<?php else: ?>
							<!-- article -->
							<article>
								<h2><?php _e( 'Exciting events coming soon!', 'max-event' ); ?></h2>
							</article>
							<!-- /article -->
						<?php endif; ?>
			            <?php get_template_part('pagination'); ?>
					</div>
				</div>
			</div>
		</section>
		<!-- /section -->
	</main>

	<?php get_footer(); ?>
