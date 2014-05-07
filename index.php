<?php
	$selectedTabName = 'home';
	$banner='banners/home.jpg';
	$s3base = '//s3.amazonaws.com/emily_capo_portfolio/images';
?>

<html>
	<?php include('head.php'); ?>
	<body>
		<div id="content">
			<?php include('header.php'); ?>
		
			<div class="sample-container">
				<div class='home info-box'>
					Click the tabs above to view galleries or mouse over for samples.
				</div>
				<img class="portraits sample" src="<?php echo $s3base; ?>/samples/portraits.jpg" />
				<img class="travel sample" src="<?php echo $s3base; ?>/samples/travel.jpg" />
				<img class="nature sample" src="<?php echo $s3base; ?>/samples/nature.jpg" />
				<img class="happenstances sample" src="<?php echo $s3base; ?>/samples/happenstances.jpg" />
			</div>
		
			<?php include('footer.php'); ?>
		</div>

		<script type="text/javascript">
			(function() {
				$(".gallery.tab").on('mouseover', function(e) {
					var tab = $(e.target);
					var index = $(".gallery.tab").index(tab);
					$(".sample").eq(index).show();
				});
			
				$(".gallery.tab").on('mouseout', function(e) {
					var tab = $(e.target);
					var index = $(".gallery.tab").index(tab);
					$(".sample").eq(index).hide();
				});
			})();
		</script>
		
	</body>
</html>
