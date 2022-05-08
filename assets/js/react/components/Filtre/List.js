import { deleteFiltreRequest, fetchFiltresByCategoryRequest } from './service/filtre-service';

const {
    useEffect
} = wp.element;

export function List( propos ) {
    const { categoryId } = propos;
    const { filtres, setFiltres } = propos;

    async function fetchFiltresByCategory() {
        let response;
        try {
            response = await fetchFiltresByCategoryRequest( '', categoryId );
            setFiltres( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    async function deleteFiltresCategory( id ) {
        let response;
        try {
            response = await deleteFiltreRequest( categoryId, id );
            response = await fetchFiltresByCategoryRequest( '', categoryId );
            setFiltres( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        if ( 0 < categoryId ) {
            fetchFiltresByCategory();
        }
    }, [ categoryId ]);

    const handleClick = ( id, e ) => {
        e.preventDefault();
        deleteFiltresCategory( id );
    };

    return (
        <>
            <table className="wp-list-table widefat fixed striped table-view-list toplevel_page_pivot_list">
                <thead>
                    <tr>
                        <th scope="col" id="booktitle" className="manage-column column-booktitle column-primary">Nom</th>
                        <th scope="col" id="booktitle" className="manage-column column-booktitle column-primary">Supprimer
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {filtres.map( ( filtre ) => (
                        <>
                            <tr>
                                <td className={'booktitle column-booktitle has-row-actions column-primary'}>{filtre.nom}</td>
                                <td>
                                    <button
                                        className={'button button-danger'}
                                        type={'button'}
                                        onClick={( e ) => handleClick( filtre.id, e )}>
                                        <span className="dashicons dashicons-trash"></span>
                                    </button>
                                </td>
                            </tr>
                        </>
                    ) )}
                </tbody>
            </table>
        </>
    );
}
