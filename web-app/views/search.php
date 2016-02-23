<?php if($auctionsFound){?>

	<div class="row">
		<h4>Search results for "<?php echo $searchTerm?>":</h4>

		<?php foreach ($auctionData as $value) {?>
			<div class="col-md-4">
				<a href=<?php echo "/auction/".$value['id'] ?>>
					<div class="col-md-10 col-md-offset-1 category">
						
						<div class="title"><?php echo $value['name'] ?></div>

					</div>
				</a>
			</div>
		<?php }?>

	</div>

<?php }else{?>

	<h1>Sorry, no auction contains the word "<?php echo $searchTerm?>"</h1>

<?php }?>