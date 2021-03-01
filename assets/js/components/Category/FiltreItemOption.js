function FiltreItemOption( propos ) {
    const { value, key } = propos;
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
            key={key}
            value={value}
        >
            {value}
        </option>
    );
}

export default FiltreItemOption;
