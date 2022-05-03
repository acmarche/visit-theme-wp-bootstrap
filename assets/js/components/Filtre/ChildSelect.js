import { fetchFiltresByParentRequest } from './service/filtre-service';

const {
    useState,
    useEffect
} = wp.element;

export function ChildSelect( propos ) {
    const { parentId } = propos;
    const { setChildId } = propos;
    const [ childs, setChilds ] = useState([]);

    async function loadRootFiltres( parent ) {
        let response;
        try {
            response = await fetchFiltresByParentRequest( parent );
            setChilds( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        if ( 0 < parentId ) { loadRootFiltres( parentId ); }
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
                    <option value={option.reference} key={option.id}>{option.nom}</option>
                ) )}
            </select>
        </>
    );
}
