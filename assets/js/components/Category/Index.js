import CategoryTitle from './CategoryTitle';

const {
    useState,
    useEffect
} = wp.element;

function Category() {
    const [ selectedCategory, setSelectedCategory ] = useState( 0 );
    const [ selectedCategoryTitle, setSelectedCategoryTitle ] = useState( '' );
    const name = 'app-category';

    const mainCategory = document.getElementById( name )
        .getAttribute( 'data-main-category-id' );
    const categoryTitle = document.getElementById( name )
        .getAttribute( 'data-site-name' );

    useEffect( () => {
        setSelectedCategory( mainCategory );
        setSelectedCategoryTitle( categoryTitle );
    }, []);

    return (
        <>
            <CategoryTitle title={selectedCategoryTitle}/>
        </>
    );
}

export default Category;
