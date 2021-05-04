import { fetchCategory } from './service/categories-service';

const {
    useState,
    useEffect
} = wp.element;

function CategoryTitle( propos ) {
    const { categoryId } = propos;
    const [ category, setCategory ] = useState( null );

    async function loadCategory( ) {
        console.log( 'loading cat', categoryId );

        let response;
        try {
            console.log( categoryId );
            response = await fetchCategory( categoryId );
            setCategory( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        if ( 0 < categoryId ) { loadCategory( ); }
    }, [ categoryId ]);

    if ( category && 0 < category.description.length ) {
        return <p>{category.description}</p>;
    }
    return <></>;
}

export default CategoryTitle;
