import { fetchFiltresByParentRequest } from './service/filtre-service';

const {
    useState,
    useEffect
} = wp.element;

export function ChildSelect( propos ) {
    const { parentId } = propos;
    const { setChildId } = propos;
    const [ childs, setChilds ] = useState([]);

    async function loadFiltres( ) {
        let response;
        try {
            response = await fetchFiltresByParentRequest( parentId );
            setChilds( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        if ( 0 < parentId ) { loadFiltres( ); }
    }, [ parentId ]);

    const handleChange = ( e ) => {
        e.preventDefault();
        setChildId( e.target.value );
    };

    return (
        <>
            <select onChange={handleChange}>
                <option value={0}>Sélectionnez une catégorie</option>
                {childs.map( ( option ) => (
                    <option value={option.id} key={option.id}>{option.nom}</option>
                ) )}
            </select>
        </>
    );
}
