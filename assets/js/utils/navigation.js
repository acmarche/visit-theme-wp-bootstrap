function btnOpenSecond( nameMenu ) {
    if ( nameMenu ) {
        const secondToggle = document.getElementById( nameMenu );
        if ( null !== secondToggle ) {
            secondToggle.classList.toggle( 'toggle-opened' );//pour faire suivant infographiste mais marche pas
            const menu = secondToggle.getElementsByTagName( 'div' )[0];
            menu.style.left = '0';
        }
    }
}

function btnCloseSecond( nameMenu ) {
    if ( nameMenu ) {
        const secondToggle = document.getElementById( nameMenu );
        if ( null !== secondToggle ) {
            secondToggle.classList.toggle( 'toggle-opened' );//pour faire suivant infographiste mais marche pas
            const popo = secondToggle.getElementsByTagName( 'div' )[0];
            popo.style.left = '100%';
            const menu = secondToggle.getElementsByTagName( 'a' )[0];
            menu.style.color = 'white';
            menu.style.backgroundColor = 'transparent';
        }
    }
}

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

    /**
     * Fermeture box alert
     */
    const btnCloseAlert = document.getElementById( 'btn-close-alert' );

    if ( null != btnCloseAlert ) {
        const alertMessage = document.querySelector( '.object-alert' );
        btnCloseAlert.addEventListener( 'click', () => {
            alertMessage.classList.add( 'd-none' );
            const date = new Date( Date.now() );
            date.setDate( date.getDate() + 3 );
            document.cookie = `closeAlert=true; expires=${date.toUTCString()}`;
        });
    }

    /**
     * Ouverture main nav
     */
    const mainToggle = document.getElementById( 'main-toggle' );//pour faire suivant infographiste mais marche pas
    const btnOpenMainNav = document.getElementById( 'btn-open-main-nav' );
    const mainNav = document.getElementById( 'main-nav' );

    btnOpenMainNav.addEventListener( 'click', () => {
        mainToggle.classList.toggle( 'toggle-opened' );
        mainNav.style.top = 0;
        mainNav.style.zIndex = 10;
    });

    /**
     * Fermeture main nav
     */
    const btnCloseMainNav = document.getElementById( 'btn-close-main-nav' );

    btnCloseMainNav.addEventListener( 'click', () => {
        mainToggle.classList.toggle( 'toggle-opened' );
        mainNav.style.top = '100%';
        mainNav.style.zIndex = 'auto';
    });
});
