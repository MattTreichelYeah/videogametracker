// Be able to search for Pokemon --> Find Pokémon. Doesn't allow you to search with accents though.
// Not really sure why 'html' vs. 'string' part of search. String gives console names.

$.fn.DataTable.ext.type.search.html = function (data) {
    return ! data ? '' :
        (typeof data === 'string' ?
            data.replace( /é/g, 'e' )
                .replace( /ü/g, 'u' )
                .replace( /ñ/g, 'n' ) : data);
};