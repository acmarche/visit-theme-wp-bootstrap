import FiltresComposant from './FiltresComposant';
import { OffreResults } from './OffreResults';
import { fetchOffres } from './service/posts-service';
import CategoryTitle from './CategoryTitle';

const {
    useState,
    useEffect
} = wp.element;

function Category() {
    const [ categoryId, setCategoryId ] = useState( 0 );
    const [ offres, setOffres ] = useState([]);
    const [ filtreSelected, setFiltreSelected ] = useState( 0 );
    const [ isLoading, setIsLoading ] = useState( true );
    const [ language, setLanguage ] = useState( 'fr' );

    async function loadOffres() {
        console.log( 'loading offres', language, categoryId, filtreSelected );
        setIsLoading( true );
        let response;
        try {
            response = await fetchOffres( '', categoryId, filtreSelected );
            setOffres( response.data );
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
        setLanguage( document.getElementById( 'body' ).getAttribute( 'data-current-language' ) );
    }, []);

    useEffect( () => {
        if ( 0 < categoryId ) {
            loadOffres();
        }
    }, [ categoryId, filtreSelected ]);

    return (
        <>
            <CategoryTitle categoryId={categoryId}/>
            <FiltresComposant
                categoryId={categoryId}
                setFiltreSelected={setFiltreSelected}
                language={language}
            />
            <OffreResults
                isLoading={isLoading}
                offres={offres}/>
        </>
    );
}

export default Category;
