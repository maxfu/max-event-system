<?php

  //response generation function

  $response = "";

  //function to generate response
  function my_contact_form_generate_response($type, $message){

    global $response;

    if($type == "success") $response = "<div class='success'>{$message}</div>";
    else $response = "<div class='error'>{$message}</div>";

  }

  //response messages
  $not_human       = "Human verification incorrect.";
  $missing_content = "Please supply all information.";
  $email_invalid   = "Email Address Invalid.";
  $message_unsent  = "Message was not sent. Try Again.";
  $message_sent    = "Thanks! Your message has been sent.";

  //user posted variables
  $name = $_POST['message_name'];
  $email = $_POST['message_email'];
  $message = $_POST['message_text'];
  $human = $_POST['message_human'];

  //php mailer variables
  // $to = get_option('admin_email');
	$to = $_POST['message_email'];
  $subject = "Someone sent a message from ".get_bloginfo('name');
  $headers = 'From: '. $email . "\r\n" .
    'Reply-To: ' . $email . "\r\n";

  if(!$human == 0){
    if($human != 2) my_contact_form_generate_response("error", $not_human); //not human!
    else {

      //validate email
      if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        my_contact_form_generate_response("error", $email_invalid);
      else //email is valid
      {
        //validate presence of name and message
        if(empty($name) || empty($message)){
          my_contact_form_generate_response("error", $missing_content);
        }
        else //ready to go!
        {
          $sent = wp_mail($to, $subject, strip_tags($message), $headers);
          if($sent) {
						my_contact_form_generate_response("success", $message_sent); //message sent!
						my_contact_form_generate_response("success", $_POST['message_email']); //message sent!
					}
          else my_contact_form_generate_response("error", $message_unsent); //message wasn't sent
        }
      }
    }
  }
  else if ($_POST['submitted']) my_contact_form_generate_response("error", $missing_content);
?>

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
        <?php if ( $event_end_date < myStrtotime('now') ) { ?>
          <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7"><?php the_content(); ?></div>
          </div>
        <?php } else { ?>
          <div class="media-container-row">
  			<div class="mbr-text col-9 col-md-9 mbr-fonts-style display-7">
              <p>
                <?php _e($event_details); ?>
              </p>
  			  <p>
                <?php echo 'Event Start Date: ' . date( 'd/m/Y g:i A', $event_start_date ); ?><br>
                <?php echo 'Event End Date: ' . date( 'd/m/Y g:i A', $event_end_date ); ?><br>
                <?php echo 'Event Venue: ' . $event_venue; ?><br>
                <?php echo $event_rsvp_link; ?><br>
              </p>
  			</div>
  		  </div>
  				<?php if ( $event_rsvp == 'All' ) { ?>
  					<div class="media-container-row">
  						<div class="mbr-text col-9 col-md-9 mbr-fonts-style display-7">
  							<style type="text/css">
  								.error{
  									padding: 5px 9px;
  									border: 1px solid red;
  									color: red;
  									border-radius: 3px;
  								}

  								.success{
  									padding: 5px 9px;
  									border: 1px solid green;
  									color: green;
  									border-radius: 3px;
  								}

  								form span{
  									color: red;
  								}
  							</style>

  							<div id="respond">
  								<?php echo $response; ?>
  								<form action="<?php the_permalink(); ?>" method="post">
  									<fieldset>
  										<legend>RSVP information:</legend>
  										<p><label for="name">Name: <span>*</span> <br><input type="text" name="message_name" value="<?php echo esc_attr($_POST['message_name']); ?>"></label></p>
  										<p><label for="message_email">Email: <span>*</span> <br><input type="text" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>"></label></p>
  										<p><label for="message_text">Message: <span>*</span> <br><textarea type="text" name="message_text"><?php echo esc_textarea($_POST['message_text']); ?></textarea></label></p>
  										<p><label for="message_human">Human Verification: <span>*</span> <br><input type="text" style="width: 60px;" name="message_human"> + 3 = 5</label></p>
  										<input type="hidden" name="submitted" value="1">
  										<p><input type="submit"></p>
  									</fieldset>
  								</form>
  							</div>
  						</div>
  					</div>
  				<?php } ?>
            <?php } ?>
			</div>
		</section>
	</main>
<?php endwhile; ?>
<?php else: ?>
	<main role="main">
		<section class="mbr-section article content1 cid-qSSbnPkOyI">
			<div class="container">
				<div class="media-container-row">
					<div class="mbr-text col-12 col-md-8 mbr-fonts-style display-7">
						<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
					</div>
				</div>
			</div>
		</section>
	</main>
<?php endif; ?>

<?php get_footer(); ?>
<script charset="UTF-8" defer>(function(h){function n(a){return null===a?null:a.scrollHeight>a.clientHeight?a:n(a.parentNode)}function t(b){if(b.data){var f=JSON.parse(b.data);!f.height||p||q||(d.style.height=+f.height+"px");if(f.getter){b={};var f=[].concat(f.getter),k,h=f.length,m,c,g,e;for(k=0;k<h;k++){m=k;c=f[k]||{};c.n&&(m=c.n);g=null;try{switch(c.t){case "window":e=window;break;case "scrollParent":e=n(a)||window;break;default:e=a}if(c.e)if("rect"===c.v){g={};var l=e.getBoundingClientRect();g={top:l.top,left:l.left,width:l.width,height:l.height}}else g=e[c.v].apply(e,[].concat(c.e))||!0;else c.s?(e[c.v]=c.s,g=!0):g=e[c.v]||!1}catch(u){}b[m]=g}b.innerState=!p&&!q;a.contentWindow.postMessage(JSON.stringify({queryRes:b}),"*")}}}for(var r=h.document,b=r.documentElement;b.childNodes.length&&1==b.lastChild.nodeType;)b=b.lastChild;var d=b.parentNode,a=r.createElement("iframe");d.style.overflowY="auto";d.style.overflowX="hidden";var p=d.style.height&&"auto"!==d.style.height,q="absolute"===d.style.position||window.getComputedStyle&&"absolute"===window.getComputedStyle(d,null).getPropertyValue("position")||d.currentStyle&&"absolute"===d.currentStyle.position;h.addEventListener&&h.addEventListener("message",t,!1);a.src="<?php echo 'http://ccca-australia.au.mikecrm.com/PZ0ttu1'; ?>";a.id="mkinPZ0ttu1";a.onload=function(){a.contentWindow.postMessage(JSON.stringify({cif:1}),"*")};a.frameBorder=0;a.scrolling="no";a.style.display="block";a.style.minWidth="100%";a.style.width="100px";a.style.height="100%";a.style.border="none";a.style.overflow="auto";d.insertBefore(a,b)})(window);</script>