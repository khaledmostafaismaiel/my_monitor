<form class="d-flex mt-3" role="search" action="/transactions" method="get">
    {{csrf_field()}}

    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
    <button class="btn btn-outline-info" type="submit">Search</button>
</form>