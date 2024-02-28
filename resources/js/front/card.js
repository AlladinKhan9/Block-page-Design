import moment from 'moment';
export default function cardTemplate( post ) {
  const columns = +blog_info.sidebar ? 2 : 3;

  return ( `
  <article class="tw-col ll-post-card tw-flex tw-flex-col tw-w-full tw-mb-12 md:tw-w-1/${columns}">
  <a
  href="${post.link}"
  class="post-thumbnail tw-flex tw-flex-col tw-flex-1 tw-border ll-blog-card">
    <div
    style="background-image: url( ${post._embedded['wp:featuredmedia'] ? post._embedded['wp:featuredmedia'][0].source_url : blog_info.default_grid_image} )"
    class="post-thumbnail-image">
    </div>
    <div class="post-thumbnail-content tw-p-4 tw-flex tw-flex-col tw-flex-1">
      <p class="tw-title tw-pb-4 ll-card-post-title">
        ${post.title.rendered}
      </p>
      <p class="date ll-card-post-date">
        ${moment( post.date ).format( 'MMMM D, YYYY' )}
      </p>
      <div class="excerpt tw-py-4 ll-card-post-excerpt">
        ${post.excerpt.rendered}
      </div>
      <button class="read-more tw-mb-4 tw-mt-auto tw-mr-auto ll-card-post-link">
        Read More
      </button>
    </div>
  </a>
</article>
` );
}
