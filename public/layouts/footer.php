        <footer >

<div class="row">
    <div class="col-1-of-2">
        <div class="footer__logo">
            
        </div>
    </div>
    <div class="col-1-of-2">
        <p class="footer__copyright">
            Built by <a href="MY_CV/my_cv.html" class="footer--link" target ="_blank">Khaled Mostafa</a> .
            Copyright &copy; 2020-<?php echo date("Y");?> by Khaled Mostafa . You are 100% allowed to use this webpage for both personal
            and commercial use, but NOT to claim it as your own design. A credit to the original author, Khaled
            Mostafa, is of course highly appreciated!
        </p>
    </div>
</div>

</footer>


</body>

</html>


<?php
    //5. close database connection
    mysqli_close($database->connection);
?>