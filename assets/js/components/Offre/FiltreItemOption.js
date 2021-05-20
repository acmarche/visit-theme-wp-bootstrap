function FiltreItemOption( propos ) {
    const { name, value } = propos;

    if ( null == value ) {
        return ( <option
            key={0}
            value={0}
        >
            Tout
        </option> );
    }

    return (
        <option
            key={value}
            value={value}
        >
            {name}
        </option>
    );
}

export default FiltreItemOption;
