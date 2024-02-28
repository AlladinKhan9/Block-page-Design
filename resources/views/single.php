<?php
$config_post = get_posts(['post_type' => 'll_blog', ])[0];
$config = get_post_meta($config_post->ID);
$default_style = $config['apply_styles'][0] === 'true' ? 'default-style' : '';
$top_padding = $config['top_padding'][0] ? "{$config['top_padding'][0]}px" : '0px';

?>
<div class="ll-single-wrapper tw-py-10 <?php echo $default_style ?>" style="padding-top : <?php echo $top_padding ?>">
  <div class="tw-container">
    <div class="tw-row tw-mx-auto tw-w-full xl:tw-w-11/12">
      <div class="tw-col tw-px-0 tw-mb-2">
        <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ) ?>" class="ll-back-to-archive-link">Back to blog</a>
      </div>
    </div>
    <div class="tw-row tw-w-full tw-flex-col-reverse lg:tw-flex-row xl:tw-w-11/12 tw-mx-auto">
    <?php if( $config['show_sidebar'][0] === 'true' ) : ?>
      <?php include 'sidebar.php' ?>
      <div class="tw-col tw-w-full lg:tw-w-2/3">
      <?php else: ?>
        <div class="tw-col tw-w-full lg:tw-w-2/3 tw-mx-auto">
    <?php endif; ?>
        <?php if( have_posts() ) : ?>
          <?php while (have_posts()): the_post(); ?>
            <div class="inner">
              <div class="post-header text-center mb-12">
                <?php if ( get_post_meta($post->ID, '_ll_subtitle', true) ) : ?>
                  <h1 class="ll-post-header-small-text">
                    <?php echo get_post_meta($post->ID, '_ll_subtitle', true); ?>
                  </h1>
                  <h2 class="ll-post-header-title">
                    <?php the_title(); ?>
                  </h2>
                <?php else : ?>
                  <h1 class="ll-post-header-title">
                    <?php the_title(); ?>
                  </h1>
                <?php endif; ?>
                <div class="ll-post-header-details">
                  <span class="ll-post-header-category tw-border tw-px-2 tw-mx-1">
                    <?php echo get_the_category()[0]->name; ?>
                  </span>
                  <span class="ll-post-header-details-divider">|</span>
                  <span class="ll-post-header-date tw-mx-1">
                    <?php echo get_the_date(); ?>
                  </span>
                  <span class="ll-post-header-details-divider">|</span>
                  <span class="ll-post-header-author tw-mx-1">
                    <?php echo get_the_author(); ?>
                  </span>
                </div>
              </div>
              <div class="post-content tw-mb-10">
                <img class="post-content-featured-image tw-mb-14 tw-block" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id()) ?: wp_get_attachment_url($config['default_grid_image'][0]) ?>">
                <div class="post-content-wysiwyg">
                  <?php the_content(); ?>
                </div>
                <?php do_action('ll_blog_single_after_content') ?>
              </div>
              <div class="tw-row items-below-content ll-post-footer">
                <div class="tw-col post-tags tw-w-full ll-post-footer-block tw-w-full md:tw-w-1/2">
                  <p class="post-tags-header ll-post-footer-header">
                    Tags
                  </p>
                  <?php $tags = get_tags();?>
                  <div class="ll-single-tag-list tw-flex tw-flex-wrap tw-mb-3 limit">
                    <?php foreach( $tags as $tag ) : ?>
                      <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ) ?>?tag=<?php echo $tag->slug ?>"
                      class="tag tw-px-2 tw-m-1 tw-border ll-single-post-tag">
                        <?php echo $tag->name ?>
                      </a>
                    <?php endforeach; ?>
                  </div>
                  <button class="load-more-tags">Load More</button>
                </div>
                <div class="col post-social-links tw-w-full md:tw-w-1/2 md:tw-pl-10">
                  <p class="post-social-header ll-post-footer-header">
                    Share
                  </p>
                  <?php
                    $encoded_url =  rawurlencode( get_permalink( ) );
                    $encoded_title = get_the_title();
                    $fb_share_link = "https://www.facebook.com/sharer/sharer.php?u={$encoded_url}";
                    $twitter_share_link = "https://twitter.com/intent/tweet?url={$encoded_url}&text={$encoded_title}";
                    $linkedin_share_link = "https://www.linkedin.com/shareArticle?url={$encoded_url}&title={$encoded_title}";
                  ?>
                  <a class="share-facebook" target="_blank" href="<?php echo $fb_share_link; ?>">
                    <svg class="share-icon tw-fill-current tw-inline-block tw-mr-3 tw-w-10 tw-h-10"><use xlink:href="#icon-facebook"></use></svg>
                  </a>
                  <a class="share-twitter" target="_blank" href="<?php echo $twitter_share_link; ?>">
                    <svg class="share-icon tw-fill-current tw-inline-block tw-mr-3 tw-w-10 tw-h-10"><use xlink:href="#icon-twitter"></use></svg>
                  </a>
                  <a class="share-linkedin" target="_blank" href="<?php echo $linkedin_share_link; ?>">
                    <svg class="share-icon tw-fill-current tw-inline-block tw-mr-3 tw-w-10 tw-h-10"><use xlink:href="#icon-linkedin"></use></svg>
                  </a>
                </div>
                <?php
                $args = [
                  'posts_per_page' => 6,
                  'category_name' => get_the_category()[0]->slug,
                  'post__not_in' => [$post->ID]
                ];
                $rel_query = new WP_Query($args);
                ?>
                <?php if( $rel_query->have_posts() ) : ?>
                  <div class="tw-col tw-w-full post-related ll-post-footer-block">
                    <p class="post-related-header ll-post-footer-header">
                      Related Posts
                    </p>
                    <div class="tw-row">
                    <?php while($rel_query->have_posts()) : $rel_query->the_post(); ?>
                      <?php include 'card.php' ?>
                    <?php endwhile; ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="ll-blog-single-after">
    <?php do_action('ll_blog_single_after') ?>
  </div>
</div>