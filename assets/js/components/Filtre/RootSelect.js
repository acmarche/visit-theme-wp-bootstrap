import { fetchFiltresByParentRequest } from './service/filtre-service';

const {
    useState,
    useEffect
} = wp.element;

export function RootSelect( propos ) {
    const [ language, setLanguage ] = useState( '' );
    const { setParentId } = propos;
    const [ roots, setRoots ] = useState([]);

    async function loadRootFiltres( ) {
        let response;
        try {
            response = await fetchFiltresByParentRequest( 0 );
            setRoots( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        loadRootFiltres( );
    }, [ ]);

    const handleChange = ( e ) => {
        e.preventDefault();
        setParentId( e.target.value );
    };

    return (
        <>
            <select onChange={handleChange}>
                <option value={0}>Sélectionnez une catégorie</option>
                {roots.map( ( option ) => (
                    <option value={option.id} key={option.id}>{option.nom}</option>
                ) )}
            </select>
        </>
    );
}
