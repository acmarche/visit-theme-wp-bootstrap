import FiltresComposant from './FiltresComposant';

const {
    useState,
    useEffect
} = wp.element;

function Category() {
    const [ referenceHades, setReferenceHades ] = useState( '' );

    const name = 'app-category';

    useEffect( () => {
        setReferenceHades( document.getElementById( name ).getAttribute( 'data-main-reference-hades' ) );
    }, [ ]);

    return (
        <>
            <FiltresComposant referenceHades={referenceHades}/>
        </>
    );
}

export default Category;
