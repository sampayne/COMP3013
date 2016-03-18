<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <form role="search" method="get" action="/search">
                <ul class="list-group" >

                    <?php foreach($categories as $category):

                        if(isset($selectedCategories) && in_array($category->name, $selectedCategories)) : ?>

                            <li class="list-group-item"><?php echo $category->name; ?><input class="pull-right" type="checkbox" name=<?php echo '"'.($category->name).'"'; ?> value=<?php echo '"'.($category->name).'"'; ?> checked></li>

                        <?php else:?>

                            <li class="list-group-item"><?php echo $category->name; ?><input class="pull-right" type="checkbox" name=<?php echo '"'.($category->name).'"'; ?> value=<?php echo '"'.($category->name).'"'; ?>></li>

                        <?php endif ?>

                    <?php endforeach ?>

                    <input type="hidden" name="search-bar" value=<?php echo '"'.$searchTerm.'"';?>>
                    <button type="submit" class="btn btn-primary btn-block">Filter Results</button>

                </ul>
            </form>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <h2 class="panel-title pull-left">Search results for "<?= $searchTerm ?>"</h2>

                    <?php if($auctionsFound):?>

                            <form method="get" class="pull-right" action="/search">

                                <?php if(isset($date) && $date == "0"): ?>
                                    <input type="hidden" name="date" value="1">

                                <?php else: ?>
                                    <input type="hidden" name="date" value="0">

                                <?php endif ?>

                                <?php foreach($categories as $category): ?>
                                    <?php if(in_array($category->name, $selectedCategories)): ?>
                                        <input type="hidden" name="<?= $category->name ?>" value="<?= $category->name ?>" >
                                    <?php endif ?>
                                <?php endforeach ?>

                                <input type="hidden" name="search-bar" value=" <?= $searchTerm ?>">

                                <button type="submit" class="btn btn-default">Date<?php if(isset($date) && $date == "1"): ?><span class="glyphicon glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span><?php else:?><span class="glyphicon glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span><?php endif ?></button>
                            </form>

                            <form class="pull-right" method="get" action="/search">

                                <?php if(isset($price) && $price == "0"): ?>
                                    <input type="hidden" name="price" value="1">

                                <?php else: ?>
                                    <input type="hidden" name="price" value="0">

                                <?php endif ?>

                                <?php foreach($categories as $category): ?>
                                    <?php if(in_array($category->name, $selectedCategories)): ?>

                                        <input type="hidden" name=<?php echo '"'.($category->name).'"'; ?> value=<?php echo '"'.($category->name).'"'?>>

                                    <?php endif ?>
                                <?php endforeach ?>

                                <input type="hidden" name="search-bar" value=<?php echo '"'.$searchTerm.'"';?>>

                                <button type="submit" class="btn btn-default">Price <?php if(isset($price) && $price == "1"): ?><span class="glyphicon glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span><?php else: ?><span class="glyphicon glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span><?php endif ?></button>
                            </form>
                        <?php endif ?>
                    <br>
                    <br>
                </div>
                <ul class="list-group">

                <?php if($auctionsFound):?>

                         <?php foreach ($auction_array as $auction) :?>

                            <li class="list-group-item">

                                <?php include('single_auction.php') ?>

                            </li>

                        <?php endforeach ?>


                    <?php else: ?>

                        <li class="list-group-item">
                            <div class="alert alert-info" role="alert">No results for "<?= $searchTerm ?>"</div>
                        </li>

                    <?php endif ?>

                </ul>
            </div>
        </div>
    </div>
</div>