window.addEventListener( 'load', () => {
    const incrementBtn = document.querySelector( '.increment' );
    const decrementBtn = document.querySelector( '.decrement' );
    const container = document.querySelector( '#reading' );

    if ( null != container ) {
        const containerChildren = container.children;

        let fontSizeStatus = 1;

        incrementBtn.addEventListener( 'click', () => {
            if ( 2 > fontSizeStatus ) {
                decrementBtn.disabled = false;
                for ( let i = 0; i < containerChildren.length; i++ ) {
                    const { fontSize } = window.getComputedStyle( containerChildren[i]);
                    containerChildren[i].style.fontSize = `${parseInt( fontSize ) + 5}px`;
                }
                fontSizeStatus++;
                if ( 2 === fontSizeStatus ) {
                    incrementBtn.disabled = true;
                }
            }
        });

        decrementBtn.addEventListener( 'click', () => {
            if ( 0 < fontSizeStatus ) {
                incrementBtn.disabled = false;
                for ( let i = 0; i < containerChildren.length; i++ ) {
                    const { fontSize } = window.getComputedStyle( containerChildren[i]);
                    containerChildren[i].style.fontSize = `${parseInt( fontSize ) - 5}px`;
                }
                fontSizeStatus--;

                if ( 0 === fontSizeStatus ) {
                    decrementBtn.disabled = true;
                }
            }
        });
    }
});
