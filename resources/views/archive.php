<?php
$config_post = get_posts(['post_type' => 'll_blog', ])[0];
$config = get_post_meta($config_post->ID);

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = [
  'posts_per_page' => $config['posts_per_page'][0],
  'paged' => $paged
];
if ($_GET['category']) {
  $args['category_name'] = $_GET['category'];
}
if ($_GET['tag']) {
  $args['tag'] = $_GET['tag'];
}
$custom_query = new WP_Query( $args );
$current_url = home_url( add_query_arg( array(), $wp->request ) );
$default_style = $config['apply_styles'][0] === 'true' ? 'default-style' : '';
$top_padding = $config['top_padding'][0] ? "{$config['top_padding'][0]}px" : '0px';
?>
<div class="ll-archive-wrapper <?php echo $default_style ?>" style="padding-top : <?php echo $top_padding ?>">
  <div class="tw-container">
    <div class="tw-row tw-pt-16 tw-pb-10">
      <div class="tw-col tw-mx-auto tw-text-center">
          <h1 class="ll-archive-subtitle"><?php echo $config['archive_subtitle'][0] ?></h1>
          <h2 class="ll-archive-title"><?php echo $config['archive_title'][0] ?></h2>
      </div>
    </div>
    <div class="tw-row tw-w-full xl:tw-w-11/12 tw-mx-auto">
<?php if( $config['show_sidebar'][0] === 'true' ) : ?> 
      <?php include 'sidebar.php' ?>
      <div class="tw-col tw-w-full lg:tw-w-2/3">
<?php else: ?>
      <div class="tw-col tw-w-full tw-mx-auto">
<?php endif; ?>
        <div class="inner">
        <?php if( $config['show_featured_post'][0] === 'true' && !$_GET['category'] && !$_GET['tag'] ) : ?>
          <?php $featured = get_post($config['featured_post'][0]); ?>
          <article class="tw-flex tw-flex-col tw-w-full tw-mb-12">
            <?php $featured_image = get_the_post_thumbnail_url($featured->ID) ?>
            <a href="<?php echo get_post_permalink($featured->ID) ?>" class="ll-featured-post ll-blog-card tw-block tw-relative tw-flex-1 tw-flex tw-flex-col tw-justify-stretch tw-border">
              <div class="ll-featured-post-thumbnail tw-bg-cover" style="background-image:url(<?php echo $featured_image ?: wp_get_attachment_url($config['default_grid_image'][0]); ?>);"></div>
              <div class="ll-featured-post-tag ll-card-corner-tag tw-px-2 tw-bg-white">
                Featured
              </div>
              <div class="ll-featured-post-content tw-p-4">
                <h3 class="ll-featured-post-title ll-card-post-title tw-relative"><?php echo $featured->post_title ?></h3>
                <p class="ll-featured-post-date ll-card-post-date tw-relative"><?php echo date_format( date_create( $featured->post_date ), "F d, Y" ) ?></p>
                <p class="ll-featured-post-excerpt ll-card-post-excerpt tw-relative"><?php echo get_the_excerpt($featured->ID); ?></p>
                <button class="ll-featured-post-link ll-card-post-link tw-relative">Read More</button>
              </div>
            </a>
          </article>
        <?php endif; ?>
          <div class="tw-row ll-archive-post-container">
            <?php while($custom_query->have_posts()) : $custom_query->the_post(); ?>
            <?php include 'card.php' ?>
            <?php endwhile; ?>
          </div>
          <div class="load-more-button-box tw-flex tw-justify-center tw-mb-10">
            <button class="ll-archive-load-more-button">Load More</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="ll-blog-archive-after">
    <?php do_action('rt_blog_archive_after') ?>
  </div>
</div>
