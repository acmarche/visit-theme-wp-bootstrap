import FiltresComposant from './FiltresComposant';
import OffreResults from './OffreResults';
import { fetchOffres } from './service/posts-service';

const {
    useState,
    useEffect
} = wp.element;

function Category() {
    const [ referenceHades, setReferenceHades ] = useState( '' );
    const [ offres, setOffres ] = useState([]);
    const [ referenceOffre, setReferenceOffre ] = useState( '' );

    async function loadOffres( quoi ) {
        let response;
        try {
            console.log( `load: ${quoi}` );
            response = await fetchOffres( quoi );
            console.log( response.data );
            setOffres( Object.entries( response.data ) );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    const name = 'app-category';
    useEffect( () => {
        setReferenceHades( document.getElementById( name ).getAttribute( 'data-main-reference-hades' ) );
        if ( 0 < referenceHades.length ) {
            loadOffres( referenceHades );
        }
    }, [ referenceHades ]);

    useEffect( () => {
        if ( 0 < referenceOffre.length ) {
            loadOffres( referenceOffre );
        }
    }, [ referenceOffre ]);

    return (
        <>
            <FiltresComposant
                referenceHades={referenceHades}
                setReferenceOffre={setReferenceOffre}
            />
            <OffreResults offres={offres}/>
        </>
    );
}

export default Category;
