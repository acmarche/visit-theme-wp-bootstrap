function FiltreItem( propos ) {
    const { item, key } = propos;

    function handleClick( categoryId, categoryTitle ) {
        propos.setItemActive( categoryId );
        propos.setSelectedCategory( categoryId );
        propos.setSelectedCategoryTitle( categoryTitle );
        document.title = categoryTitle;
    }
    console.log( item );
    return (
        <>
            <li className="mx-16px position-relative">
                <input name="cat" id="cat-all" className="position-absolute top-0 bottom-0 left-0 right-0 w-100 h-100"
                    type="radio"
                    onChange={( ( ) => handleClick( key, item ) )}
                    value="all"/>
                <label htmlFor="cat-all"
                    className="py-4px px-8px fs-short-2 ff-semibold transition-color">{item}</label>
            </li>
        </>
    );
}

export default FiltreItem;
