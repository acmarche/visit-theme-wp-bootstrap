import { fetchFiltresByCategory } from './service/filtre-service';
import { RootSelect } from './RootSelect';
import { ChildSelect } from './ChildSelect';

const {
    useState,
    useEffect
} = wp.element;

export function List( propos ) {
    const { filtres, setFiltres } = propos;
    return (
        <>
            <table className="wp-list-table widefat fixed striped table-view-list toplevel_page_pivot_list">
                <thead>
                    <tr>
                        <th scope="col" id="booktitle" className="manage-column column-booktitle column-primary">Nom</th>
                        <th scope="col" id="booktitle" className="manage-column column-booktitle column-primary">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td className={'booktitle column-booktitle has-row-actions column-primary'}>Xxx</td>
                        <td>Delete icon</td>
                    </tr>
                </tbody>
            </table>
        </>
    );
}
