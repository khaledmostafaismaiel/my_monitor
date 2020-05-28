<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
         
        <link rel="stylesheet"  href= "stylesheets/css/expenses.css"  media="screen and (min-width:300px)" > 

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


        <div>
            <table class="expenses_table table table-striped table-hover table-responsive-sm">
                
                <thead>
                    <tr>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Options</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>20.00</td>
                        <td>Food</td>
                        <td>pepsi</td>
                        <td>28-May-2020</td>
                        <td>
                            <div class="row">
                                <div class="btn-action">
                                    <a href="" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="btn-action">
                                    <form action="" method="POST">
                                        <input type="checkbox" name="_method" value="DELETE">
                                        <input type="checkbox" name="expenseId"value="3">
                                        <button type="submit" class="btn btn-sm btn-danger delete-expense" title="Delete">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>20.00</td>
                        <td>Food</td>
                        <td>pepsi</td>
                        <td>28-May-2020</td>
                        <td>
                            <div class="row">
                                <div class="btn-action">
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="btn-action">
                                    <form action="#" method="POST">
                                        <input type="checkbox" name="_method" value="DELETE">
                                        <input type="checkbox" name="expenseId"value="3">
                                        <button type="submit" class="btn btn-sm btn-danger delete-expense" title="Delete">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <td>20.00</td>
                        <td>Food</td>
                        <td>pepsi</td>
                        <td>28-May-2020</td>
                        <td>
                            <div class="row">
                                <div class="btn-action">
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="btn-action">
                                    <form action="#" method="POST">
                                        <input type="checkbox" name="_method" value="DELETE">
                                        <input type="checkbox" name="expenseId"value="3">
                                        <button type="submit" class="btn btn-sm btn-danger delete-expense" title="Delete">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>



                    <tr>
                        <td>20.00</td>
                        <td>Food</td>
                        <td>pepsi</td>
                        <td>28-May-2020</td>
                        <td>
                            <div class="row">
                                <div class="btn-action">
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="btn-action">
                                    <form action="#" method="POST">
                                        <input type="checkbox" name="_method" value="DELETE">
                                        <input type="checkbox" name="expenseId"value="3">
                                        <button type="submit" class="btn btn-sm btn-danger delete-expense" title="Delete">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>




                    <tr>
                        <td>20.00</td>
                        <td>Food</td>
                        <td>pepsi</td>
                        <td>28-May-2020</td>
                        <td>
                            <div class="row">
                                <div class="btn-action">
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="btn-action">
                                    <form action="#" method="POST">
                                        <input type="checkbox" name="_method" value="DELETE">
                                        <input type="checkbox" name="expenseId"value="3">
                                        <button type="submit" class="btn btn-sm btn-danger delete-expense" title="Delete">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>


                    

                </tbody>
            </table>
        </div>


        <footer >

            <div class="row">
                <div class="col-1-of-2">
                    <div class="footer__logo">
                        
                    </div>
                </div>
                <div class="col-1-of-2">
                    <p class="footer__copyright">
                        Built by <a href="MY_CV/test.html" class="footer--link" target ="_blank">Khaled Mostafa</a> .
                        Copyright &copy; by Khaled Mostafa. You are 100% allowed to use this webpage for both personal
                        and commercial use, but NOT to claim it as your own design. A credit to the original author, Khaled
                        Mostafa, is of course highly appreciated!
                    </p>
                </div>
            </div>

        </footer>


    </body>

</html>