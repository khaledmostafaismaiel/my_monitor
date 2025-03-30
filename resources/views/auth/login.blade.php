@extends('layouts.master_layout')
@section('content')
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
            <h1 class="text-center mb-3">Welcome to My Monitor</h1>
            <form name="submit" method="post" action="/users/sign_in">
                {{ csrf_field() }}
                
                <fieldset>
                    <legend class="text-center mb-3"><h2>Please, Sign in ...</h2></legend>
                    
                    <div class="mb-3">
                        <label for="user_name" class="form-label">User Name:</label>
                        <input type="text" id="user_name" class="form-control" name="user_name" placeholder="Your E-mail" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" class="form-control" name="password" placeholder="Your Password" required>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" name="submit_sign_in" class="btn btn-primary w-100">Sign In</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
