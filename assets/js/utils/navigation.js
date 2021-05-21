window.addEventListener( 'load', () => {

    /**
     * Fermeture ecran de recherche
     */
    const btnCloseSearch = document.getElementById( 'btn-close-search' );
    const searchScreen = document.querySelector( '.searchScreen' );
    if ( null != btnCloseSearch ) {
        btnCloseSearch.addEventListener( 'click', () => {
            searchScreen.classList.add( 'd-none' );//d-none pour bug samsung
            searchScreen.classList.remove( 'd-block' );
            searchScreen.style.top = '100%';
        });
    }

    /**
     * Ouverture ecran de recherche
     */
    const btnOpenSearch = document.getElementById( 'btn-open-search' );

    if ( null != btnOpenSearch ) {
        btnOpenSearch.addEventListener( 'click', () => {
            searchScreen.classList.add( 'd-block' );
            searchScreen.classList.remove( 'd-none' );//d-none pour bug samsung
            searchScreen.style.top = 0;
        });
    }
});
