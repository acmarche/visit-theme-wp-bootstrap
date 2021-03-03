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
    const [ isLoading, setIsLoading ] = useState( true );

    async function loadOffres( quoi ) {
        setIsLoading( true );
        let response;
        try {
            console.log( quoi );
            response = await fetchOffres( quoi );
            setOffres( Object.entries( response.data ) );
            setIsLoading( false );
        } catch ( e ) {
            console.log( e );
            setIsLoading( false );
        }
        return null;
    }

    useEffect( () => {
        const name = 'app-offres';
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
            <OffreResults
                isLoading={isLoading}
                setIsLoading={setIsLoading}
                offres={offres}/>
        </>
    );
}

export default Category;
