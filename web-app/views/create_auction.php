<div class="container">

    <div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h1 class="panel-title">Create New Auction</h1></div>
            <div class="panel-body">
                <form enctype="multipart/form-data" method="post" action="/auction">

                    <div class="form-group">
                        <label for="auction_name">Title/Name:</label>
                        <input type="text" class="form-control" id="auction_name" name="auction_name" placeholder="Auction Name...">
                    </div>

                    <div class="form-group">
                        <label for="auction_description">Description:</label>
                        <textarea class="form-control" id="auction_description" name="auction_description" placeholder="Description..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="auction_description">Starting Price:</label>
                        <div class="input-group">
                            <span class="input-group-addon">&pound;</span>
                            <input type="number" class="form-control" name="starting_price" placeholder="Starting Price...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="auction_description">Reserve Price (Optional):</label>
                        <div class="input-group">
                            <span class="input-group-addon">&pound;</span>
                            <input type="number" class="form-control" name="reserve_price" placeholder="Reserve Price...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="auction_description">End Date &amp; Time:</label><br>
                        <input type="text" class="form-control" id="end_date_time" name="end_date_time" placeholder="End Date & Time...">
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-title">Items</h1>
                        </div>
                        <ul id="item-list" class="list-group">

                            <!-- Items added via JS -->

                        </ul>
                        <div class="panel-footer">
                            <button id="add-new-item-button" class="btn btn-default pull-right" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success center-block btn-lg">List Auction</button>

                </form>
            </div>
        </div>
    </div>
</div>

</div>


<link rel="stylesheet" type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css" />

<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script src="/js/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>

<script>

    var number_of_items = 0;

    function addNewItem() {

            $("#item-list").append('<li class="list-group-item">' +
                                '<div class="form-group">' +
                                    '<label for="auction_name">Title/Name:</label>' +
                                    '<input type="text" class="form-control" name="items['+number_of_items+'][item_name]" placeholder="Item Name...">' +
                                '</div>'+
                                '<div class="form-group">'+
                                    '<label for="item_description">Description:</label>'+
                                    '<textarea class="form-control" name="items['+number_of_items+'][item_description]" placeholder="Item Description..."></textarea>'+
                                '</div>'+



                                '<div class="form-group">'+
                                    '<label for="item_category">Category:</label>'+
                                    '<select class="form-control" size="<?= count($item_categories) ?>" name="items['+number_of_items+'][item_category][]" multiple>'+

                                        <?php foreach($item_categories as $category): ?>
                                            '<option value="<?= $category->id ?>"><?= $category->name ?></option>'+
                                        <?php endforeach ?>

                                    '</select>'+
                                '</div>'+

                                '<div class="form-group">'+
                                    '<label for="auction_description">Image:</label>'+
                                    '<input class="form-control" name="item_image[]" type="file" accept="image/*">'+
                                '</div>'+


                            '</li>');

            number_of_items++;

    }

    $("#add-new-item-button").click(function(){
        addNewItem();
    });

    var date = new Date()

    date.setDate(date.getDate() + 1);

    $('#end_date_time').datetimepicker({step:5, format:'Y-m-d H:i:s', inline:true, startDate: date, minDate:date, defaultSelect: false});

    addNewItem();

</script>