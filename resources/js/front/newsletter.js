import axios from 'axios';
import qs from 'qs';

// email form handler
const block = document.querySelector( '.ll-sidebar-newsletter-input-wrapper' );
const thanks = document.querySelector( '.newsletter-thanks' );
const form = document.querySelector( '.ll-sidebar-newsletter-signup-form' );

function handleNewsletter() {
  if ( form ) {
    form.addEventListener( 'submit', ( e ) => {
      const email = e.target.querySelector( 'input' ).value;
      e.preventDefault();
      const data = {action: 'newsletter', email};
      console.log( data );
      axios.post( blog_info.ajax_url, qs.stringify( data ) )
          .then( ( res ) => {
            console.log( res.data );
            block.classList.add( 'hidden' );
            thanks.classList.remove( 'hidden' );
          } );
    } );
  }
}

export default handleNewsletter;
