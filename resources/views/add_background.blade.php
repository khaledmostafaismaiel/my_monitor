@extends('layouts.master_layout')
@section('content')
<form action="/backgrounds" enctype="multipart/form-data" method="POST">
    {{csrf_field()}}
    <fieldset class="form-add_back_ground">
        <legend>
            <h2>
                Photo Upload...
            </h2>
        </legend>

        <input type="hidden" name="MAX_FILE_SIZE" value="<?php /*echo $max_file_size; */?>" />

        <p class="form-add_back_ground-choose_file">
            <input type="file" name="file_upload" />
        </p>

        <p  class="form-add_back_ground-caption">
            Caption:<textarea id="" cols="20" name="caption" rows="3" placeholder="Like,place..."></textarea>
        </p>

        <div class="form-add_back_ground-cancel_btn">
            <a href="/" class="btn">
                cancel
            </a>
        </div>
        <input type="submit" name="submit" value="+ add" class="form-add_back_ground-add_btn btn" />

    </fieldset>
</form>

@endsection
