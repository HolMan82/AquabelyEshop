<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="main" class="wp-content brands-page"><nav class="brand-breadcrumb" aria-label="Drobečková navigace"><a href="<?php echo esc_url(home_url('/')); ?>">Domů</a><span>›</span><span>Značky</span></nav><h1>Značky</h1><p class="brands-intro">Najděte svou oblíbenou značku a prohlédněte si její produkty.</p><?php echo bella_render_brands(); ?></main>
<?php get_footer(); ?>
