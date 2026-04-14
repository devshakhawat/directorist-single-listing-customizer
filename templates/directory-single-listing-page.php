<?php
/**
 * Single Listing Page Template
 *
 * @since   1.0.0
 */

use \Directorist\Directorist_Single_Listing;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$listing = Directorist_Single_Listing::instance();
$listing_id = $listing->id;

// Listing data
$title          = $listing->get_title();
$tagline        = $listing->get_tagline();
$content        = $listing->get_contents();
$address        = get_post_meta( $listing_id, '_address', true );
$phone          = get_post_meta( $listing_id, '_phone', true );
$email          = $listing->contact_owner_email();
$website        = get_post_meta( $listing_id, '_website', true );
$latitude       = get_post_meta( $listing_id, '_manual_lat', true );
$longitude      = get_post_meta( $listing_id, '_manual_lng', true );
$review_count   = $listing->get_review_count();
$avg_rating     = $listing->get_rating_count();
$followers      = (int) get_post_meta( $listing_id, '_followers_count', true );
$following      = 25; // placeholder

// Images
$preview_img_id   = function_exists( 'directorist_get_listing_preview_image' ) ? directorist_get_listing_preview_image( $listing_id ) : 0;
$preview_img_url  = $preview_img_id ? wp_get_attachment_image_url( $preview_img_id, 'large' ) : '';
$gallery_ids      = function_exists( 'directorist_get_listing_gallery_images' ) ? directorist_get_listing_gallery_images( $listing_id ) : [];

// Categories & Tags
$cats     = get_the_terms( $listing_id, ATBDP_CATEGORY );
$tags     = get_the_terms( $listing_id, ATBDP_TAGS );
$locations = get_the_terms( $listing_id, ATBDP_LOCATION );

// Author
$author_id     = $listing->author_id;
$author_name   = $listing->author_info( 'name' );
$author_email  = $listing->author_info( 'email' );
$author_avatar = get_avatar_url( $author_id, [ 'size' => 48 ] );

// Social links from author
$author_facebook = $listing->author_info( 'facebook' );
$author_twitter  = $listing->author_info( 'twitter' );
$author_linkedin = $listing->author_info( 'linkedin' );

// Reviews
$reviews = get_comments( [
	'post_id' => $listing_id,
	'type'    => 'review',
	'status'  => 'approve',
	'orderby' => 'comment_date',
	'order'   => 'DESC',
	'number'  => 3,
] );

// Helper: render star rating
function sktpr_render_stars( $rating, $max = 5 ) {
	$html = '';
	$full  = floor( $rating );
	$half  = ( $rating - $full ) >= 0.5 ? 1 : 0;
	$empty = $max - $full - $half;
	for ( $i = 0; $i < $full; $i++ ) {
		$html .= '<span class="star filled">&#9733;</span>';
	}
	if ( $half ) {
		$html .= '<span class="star half">&#9733;</span>';
	}
	for ( $i = 0; $i < $empty; $i++ ) {
		$html .= '<span class="star empty">&#9734;</span>';
	}
	return $html;
}

function sktpr_get_rating_label( $avg ) {
	if ( $avg >= 4.5 ) return 'Excellent';
	if ( $avg >= 3.5 ) return 'Very Good';
	if ( $avg >= 2.5 ) return 'Good';
	if ( $avg >= 1.5 ) return 'Average';
	return 'Poor';
}
?>

<div class="sktpr-single-listing">

	<!-- ===== Hero / Header Section ===== -->
	<header class="sktpr-listing-hero" style="<?php echo $preview_img_url ? 'background-image: url(' . esc_url( $preview_img_url ) . ');' : ''; ?>">
		<div class="sktpr-hero-overlay"></div>
		<div class="sktpr-hero-container">
			<div class="sktpr-hero-content">
				<div class="sktpr-hero-avatar">
					<?php if ( $author_avatar ) : ?>
						<img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>">
					<?php endif; ?>
				</div>
				<div class="sktpr-hero-info">
					<h1 class="sktpr-listing-title"><?php echo esc_html( $title ); ?></h1>
					<?php if ( $tagline ) : ?>
						<p class="sktpr-listing-tagline"><?php echo esc_html( $tagline ); ?></p>
					<?php endif; ?>
					<?php if ( $locations ) : ?>
						<p class="sktpr-listing-location">
							<span class="sktpr-icon-location">&#9873;</span>
							<?php
							$loc_names = wp_list_pluck( $locations, 'name' );
							echo esc_html( implode( ', ', $loc_names ) );
							?>
						</p>
					<?php endif; ?>
				</div>
			</div>
			<div class="sktpr-hero-meta">
				<div class="sktpr-hero-rating">
					<span class="sktpr-rating-badge"><?php echo esc_html( number_format( $avg_rating, 1 ) ); ?></span>
					<div class="sktpr-hero-stars">
						<?php echo sktpr_render_stars( $avg_rating ); ?>
					</div>
					<span class="sktpr-review-count"><?php echo esc_html( $review_count ); ?> reviews</span>
				</div>
				<div class="sktpr-hero-actions">
					<button class="sktpr-btn sktpr-btn-share" aria-label="Share">&#10150;</button>
					<button class="sktpr-btn sktpr-btn-bookmark" aria-label="Bookmark">&#9734;</button>
				</div>
			</div>
		</div>
		<?php if ( $cats ) : ?>
			<div class="sktpr-hero-categories">
				<?php foreach ( $cats as $cat ) : ?>
					<span class="sktpr-cat-badge"><?php echo esc_html( $cat->name ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</header>

	<!-- ===== Main Content Area ===== -->
	<div class="sktpr-main-wrapper">
		<div class="sktpr-container">

			<!-- Left Column -->
			<div class="sktpr-content-area">

				<!-- Tabs Navigation -->
				<div class="sktpr-tabs-nav">
					<button class="sktpr-tab-btn active" data-tab="overview">Overview</button>
					<button class="sktpr-tab-btn" data-tab="services">Services</button>
					<button class="sktpr-tab-btn" data-tab="reviews">Reviews</button>
				</div>

				<!-- Tab: Overview -->
				<div class="sktpr-tab-content active" id="sktpr-tab-overview">

					<!-- Profile Photo Section -->
					<div class="sktpr-overview-top">
						<div class="sktpr-profile-image">
							<?php if ( $preview_img_url ) : ?>
								<img src="<?php echo esc_url( $preview_img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
							<?php endif; ?>
						</div>
						<div class="sktpr-profile-details">
							<h3><?php echo esc_html( $title ); ?></h3>
							<p class="sktpr-subtitle"><?php echo esc_html( $tagline ); ?></p>

							<!-- Contact Buttons -->
							<div class="sktpr-contact-buttons">
								<?php if ( $phone ) : ?>
									<a href="tel:<?php echo esc_attr( $phone ); ?>" class="sktpr-btn sktpr-btn-call">
										<span class="sktpr-btn-icon">&#9742;</span> Call
									</a>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<a href="mailto:<?php echo esc_attr( $email ); ?>" class="sktpr-btn sktpr-btn-email">
										<span class="sktpr-btn-icon">&#9993;</span> Email
									</a>
								<?php endif; ?>
								<?php if ( $website ) : ?>
									<a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener" class="sktpr-btn sktpr-btn-website">
										<span class="sktpr-btn-icon">&#127760;</span> Website
									</a>
								<?php endif; ?>
							</div>

							<!-- Address -->
							<?php if ( $address ) : ?>
								<p class="sktpr-address">
									<span class="sktpr-icon-location">&#9873;</span>
									<?php echo esc_html( $address ); ?>
								</p>
							<?php endif; ?>
						</div>
					</div>

					<!-- Description -->
					<div class="sktpr-description">
						<h3 class="sktpr-section-title">Description</h3>
						<div class="sktpr-description-content">
							<?php echo wp_kses_post( $content ); ?>
						</div>
					</div>

					<!-- Services / Practice Areas -->
					<?php if ( $tags ) : ?>
						<div class="sktpr-services">
							<h3 class="sktpr-section-title">Services</h3>
							<div class="sktpr-services-grid">
								<?php foreach ( $tags as $tag ) :
									$tag_img = get_term_meta( $tag->term_id, '_tag_image', true );
									?>
									<div class="sktpr-service-card">
										<?php if ( $tag_img ) : ?>
											<img src="<?php echo esc_url( wp_get_attachment_image_url( $tag_img, 'medium' ) ); ?>" alt="<?php echo esc_attr( $tag->name ); ?>">
										<?php else : ?>
											<div class="sktpr-service-placeholder">
												<span>&#9878;</span>
											</div>
										<?php endif; ?>
										<h4><?php echo esc_html( $tag->name ); ?></h4>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<!-- Stats Bar -->
					<div class="sktpr-stats-bar">
						<div class="sktpr-stat-item">
							<span class="sktpr-stat-number"><?php echo esc_html( $review_count ); ?></span>
							<span class="sktpr-stat-label">Reviews</span>
						</div>
						<div class="sktpr-stat-item">
							<span class="sktpr-stat-number"><?php echo esc_html( $followers ); ?></span>
							<span class="sktpr-stat-label">Followers</span>
						</div>
						<div class="sktpr-stat-item">
							<span class="sktpr-stat-number"><?php echo esc_html( $following ); ?></span>
							<span class="sktpr-stat-label">Following</span>
						</div>
					</div>

					<!-- Client Reviews -->
					<div class="sktpr-client-reviews">
						<h3 class="sktpr-section-title">Client Reviews (<?php echo esc_html( $review_count ); ?>)</h3>

						<?php if ( $reviews ) : ?>
							<div class="sktpr-reviews-list">
								<?php foreach ( $reviews as $review ) :
									$reviewer_name   = get_comment_author( $review );
									$reviewer_avatar = get_avatar_url( $review->user_id, [ 'size' => 40 ] );
									$review_rating   = (float) get_comment_meta( $review->comment_ID, 'rating', true );
									$review_date     = get_comment_date( 'F j, Y', $review );
									?>
									<div class="sktpr-review-item">
										<div class="sktpr-review-header">
											<div class="sktpr-reviewer-info">
												<img src="<?php echo esc_url( $reviewer_avatar ); ?>" alt="<?php echo esc_attr( $reviewer_name ); ?>" class="sktpr-reviewer-avatar">
												<div>
													<strong><?php echo esc_html( $reviewer_name ); ?></strong>
													<div class="sktpr-review-stars">
														<?php echo sktpr_render_stars( $review_rating ); ?>
													</div>
												</div>
											</div>
											<span class="sktpr-review-date"><?php echo esc_html( $review_date ); ?></span>
										</div>
										<div class="sktpr-review-content">
											<?php echo wp_kses_post( wpautop( $review->comment_content ) ); ?>
										</div>
										<div class="sktpr-review-actions">
											<button class="sktpr-btn-review-like">&#9825; Helpful</button>
										</div>
									</div>
								<?php endforeach; ?>
							</div>

							<?php if ( $review_count > 3 ) : ?>
								<div class="sktpr-view-all-reviews">
									<a href="#reviews" class="sktpr-btn sktpr-btn-outline">View All Reviews</a>
								</div>
							<?php endif; ?>
						<?php else : ?>
							<p class="sktpr-no-reviews">No reviews yet. Be the first to review!</p>
						<?php endif; ?>
					</div>

				</div>
				<!-- /Tab: Overview -->

			</div>

			<!-- Right Column / Sidebar -->
			<aside class="sktpr-sidebar">

				<!-- Contact Details Card -->
				<div class="sktpr-sidebar-card sktpr-contact-card">
					<h3 class="sktpr-card-title">Contact Details</h3>

					<?php if ( $address ) : ?>
						<div class="sktpr-contact-item">
							<span class="sktpr-contact-icon">&#9873;</span>
							<div>
								<strong>Address</strong>
								<p><?php echo esc_html( $address ); ?></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $phone ) : ?>
						<div class="sktpr-contact-item">
							<span class="sktpr-contact-icon">&#9742;</span>
							<div>
								<strong>Phone</strong>
								<p><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<div class="sktpr-contact-item">
							<span class="sktpr-contact-icon">&#9993;</span>
							<div>
								<strong>Email</strong>
								<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $website ) : ?>
						<div class="sktpr-contact-item">
							<span class="sktpr-contact-icon">&#127760;</span>
							<div>
								<strong>Website</strong>
								<p><a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener"><?php echo esc_html( wp_parse_url( $website, PHP_URL_HOST ) ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<!-- Map Card -->
				<?php if ( $latitude && $longitude ) : ?>
					<div class="sktpr-sidebar-card sktpr-map-card">
						<div id="sktpr-map" class="sktpr-map-container" data-lat="<?php echo esc_attr( $latitude ); ?>" data-lng="<?php echo esc_attr( $longitude ); ?>">
							<!-- Map will be rendered by JS -->
							<img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo esc_attr( $latitude ); ?>,<?php echo esc_attr( $longitude ); ?>&zoom=14&size=400x300&markers=color:red%7C<?php echo esc_attr( $latitude ); ?>,<?php echo esc_attr( $longitude ); ?>&key=YOUR_API_KEY" alt="Map" onerror="this.parentElement.innerHTML='<div class=sktpr-map-placeholder><span>&#128506;</span><p>Map unavailable</p></div>'">
						</div>
						<div class="sktpr-map-actions">
							<a href="https://www.google.com/maps?q=<?php echo esc_attr( $latitude ); ?>,<?php echo esc_attr( $longitude ); ?>" target="_blank" rel="noopener" class="sktpr-btn sktpr-btn-small">
								<span>&#128506;</span> Get Directions
							</a>
						</div>
					</div>
				<?php endif; ?>

				<!-- Social Links Card -->
				<?php if ( $author_facebook || $author_twitter || $author_linkedin ) : ?>
					<div class="sktpr-sidebar-card sktpr-social-card">
						<h3 class="sktpr-card-title">Follow</h3>
						<div class="sktpr-social-links">
							<?php if ( $author_facebook ) : ?>
								<a href="<?php echo esc_url( $author_facebook ); ?>" target="_blank" rel="noopener" class="sktpr-social-link sktpr-social-facebook" aria-label="Facebook">f</a>
							<?php endif; ?>
							<?php if ( $author_twitter ) : ?>
								<a href="<?php echo esc_url( $author_twitter ); ?>" target="_blank" rel="noopener" class="sktpr-social-link sktpr-social-twitter" aria-label="Twitter">&#120143;</a>
							<?php endif; ?>
							<?php if ( $author_linkedin ) : ?>
								<a href="<?php echo esc_url( $author_linkedin ); ?>" target="_blank" rel="noopener" class="sktpr-social-link sktpr-social-linkedin" aria-label="LinkedIn">in</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- Author Card -->
				<div class="sktpr-sidebar-card sktpr-author-card">
					<h3 class="sktpr-card-title">Posted by</h3>
					<div class="sktpr-author-info">
						<img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="sktpr-author-avatar">
						<div>
							<strong><?php echo esc_html( $author_name ); ?></strong>
							<p class="sktpr-author-role">Listed by</p>
						</div>
					</div>
					<?php if ( $author_email ) : ?>
						<a href="mailto:<?php echo esc_attr( $author_email ); ?>" class="sktpr-btn sktpr-btn-primary sktpr-btn-full">Contact Author</a>
					<?php endif; ?>
				</div>

				<!-- Activity Tags Card -->
				<?php if ( $tags ) : ?>
					<div class="sktpr-sidebar-card sktpr-tags-card">
						<h3 class="sktpr-card-title">Activity Tags</h3>
						<div class="sktpr-tag-cloud">
							<?php foreach ( $tags as $tag ) : ?>
								<a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="sktpr-tag-item"><?php echo esc_html( $tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

			</aside>

		</div>
	</div>

</div>

<!-- Tab switching script (inline minimal) -->
<script>
(function() {
	var tabs = document.querySelectorAll('.sktpr-tab-btn');
	var contents = document.querySelectorAll('.sktpr-tab-content');
	tabs.forEach(function(btn) {
		btn.addEventListener('click', function() {
			tabs.forEach(function(t) { t.classList.remove('active'); });
			contents.forEach(function(c) { c.classList.remove('active'); });
			this.classList.add('active');
			var target = document.getElementById('sktpr-tab-' + this.getAttribute('data-tab'));
			if (target) target.classList.add('active');
		});
	});
})();
</script>
