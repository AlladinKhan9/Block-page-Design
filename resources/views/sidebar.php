<div class="sidebar-col tw-col tw-w-full lg:tw-w-1/3 tw-mb-10">
  <div class="ll-blog-sidebar tw-border tw-p-4" style="top:<?php echo $top_padding ?>;">
  <?php if( $config['sidebar_show_categories'][0] === 'true'  ) : ?>
    <div class="ll-sidebar-block">
      <p class="ll-sidebar-header tw-mb-3">
        Categories
      </p>
      <?php $cats = get_categories(); ?>
      <div class="ll-sidebar-category-list tw-mb-3">
        <?php foreach( $cats as $cat ) : ?>
          <a
          href="<?php echo get_permalink( get_option( 'page_for_posts' ) ) ?>?category=<?php echo $cat->slug ?>"
          data-toggle-class="is-active"
          data-toggle-self
          
          data-filter="categories"
          data-id="<?php echo $cat->term_id ?>"
          class="ll-sidebar-category-item tw-block filter-control">
            <?php echo $cat->name . ' (' . $cat->count . ')' ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if( $config['sidebar_show_tags'][0] === 'true'  ) : ?>
    <div class="ll-sidebar-block">
      <p class="ll-sidebar-header tw-mb-3">
        Tags
      </p>
      <?php $tags = get_tags(['orderby' => 'count','order' => 'DESC','number' => 16,]);?>
      <div class="ll-sidebar-tag-list tw-flex tw-flex-wrap tw-mb-3">
        <?php foreach( $tags as $tag ) : ?>
          <a
          href="<?php echo get_permalink( get_option( 'page_for_posts' ) ) ?>?tag=<?php echo $tag->slug ?>"
          data-filter="tags"
          data-toggle-self
          data-toggle-class="is-active"
          
          data-id="<?php echo $tag->term_id ?>"
          class="ll-sidebar-tag ll-blog-tag tag tw-px-2 tw-m-1 tw-border filter-control">
            <?php echo $tag->name ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if( $config['sidebar_show_newsletter'][0] === 'true'  ) : ?>
    <div class="ll-sidebar-block ll-sidebar-newsletter-signup">
      <p class="ll-sidebar-header tw-mb-3">
        Newsletter Sign Up
      </p>
      <form class="ll-sidebar-newsletter-signup-form">
        <label class="ll-sidebar-newsletter-label" for="newsletter-input"><?php echo $config['newsletter_text'][0] ?></label>
        <div class="ll-sidebar-newsletter-input-wrapper tw-flex tw-flex-no-wrap tw-mb-3">
          <input class="tw-border ll-sidebar-newsletter-email-input" placeholder="Email Address" type="email" name="newsletter-email" id="newsletter-input" required>
          <button class="tw-border tw-whitespace-no-wrap ll-sidebar-newsletter-submit" type="submit">Sign Up</button>
        </div>
        <div class="newsletter-thanks tw-hidden">
          <p>Thank you for signing up!</p>
        </div>
      </form>
    </div>
  <?php endif; ?>
  </div>
</div>