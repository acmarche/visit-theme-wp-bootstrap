function FiltreItemOption( propos ) {
    const { value, clef } = propos;
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
            key={clef}
            value={value}
        >
            {value}
        </option>
    );
}

export default FiltreItemOption;
