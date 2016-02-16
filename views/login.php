<html>
    <body>
        <p>Login Form:</p>

        <?php if(isset($errors)) :?>

            <p>!!! <?= $errors ?> !!!</p>

        <?php endif ?>

        <form method="post" action="/login">
            <input type="email" name="email" />
            <input type="password" name="password" />

            <button type="submit">Login</button>
        </form>

        <p>Signup Form:</p>

            <?php if(isset($signup_errors)) :?>

                <p>!!! <?= $signup_errors ?> !!!</p>

            <?php endif ?>

        <form method="post" action="/signup">
            <input type="email" name="email" />
            <input type="password" name="password" />
            <input type="password" name="confirm_password" />

            <label>Register as Buyer:</label>
            <input type="hidden" name="buyer_account" value="0">
            <input type="checkbox" name="buyer_account" value="1">

            <label>Register as Seller:</label>
            <input type="hidden" name="seller_account" value="0">
            <input type="checkbox" name="seller_account" value="1">

            <button type="submit">Signup</button>
        </form>
    </body>
</html>