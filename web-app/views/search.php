<?php if($auctionsFound){?>

    <div class="container col-md-2 categoryFilter">
        <form role="search" method="get" action="/search">
            <ul class="list-group" >

                <?php foreach($categories as $category){
                    if(in_array($category->name, $selectedCategories)) { ?>

                        <li class="list-group-item"><?php echo $category->name; ?><input class="pull-right" type="checkbox" name=<?php echo '"'.($category->name).'"'; ?> value=<?php echo '"'.($category->name).'"'; ?> checked></li>

                    <?php }else{?>

                        <li class="list-group-item"><?php echo $category->name; ?><input class="pull-right" type="checkbox" name=<?php echo '"'.($category->name).'"'; ?> value=<?php echo '"'.($category->name).'"'; ?>></li>

                <?php }}?>

                <input type="hidden" name="search-bar" value=<?php echo '"'.$searchTerm.'"';?>>
                <button type="submit" class="btn btn-primary btn-block">Filter Results</button>

            </ul>
        </form>
    </div>

	<div class="row">
        <?php if($searchTerm != "") {?>

            <h4>Search results for "<?php echo $searchTerm?>":</h4>

        <?php }?>

        <div class="list-group pane">

    		<?php foreach ($auctionData as $value) {?>

                <a href=<?php echo "/auction/".$value[3] ?> class="list-group-item">

                    <table>
                        <tr>
                            <!-- <td class="col-md-2"> <img class="col-md-12" src="<?= $value[4]?>"></td> -->
                             <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>

                            <td  class="col-md-8">

                                <h4><strong><?php echo $value[0]; ?></strong></h4>
                                <p><em><?php echo $value[1]; ?> </em></p>
                                <p>End date: <?php echo $value[2]; ?><br /></p>

                            </td>
                        </tr>
                    </table>

                </a><br>

    		<?php }?>

        </div>

	</div>

<?php }else{?>

	<h1>Sorry, no auction contains the word "<?php echo $searchTerm?>"</h1>

<?php }?>