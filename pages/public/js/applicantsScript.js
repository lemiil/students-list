$(document).ready(function () {
    let headers = $('th[data-sort]');
    let searchParams = new URLSearchParams(window.location.search);
    let currentSort = searchParams.get('sort');
    let currentSortField = searchParams.get('sortField');

    headers.each(function () {
        let field = $(this).attr('data-sort');
        if (field === currentSortField) {
            $(this).addClass(currentSort === 'desc' ? 'sort-desc' : 'sort-asc');
        }

        $(this).on('click', function () {
            let newSort = 'asc';
            if (currentSortField === field && currentSort === 'asc') {
                newSort = 'desc';
            }
            searchParams.set('sort', newSort);
            searchParams.set('sortField', field);
            window.location.search = searchParams.toString();
        });
    });

    let searchString = "{{ search }}";
    if (searchString) {
        $('td, th').each(function () {
            if ($(this).text().includes(searchString)) {
                $(this).addClass('highlight');
            }
        });
    }
});
