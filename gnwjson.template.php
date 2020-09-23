<?php

/**
 * Template to display a single article
 *
 * @global $article
 * @return string HTML
 */
global $article;

$escaped_title = esc_html($article->Title);

?>

	<div class="gnw_wp_wrapper">
		<section id="content" class="content-area">
			<main id="main" class="site-main">
				<div><a href="#"></a></div>
				<article class="gnw_wp_article">
					<header class="gnw_wp_header">
						<?php echo '<h3>' . $escaped_title . '</h3>'; ?>
					</header>

                    <?php echo ( empty($article->ReleaseDateTime) ?
                        '' :
                        '<small class="gnw_wp_datetime">' . $article->ReleaseDateTime . '</small>' ); ?>

					<div class="gnw_wp_body">
						<?php echo $article->Content; ?>
					</div>
				</article>
			</main>
		</section>
	</div>


