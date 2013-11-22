<?php
	$selectedTabName = 'home';
	$banner='static/images/banners/home.jpg';
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
				<img class="portraits sample" src="static/images/samples/portraits.jpg" />
				<img class="travel sample" src="static/images/samples/travel.jpg" />
				<img class="nature sample" src="static/images/samples/nature.jpg" />
				<img class="happenstances sample" src="static/images/samples/happenstances.jpg" />
				<img class="food sample" src="static/images/samples/food.jpg" />
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
