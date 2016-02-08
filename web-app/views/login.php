<html>

    <body>

        <?php if(isset($errors)) :?>

            <p><?= $errors ?></p>

        <?php endif ?>

        <p>Login Form:</p>
        <form method="post" action="/login">
            <input type="email" name="email" />
            <input type="password" name="password" />
            <button type="submit">Login</button>

        </form>
        <p>Signup Form:</p>
        <form method="post" action="/signup">
            <input type="email" name="email" />
            <input type="password" name="password" />
            <button type="submit">Signup</button>
        </form>
    </body>
</html>