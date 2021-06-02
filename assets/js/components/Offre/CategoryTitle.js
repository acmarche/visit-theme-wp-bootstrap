import { fetchCategory } from './service/categories-service';

const {
    useState,
    useEffect
} = wp.element;

function CategoryTitle( propos ) {
    const { categoryId } = propos;
    const [ category, setCategory ] = useState( null );

    async function loadCategory( ) {
        let response;
        try {
            response = await fetchCategory( categoryId );
            console.log( response.data );
            setCategory( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        if ( 0 < categoryId ) { loadCategory( ); }
    }, [ categoryId ]);

    if ( category && 0 < category.description.length && null != category.icone ) {
        console.log( category.icone );
        return <p className={'mb-3'}>{category.description}</p>;
    }
    return <></>;
}

export default CategoryTitle;
