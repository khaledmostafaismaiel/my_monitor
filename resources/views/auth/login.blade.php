@extends('layouts.master_layout')
@section('content')
    <h1 class="message-welcome">
        Welcome to My Monitor
    </h1>

    <div class="form">
        <form  name="submit"  method="post"  action="/users/sign_in">
            {{csrf_field()}}

            <fieldset class="form-sign_in">
                <legend>
                    <h2>
                        Please, Sign in ...
                    </h2>
                </legend>

                <p>
                    User Name:
                    <input type="text" class="form-sign_in-user_name" name="user_name" value="" placeholder="Your E_mail" required>
                </p>


                <p>
                    Password: &nbsp;
                    <input type="password" class="form-sign_in-password" name="password" value="" placeholder="Your Password" required>
                </p>

                <div class="from-sign_in_btn">
                    <input name="submit_sign_in" type="submit" class="btn" value="sign in"/>
                </div>
            </fieldset>
        </form>
    </div>
@endsection
