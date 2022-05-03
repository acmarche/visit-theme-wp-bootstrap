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

    useEffect( () => {
        const name = 'filtres-box';
        setCategoryId( document.getElementById( name ).getAttribute( 'data-category-id' ) );
    }, []);

    useEffect( () => {
    }, [ parentId ]);

    const divStyle = {
        display: 'grid',
        gridTemplateColumns: 'repeat(2, 1fr)',
        gridGap: '10px'
    };

    return (
        <>
            <List
                categoryId={categoryId}
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
                    />
                </div>
            </div>
        </>
    );
}

export default Category;
