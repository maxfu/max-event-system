<?php get_header(); ?>

<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	<style type="text/css">
	.cid-qUC8WLfjwn {
		<?php if ( has_post_thumbnail() ) { ?>
		background-image: url("<?php the_post_thumbnail_url('full'); ?>");
		<?php } else { ?>
		background-image: url("<?php echo get_template_directory_uri(); ?>/assets/images/jumbotron.jpg");
		<?php } ?>
	}
	</style>

	<main role="main" <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<section class="mbr-section content5 cid-qUC8WLfjwn mbr-parallax-background" id="content5-1y">
			<div class="mbr-overlay" style="opacity: 0.4; background-color: rgb(35, 35, 35);"></div>
			<div class="container">
				<div class="media-container-row">
					<div class="title col-12">
						<h2 class="align-center mbr-bold mbr-white mbr-fonts-style display-1"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					</div>
				</div>
			</div>
		</section>

		<section class="features11 cid-qSShVnZyJK" id="content4-p">
			<div class="container">
				<?php $event_start_date = get_post_meta( get_the_ID(), 'event_begin', true ); ?>
				<?php $event_end_date = get_post_meta( get_the_ID(), 'event_end', true ); ?>
				<?php $event_venue = get_post_meta( get_the_ID(), 'event-venue', true ); ?>
				<?php $event_rsvp = get_post_meta( get_the_ID(), 'event-rsvp', true ); ?>
				<?php $event_rsvp_link = get_post_meta( get_the_ID(), 'event-rsvp-link', true ); ?>
				<?php $event_details = get_post_meta( get_the_ID(), 'event-details', true ); ?>
          <div class="media-container-row">
  			<div class="mbr-text col-9 col-md-9 mbr-fonts-style display-7">
                <?php the_content(); ?>
  			  <p>
                <?php echo __( 'Event Details as following:', 'max-event' ); ?><br>
                <?php echo __( 'Start Date: ', 'max-event' ) . date( 'd/m/Y g:i A', $event_start_date ); ?><br>
                <?php echo __( 'End Date: ', 'max-event' ) . date( 'd/m/Y g:i A', $event_end_date ); ?><br>
                <?php echo __( 'Venue: ', 'max-event' ) . $event_venue; ?><br>
              </p>
  				<?php if ( $event_rsvp == 'Yes' ) { ?>
                    <p>
                        <?php _e( 'This event is open for RSVP, please click the following to register:', 'max-event' ); ?><br>
                        <a href="<?php echo $event_rsvp_link; ?>" title="<?php the_title(); ?>"><?php the_title(); ?><?php _e( ' RSVP link', 'max-event' ); ?></a><br>
                    </p>
  				<?php } ?>
  			</div>
  		  </div>
		</div>
		</section>
	</main>
<?php endwhile; ?>
<?php else: ?>
	<main role="main">
		<section class="mbr-section article content1 cid-qSSbnPkOyI">
			<div class="container">
				<div class="media-container-row">
					<div class="mbr-text col-9 col-md-9 mbr-fonts-style display-7">
						<h2><?php _e( 'Sorry, nothing to display.', 'max-event' ); ?></h2>
					</div>
				</div>
			</div>
		</section>
	</main>
<?php endif; ?>

<?php get_footer(); ?>