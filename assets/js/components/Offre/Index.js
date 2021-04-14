import FiltresComposant from './FiltresComposant';
import OffreResults from './OffreResults';
import { fetchOffres } from './service/posts-service';

const {
    useState,
    useEffect
} = wp.element;

function Category() {
    const [ categoryId, setCategoryId ] = useState( 0 );
    const [ offres, setOffres ] = useState([]);
    const [ filtreSelected, setFiltreSelected ] = useState( '' );
    const [ isLoading, setIsLoading ] = useState( true );

    async function loadOffres( ) {
        setIsLoading( true );
        let response;
        try {
            console.log( categoryId, filtreSelected );
            response = await fetchOffres( categoryId, filtreSelected );
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
        setCategoryId( document.getElementById( name ).getAttribute( 'data-category-id' ) );
    }, [ ]);

    useEffect( () => {
        if ( filtreSelected ) {
            loadOffres( );
        }
    }, [ filtreSelected ]);

    return (
        <>
            <FiltresComposant
                categoryId={categoryId}
                setFiltreSelected={setFiltreSelected}
            />
            <OffreResults
                isLoading={isLoading}
                setIsLoading={setIsLoading}
                offres={offres}/>
        </>
    );
}

export default Category;
