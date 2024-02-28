<?php $columns = $config['show_sidebar'][0] === 'true' ? 'lg:tw-w-1/2' : 'lg:tw-w-1/3' ?>
<article class="tw-col ll-post-card tw-flex tw-flex-col tw-w-full tw-mb-12 tw-bg-white <?php echo $columns; ?>">
  <a
  href="<?php the_permalink(); ?>"
  class="post-thumbnail tw-flex tw-flex-col tw-flex-1 tw-border ll-blog-card">
    <div
    style="background-image: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id()) ?: wp_get_attachment_url($config['default_grid_image'][0]) ?>)"
    class="post-thumbnail-image">
    <?php // if( !empty(get_the_category()) ) : ?> 
      <!-- <div class="post-thumbnail-category ll-card-corner-tag tw-px-2 tw-bg-white">
        <?php // echo get_the_category()[0]->name ?>
      </div> -->
    <?php // endif; ?>
    </div>
    <div class="post-thumbnail-content tw-flex tw-flex-col tw-flex-1">
      <p class="title tw-pb-4 ll-card-post-title">
        <?php echo the_title(); ?>
      </p>
      <p class="date ll-card-post-date">
        <?php echo the_date('F j, Y'); ?>
      </p>
      <div class="excerpt tw-py-4 ll-card-post-excerpt">
        <?php if( $config['card_show_excerpt'][0] === 'true' ) : ?> 
          <?php the_excerpt(); ?>
        <?php endif; ?>
      </div>
      <button class="read-more tw-mb-4 tw-mt-auto tw-mr-auto ll-card-post-link">
        Read More
      </button>
    </div>
  </a>
</article>