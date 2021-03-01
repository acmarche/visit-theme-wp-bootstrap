import OffreItem from './OffreItem';

const {
    useState,
    useEffect
} = wp.element;

function OffreResults( propos ) {
    const { offres } = propos;

    const listItems = offres.map( ([ key, offre ]) => ( <OffreItem
        offre={offre}
        key={key}
        clef={key}
    /> ) );

    return (
        <>
            <ul className="pt-24px pt-md-32px d-md-flex flex-md-wrap mx-md-n4px mx-lg-n8px object-cardsList mw-1440px">
                {listItems}
            </ul>
        </>
    );
}

export default OffreResults;
