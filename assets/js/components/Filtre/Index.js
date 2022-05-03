import { addFiltreRequest, fetchFiltresByCategoryRequest } from './service/filtre-service';
import { RootSelect } from './RootSelect';
import { ChildSelect } from './ChildSelect';
import { List } from './List';

const {
    useState,
    useEffect
} = wp.element;

function Category() {
    const [ language, setLanguage ] = useState( '' );
    const [ categoryId, setCategoryId ] = useState( 0 );
    const [ parentId, setParentId ] = useState( 0 );
    const [ childId, setChildId ] = useState( 0 );
    const [ filtres, setFiltres ] = useState([]);

    useEffect( () => {
        const name = 'filtres-box';
        setCategoryId( document.getElementById( name ).getAttribute( 'data-category-id' ) );
    }, []);

    async function fetchFiltresByCategory( ) {
        let response;
        try {
            response = await fetchFiltresByCategoryRequest( '', categoryId );
            setFiltres( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    async function addFilter() {
        let response;
        try {
            response = await addFiltreRequest( categoryId, parentId, childId );
            console.log( response.data );
            response = await fetchFiltresByCategory( '', categoryId );
            setFiltres( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    const divStyle = {
        display: 'grid',
        gridTemplateColumns: 'repeat(2, 1fr)',
        gridGap: '10px'
    };

    const handleAdd = ( e ) => {
        addFilter();
    };

    return (
        <>
            <List
                categoryId={categoryId}
                filtres={filtres}
                setFiltres={setFiltres}
            />
            <h3>Ajouter</h3>
            <hr/>
            <div style={divStyle}>
                <div>
                    <RootSelect
                        parentId={parentId}
                        setParentId={setParentId}/>
                </div>
                <div>
                    <ChildSelect
                        parentId={parentId}
                        setChildId={setChildId}
                    />
                </div>
            </div>
            <div>
                <br/><br/>
                <button
                    className={'button button-primary'}
                    type={'button'}
                    onClick={( e ) => handleAdd( e )}>
                    Ajouter
                </button>
            </div>
        </>
    );
}

export default Category;
