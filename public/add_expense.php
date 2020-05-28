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

        <form  action="index.php">
            
            <fieldset class="form_add_expense">
                <legend> 
                    <h2>
                        Add Expense
                    </h2>   
                </legend>

                <div class="form_add_expense-price">
                    <label>Price:</label> 

                    <input type="text" placeholder="Item Price ?">  
                </div>

                <div class="form_add_expense-category">
                    <label>Category:</label> 

                    <select name="" id=""  size="4" class="form_add_expense-category-menu">
                            <option value="1">Food</option>
                            <option value="2">Drink</option>
                            <option value="3">Mobile</option>
                            <option value="3">Computer</option>
                            <option value="3">Other</option>
                    </select>
                </div>

                <div class="form_add_expense-comment">
                    <label>Comment:</label> 

                    <textarea id="" cols="20" rows="3" placeholder="Like Item Name,..."></textarea>
                </div>

                <div class="form_add_expense-date">
                    <label>Date:</label> 

                    <input type="date">  
                </div>


                <div class="form_add_expense-submit">
                    <input type="submit" value="add" class="form-sign_in-animated btn">  
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