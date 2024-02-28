
const loadTags = () => {
  const tagBox = document.querySelector( '.ll-single-tag-list' );
  const loadBtn = document.querySelector( '.load-more-tags' );
  if ( loadBtn ) {
    loadBtn.addEventListener( 'click', () => {
      tagBox.classList.remove( 'limit' );
      loadBtn.style.display = 'none';
    } );
  }
};

export {loadTags};
