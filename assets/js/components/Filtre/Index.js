import { fetchFiltresByCategory } from './service/filtre-service';
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
    const [ filtres, setFiltres ] = useState([]);

    async function loadFiltresCategory() {
        let response;
        try {
            response = await fetchFiltresByCategory( language, categoryId );
            console.log( response.data );
            setFiltres( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        const name = 'filtres-box';
        setCategoryId( document.getElementById( name ).getAttribute( 'data-category-id' ) );
    }, []);

    useEffect( () => {
        if ( 0 < categoryId ) {
            loadFiltresCategory();
        }
    }, [ categoryId ]);

    useEffect( () => {
    }, [ parentId ]);

    const divStyle = {
        display: 'grid',
        gridTemplateColumns: 'repeat(2, 1fr)',
        gridGap: '10px'
    };

    return (
        <>
            <List filtres={filtres}/>
            <div style={divStyle}>
                <div>
                    <RootSelect
                        parentId={parentId}
                        setParentId={setParentId}/>
                </div>
                <div>
                    <ChildSelect
                        parentId={parentId}
                    />
                </div>
            </div>
        </>
    );
}

export default Category;
