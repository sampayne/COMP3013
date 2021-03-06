
<div class="jumbotron">
    <div class="container">
	<h1>Welcome to BuyNow!</h1>
	<p class="lead">Welcome to BuyNow, where you can buy and sell anything!</p>
    <p class="lead"><a href="/login" class="btn btn-primary">Signup</a> <a href="/login" class="btn btn-primary">Login</a></p>
    </div>
</div>


<div class="container-fluid">
    <?php foreach(array_chunk($categories,3) as $category_pair): ?>
        <div class="row">
            <?php foreach($category_pair as $category):?>
                <div class="cat-card col-md-4">
        			<form method="get" action="/search">
        				<input type="hidden" name="<?= $category->name?>" value="<?= $category->name ?>" >
        				<input type="hidden" name="search-bar" value="">
        				<button type="submit" class="cat-button"><span class="<?= $category->icon_name ?>" aria-hidden="true"></span><br><br><?= $category->name ?></button>
        			</form>
                </div>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>
</div>