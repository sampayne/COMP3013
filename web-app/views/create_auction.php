

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

                    <button type="submit" class="btn btn-default center-block btn-lg">List Auction</button>

                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css"/ >

<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script src="/js/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>

<script>

    function addNewItem() {$("#item-list").append('<li class="list-group-item">' +
                                '<div class="form-group">' +
                                    '<label for="auction_name">Title/Name:</label>' +
                                    '<input type="text" class="form-control" id="item_name" name="item_name[]" placeholder="Item Name...">' +
                                '</div>'+
                                '<div class="form-group">'+
                                    '<label for="auction_description">Description:</label>'+
                                    '<textarea class="form-control" id="item_description" name="item_description[]" placeholder="Item Description..."></textarea>'+
                                '</div>'+
                                '<div class="form-group">'+
                                    '<label for="auction_description">Image:</label>'+
                                    '<input class="form-control" id="item_image" name="item_image[]" type="file" accept="image/*">'+
                                '</div>'+
                            '</li>');
    }

    $("#add-new-item-button").click(function(){
        addNewItem();
    });

    $('#end_date_time').datetimepicker({step:5, format:'Y-m-d H:i:s', inline:true});

    addNewItem();

</script>