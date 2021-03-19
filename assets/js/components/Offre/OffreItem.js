function OffreItem( propos ) {
    const { offre, clef } = propos;

    const indexedClass = [
        'object-card oc-new col-md-6 px-md-4px col-lg-4 px-lg-8px',
        'object-card oc-new pt-8px pt-md-0 col-md-6 px-md-4px col-lg-4 px-lg-8px',
        'object-card oc-new pt-8px pt-lg-0 col-md-6 px-md-4px col-lg-4 px-lg-8px',
        'object-card oc-new pt-8px col-md-6 px-md-4px col-lg-4 pt-lg-16px px-lg-8px'
    ];

    let description = '';
    if ( 0 < offre.descriptions.length ) {
        description = offre.descriptions[0].texte;
    }

    const categories = [];
    Object.entries( offre.categories ).forEach( ([ id, values ]) => {
        categories.push( values.lib );
    });

    let style = {};
    let classBg = 'bg-img-enjoy-1';

    if ( 0 < offre.medias.length ) {
        style = {
            backgroundImage: `url(${offre.medias[0].url})`,
            backgroundSize: 'cover'
        };
        classBg = '';
    }

    return (
        <>
            <li className={indexedClass[clef] ? indexedClass[clef] : indexedClass[3]}>
                <a href={offre.url} className="bg-img rounded-xs">
                    <i
                        style={style}
                        className={`${classBg} bg-img-size-hover-110`}>
                        <b className="d-block position-absolute top-0 bottom-0 left-0 right-0 bg-img-bgcolor-primary-0 bg-img-bgcolor-hover-primary-55 bg-img-transition-bgcolor"></b>
                        <span
                            className="text-white shadow-text-sm m-auto bg-img-opacity-0 bg-img-opacity-hover-1 transition-opacity d-block align-self-center z-10 ff-semibold fs-short-2">Lire plus</span>
                    </i>
                    <div className="col py-18px pl-28px pr-14px text-left lh-0 px-lg-16px">

                        <h3 maxlenght="0">{offre.titre}</h3>

                        <p maxlenght="170">
                            {description && description.slice( 0, 170 )}
                        </p>

                        <span className="text-primary">
                            {categories.join( ',' )}
                        </span>
                    </div>
                </a>
            </li>
        </>
    );
}

export default OffreItem;
