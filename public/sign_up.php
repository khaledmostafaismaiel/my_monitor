<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
         
        <link rel="stylesheet"  href= "stylesheets/css/style.css"  media="screen and (min-width:1200px)" > 

        <link rel="shortcut icon" type="image/png" href="stylesheets/images/favicon.png">


        <title>My Monitor</title>

        <!-- <script>alert("Welcome!");</script> -->
    
    </head>


    <body>

        <div class="bg-video">
            <video class="bg-video__content" autoplay muted loop>
                <source src="stylesheets/images/video.mp4" type="video/mp4">
                <source src="stylesheets/images/video.webm" type="video/webm">
                Your browser is not supported!
            </video>
        </div>

        <header>
            <a href="index.php" class="">
                <img src="stylesheets/images/favicon.png" class="header-image" alt="faveicon">
            </a>
            
            <div class="navigation">

                <input type="checkbox" class="navigation__checkbox" id="navi-toggle">
                <label for="navi-toggle" class="navigation__button">
                    <span class="navigation__icon">Menu</span>
                </label>
    
    
                <div class="navigation__background">&nbsp;
                    
                </div>
    
                <nav class="navigation__nav">
                    <ul class="navigation__list">
                        <li class="navigation__item"><a href="add_expense.php" class="navigation__link">Add Expense</a></li>
                        <li class="navigation__item"><a href="expenses.php" class="navigation__link">Expenses</a></li>
                        <li class="navigation__item"><a href="sign_in.php" class="navigation__link">Sign Out</a></li>
                    </ul>
                </nav>
            </div>


        </header>

        <form  action= "index.php" method="post">

                <fieldset class="form-sign_up">
                    <legend> 
                        <h2>
                            Please, sign up first...
                        </h2>   
                    </legend>




                    <div class="form-sign_up-first_name">
                        <label for="first_name" >
                            First Name:
                        </label>
                        <input id="first_name" type="text" value=""  placeholder="Enter Your First Name">
                    </div>
                    



                    <div class="form-sign_up-second_name">
                        <label for="second_name" >
                            Second Name:
                        </label>
                        <input id = "second_name" type="text"  value=""  placeholder="Enter Your Second Name">

                    </div>





                    <div class="form-sign_up-email">
                        <label for="email" >
                                Email:        
                        </label>
                        <input id="email" type="text" name="email" value=""  placeholder="Enter Your E_mail">
                    </div>





                    <div class="form-sign_up-password">
                        <label for="password" >
                            Password:
                        </label>
                        <input id="password" type="password"  name="password" value=""  placeholder="Enter Your Password">
                    </div>

                    

                    <div class="form-sign_up-confirm_password">
                        <label for="confirm_password" >
                            Confirm Password:
                        </label>
                        <input id="confirm_password" type="password"  name="password" value="" placeholder="Confirm Your Password">
                    </div>



                    <div class="form-sign_up-not_robot">
                        <label for="not_robot" >
                            I'm not robot.
                        </label>
                        <input id="not_robot" type="checkbox">
                    </div>





                    <div class="form-sign_up-terms_of_conditions">
                        <label for="terms_of_conditions" >
                            <span>I agree with all terms of conditions.</span>
                        </label>
                        <input id="terms_of_conditions" type="checkbox">
                    </div>


                    

                    <div class="form-sign_up-submit">
                        <input type="submit" class="btn" value="sign up"/>
                    </div>  
    
    
                </fieldset>
            </form>

        <footer >

            <div class="row">
                <div class="col-1-of-2">
                    <div class="footer__logo">
                        
                    </div>
                </div>
                <div class="col-1-of-2">
                    <p class="footer__copyright">
                        Built by <a href="MY_CV/test.php" class="footer--link" target ="_blank">Khaled Mostafa</a> .
                        Copyright &copy; by Khaled Mostafa. You are 100% allowed to use this webpage for both personal
                        and commercial use, but NOT to claim it as your own design. A credit to the original author, Khaled
                        Mostafa, is of course highly appreciated!
                    </p>
                </div>
            </div>

        </footer>


    </body>

</html>