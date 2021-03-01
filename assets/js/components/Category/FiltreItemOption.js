function FiltreItemOption( propos ) {
    const { item, key } = propos;
    if ( null == item ) {
        return ( <option
            key={0}
            value={0}
        >
            Tout
        </option> );
    }

    return (
        <option
            key={key}
            value={key}
        >
            {item}
        </option>
    );
}

export default FiltreItemOption;
