import { fetchFiltresByCategoryRequest } from '../Filtre/service/filtre-service';
import FiltreItem from './FiltreItem';
import FiltreItemOption from './FiltreItemOption';

const {
    useState,
    useEffect
} = wp.element;

function FiltresComposant( propos ) {
    const [ filtres, setFiltres ] = useState([]);
    const { categoryId, setFiltreSelected } = propos;
    const { language } = propos;

    async function loadFiltres() {
        let response;
        try {
            console.log( categoryId );
            response = await fetchFiltresByCategoryRequest( '', categoryId, true );
            setFiltres( response.data );
        } catch ( e ) {
            console.log( e );
        }
        return null;
    }

    useEffect( () => {
        if ( 0 < categoryId ) {
            loadFiltres();
        }
    }, [ categoryId ]);

    /**
     * Si filtres = tout + un filtre
     */
    if ( 3 > filtres.length ) {
        return ( <></> );
    }

    const listItems = filtres.map( ( filtre ) => ( <FiltreItem
        value={filtre.nom}
        key={filtre.id}
        clef={filtre.id}
        setFiltreSelected={setFiltreSelected}
    /> ) );

    const listOptions = filtres.map( ( filtre ) => ( <FiltreItemOption
        name={filtre.nom}
        value={filtre.id}
        key={filtre.id}
    /> ) );

    function changeSelectedCategory( event ) {
        const filterSelected = event.target.value;
        setFiltreSelected( filterSelected );
    }

    return (
        <>
            <div className="d-md-none pr-12px border border-primary rounded-xs position-relative">
                <i className="fas fa-angle-down position-absolute top-0 bottom-0 right-0 mr-16px fs-big-1 text-primary py-5px"></i>
                <select
                    name="categories"
                    id="cat-select"
                    className="fs-short-3 ff-semibold rounded-xs"
                    onChange={changeSelectedCategory}>
                    value={listOptions}
                </select>
            </div>
            <ul className="cat-filters d-md-flex mw-648px flex-wrap justify-content-center align-items-center d-none">
                {listItems}
            </ul>
        </>
    );
}

export default FiltresComposant;
