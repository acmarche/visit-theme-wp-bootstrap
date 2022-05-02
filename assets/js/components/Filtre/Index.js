import { fetchFiltresByCategory, fetchFiltresByParent } from './service/filtre-service';

const {
    useState,
    useEffect
} = wp.element;

function Category() {
    const [ language, setLanguage ] = useState( '' );
    const [ categoryId, setCategoryId ] = useState( 0 );
    const [ rootSelectedId, setRootSelectedId ] = useState( 0 );
    const [ roots, setRoots ] = useState([]);

    async function loadFiltres() {
        let response;
        try {
            response = await fetchFiltresByCategory( language, categoryId );
            const filtres2 = Object.entries( response.data );

            //  console.log( filtres2 );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    async function loadRootFiltres( parent ) {
        let response;
        try {
            response = await fetchFiltresByParent( parent );
            setRoots( response.data );

            //console.log( filtres2 );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        const name = 'filtres-box';
        setCategoryId( document.getElementById( name ).getAttribute( 'data-category-id' ) );
        loadRootFiltres( 0 );
    }, []);

    useEffect( () => {
        loadRootFiltres( rootSelectedId );
    }, [ rootSelectedId ]);

    useEffect( () => {
        if ( 0 < categoryId ) {
            loadFiltres();
        }
    }, [ categoryId ]);

    const handleChange = ( e ) => {
        setRootSelectedId({ selectedValue: e.target.value });
    };

    return (
        <>
            <select onChange={handleChange}>
                <option value={0}>Sélectionnez une catégorie</option>
                {roots.map( ( option ) => (
                    <option value={option.reference}>{option.nom}</option>
                ) )}
            </select>
            <h1>You chose </h1>
        </>
    );
}

export default Category;
