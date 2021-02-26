jQuery( document )
    .ready( () => {
        jQuery( '#lightSlider' )
            .lightSlider({
                item: 5,
                loop: true,
                autoWidth: true,
                onSliderLoad() {
                    jQuery( '#lightSlider' )
                        .removeClass( 'cS-hidden' );
                },
                pager: true,
                auto: true,
                slideMove: 1,
                easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                speed: 600,
                responsive: [
                    {
                        breakpoint: 800,
                        settings: {
                            item: 3,
                            slideMove: 1,
                            slideMargin: 6
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            item: 1,
                            slideMove: 1
                        }
                    }
                ]
            });
    });
