<?php
/**
* Template Name: Syndikat Startseite
*Description: Seitentemplate für die Syndikats-Startseite
**/
?>
<?php
// css-klasse 'front-page' hinzufügen, wenn als "homepage" aufgerufen. Content und sidebars unten werden gefälliger abgegrenzt
function frontpage_add_class($classes) {
	if(is_front_page()) {
		$classes[] = 'front-page';
	}
	return $classes;
}
add_filter( 'body_class', 'frontpage_add_class');
?>

<?php get_header();?>
<?php get_sidebar('top'); ?>
<?php the_post(); ?>
<div id="featured" class="grid col-940">
  <div id="featured-content" class="grid col-460">
    
    <?php the_content(); ?>
    
  </div><!-- end of #content -->

  <div id="featured-image" class="grid col-460 fit">
    <?php if (has_post_thumbnail()) {
      the_post_thumbnail();
    }
    ?>
  </div>
</div>
<?php get_sidebar('home'); ?>
<?php get_footer(); ?>
