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

        <div class="row">
        <div class="list-group pane">

            <div class="row list-group-item" style="border-color:transparent; background-color:transparent;">

                <div style="font-size: 20px;" class="pull-left">Sort by:</div>
                <form class="pull-left" style="margin-left:5px;" method="get" action="/search">

                    <?php if(isset($date) && $date == "0"){ ?>
                        <input type="hidden" name="date" value="1">

                    <?php }else{ ?>
                        <input type="hidden" name="date" value="0">

                    <?php }?>

                    <?php foreach($categories as $category){ ?>
                        <?php if(in_array($category->name, $selectedCategories)) { ?>

                            <input type="hidden" name=<?php echo '"'.($category->name).'"'; ?> value=<?php echo '"'.($category->name).'"'; ?>>

                        <?php }?>
                    <?php }?> 

                    <input type="hidden" name="search-bar" value=<?php echo '"'.$searchTerm.'"';?>>

                    <button type="submit" class="btn btn-default">Date <?php if(isset($date) && $date == "1"){ ?><span class="glyphicon glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span><?php }else{?><span class="glyphicon glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span><?php }?></button>
                </form>

                <form class="pull-left" style="margin-left:5px;" method="get" action="/search">
                    
                    <?php if(isset($price) && $price == "0"){ ?>
                        <input type="hidden" name="price" value="1">

                    <?php }else{ ?>
                        <input type="hidden" name="price" value="0">

                    <?php }?>

                    <?php foreach($categories as $category){ ?>
                        <?php if(in_array($category->name, $selectedCategories)) { ?>

                            <input type="hidden" name=<?php echo '"'.($category->name).'"'; ?> value=<?php echo '"'.($category->name).'"'; ?>>

                        <?php }?>
                    <?php }?> 

                    <input type="hidden" name="search-bar" value=<?php echo '"'.$searchTerm.'"';?>>

                    <button type="submit" class="btn btn-default">Price <?php if(isset($price) && $price == "1"){ ?><span class="glyphicon glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span><?php }else{?><span class="glyphicon glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span><?php }?></button>
                </form>

            </div>

            <?php foreach ($auctionData as $value) {?>

                <a href=<?php echo "/auction/".$value[3] ?> class="list-group-item">

                    <table style="width: 100%;">
                        <tr>

                            <?php if(strlen($value[5]) != 0 && file_exists($_SERVER['DOCUMENT_ROOT'].$value[5])) {?>
                                
                                <td class="col-md-2"> <img class="col-md-12" src=<?php echo($value[5])?>></td>

                            <?php }else{?>

                                <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td> <!-- Change path with the path on AWS -->

                            <?php }?> 

                            <td  class="col-md-8">

                                <h4><strong><?php echo $value[0]; ?></strong></h4>
                                <p><em><?php echo $value[1]; ?> </em></p>
                                <p>End date: <?php echo $value[2]; ?><br /></p>

                            </td>

                            <td style="text-align: center;" class="col-md-2">
                                <h4><strong><?php echo "£ ".intval($value[4])/100; ?></strong></h4>
                            </td>
                        </tr>
                    </table>

                </a><br>

            <?php }?>

        </div>
        </div>

    </div>

<?php }else{?>

    <h1>Sorry, no auction contains the word "<?php echo $searchTerm?>"</h1>

<?php }?>