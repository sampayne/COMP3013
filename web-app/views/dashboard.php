<html>

    <body>

        <div>
            <p>This is the dashboard for user: <?= $user->email ?> id: <?= $user->id ?></p>
            <p>User is a buyer: <?= $user->buyer_role_id ?> </p>
            <p>User is a seller: <?= $user->seller_role_id ?> </p>

        </div>

    </body>

</html>