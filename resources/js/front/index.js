import axios from 'axios';
import qs from 'qs';
import handleNewsletter from './newsletter.js';
import cardTemplate from './card.js';
import {loadTags} from './misc.js';

loadTags();
// email form handler
handleNewsletter();

let page = 1;
const perPage = +blog_info.posts_per_page;
const filters = {};
const postContainer = document.querySelector( '.ll-archive-post-container' );
const loadButtonContainer = document.querySelector( '.load-more-button-box' );
const loadButtonTemplate = `<button class="ll-archive-load-more-button">Load More</button>`;
if ( postContainer ) {
  const filterControls = document.querySelectorAll( '.filter-control' );
  if ( filterControls.length ) {
    filterControls.forEach( ( f ) => {
      f.addEventListener( 'click', ( e ) => {
        page = 1;
        const filter = f.dataset.filter;
        const filterId = f.dataset.id;
        const filterIdArray = Array.isArray( filters[filter] ) ? filters[filter] : [];

        if ( f.classList.contains( 'is-active' ) ) {
          e.preventDefault();
          const index = filterIdArray.indexOf( filterId );
          filterIdArray.splice( index, 1 );
          if ( filterIdArray.length < 1 ) {
            delete filters[filter];
          }
          filterPosts();
        } else {
          e.preventDefault();
          filterIdArray.push( filterId );
          filters[filter] = filterIdArray;
          filterPosts();
        }
      } );
    } );
  }
  resetLoadButton();
}

function filterParams( params ) {
  const qs = Object.keys( params )
      .map( ( key ) => `${key}=${params[key]}` )
      .join( '&' );
  return qs;
}

function loadMore() {
  axios.get( `/wp-json/wp/v2/posts?_embed=1&per_page=${perPage}&page=${page + 1}&${filterParams( filters )}` )
      .then( ( res ) => {
        page++;
        res.data.forEach( ( post ) => {
          postContainer.insertAdjacentHTML( 'beforeend', cardTemplate( post ) );
        } );
      } )
      .catch( ( err ) => {
        loadButtonContainer.innerHTML = ( 'beforeend', `
          <div class="ll-archive-no-more-posts"><p>End of posts</p></div>
        ` );
      } );
}


function filterPosts( ) {
  postContainer.innerHTML = '';
  const featured = document.querySelector( '.ll-featured-post' );
  if ( featured ) {
    featured.parentElement.remove();
  }
  resetLoadButton();
  if ( filters ) {
    axios.get( `/wp-json/wp/v2/posts?_embed=1&per_page=${perPage}&page=${page}&${filterParams( filters )}` )
        .then( ( res ) => {
          res.data.forEach( ( post ) => {
            postContainer.insertAdjacentHTML( 'beforeend', cardTemplate( post ) );
          } );
        } );
  } else {
    axios.get( `/wp-json/wp/v2/posts?_embed=1&per_page=${perPage}&page=${page}` )
        .then( ( res ) => {
          res.data.forEach( ( post ) => {
            postContainer.insertAdjacentHTML( 'beforeend', cardTemplate( post ) );
          } );
        } );
  }
}


function resetLoadButton() {
  loadButtonContainer.innerHTML = loadButtonTemplate;
  const loadBtn = document.querySelector( '.ll-archive-load-more-button' );
  loadBtn.addEventListener( 'click', ( e ) => {
    loadMore();
  } );
}
