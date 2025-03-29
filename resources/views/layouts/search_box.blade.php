<form action="/expenses" method="get">
    {{csrf_field()}}
    <div class="search_box">
        <input class="search_box-text" type="text" name="search" placeholder="search">
        <img src="/images/search.png"  class="search_box-img">
        <input  class="search_box-btn"   type="submit" name="submit_search"  >
    </div>
</form>
