import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faRedo } from '@fortawesome/free-solid-svg-icons';
import OffreItem from './OffreItem';

export function OffreResults( propos ) {
    const { offres, isLoading } = propos;

    const listItems = offres.map( ([ key, offre ]) => ( <OffreItem
        offre={offre}
        key={key}
        clef={key}
    /> ) );

    if ( true === isLoading ) {
        return (
            <div style={{
                marginTop: '5vh',
                fontSize: '15px',
                color: '#487F89'
            }}>
                <FontAwesomeIcon size="3x" spin={true} icon={faRedo}/>
            </div>
        );
    }

    return (
        <>
            <ul className="pt-24px pt-md-32px d-md-flex flex-md-wrap mx-md-n4px mx-lg-n8px object-cardsList mw-1440px">
                {listItems}
            </ul>
        </>
    );
}
