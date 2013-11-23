<?php 
	function getImageCount($category) {
		$count = 0; 
    if ($handle = @opendir(__DIR__ . "/static/images/originals/$category")) {
        while (($file = readdir($handle)) !== false){
            if (!in_array($file, array('.', '..', '.DS_Store'))) {
	            $count++;
						}
        }
    }
		return $count;
	}

	function addCellAtIndex($cell, $i, &$leftColumn, &$rightColumn) {
		if ($i % 4 < 2) {
			$leftColumn .= $cell;
		} else {
			$rightColumn .= $cell;
		}
	}
	
	function getHtml($category, $page, $imageCount, $imagesPerPage, $firstIndex, $lastIndex) {
		$galleryHtml = "<div id='gallery'>";
		$leftColumn = "<div class='left column'>";
		$rightColumn = "<div class='right column'>";
		for ($i = $firstIndex; $i <= $lastIndex; $i++) {
			addCellAtIndex("<div class='cell'><img src='static/images/thumbnails/$category/$i.jpg' /></div>", $i, $leftColumn, $rightColumn);
		}
		if ($i % 2 == 1) {
			addCellAtIndex("<div class='cell'></div>", $i, $leftColumn, $rightColumn);
		}
		$leftColumn .= "</div>";
		$rightColumn .= "</div>";
		$viewport = "<div class='viewport'><img src='static/images/originals/$category/$firstIndex.jpg' /></div>";
		$galleryHtml .= $leftColumn . $viewport . $rightColumn . "</div>";
		
		$pagination = "";
		if ($imageCount > $imagesPerPage) {
			$previous = "<div class='previous paginator disabled'>< Previous</div>";
			$next = "<div class='next paginator disabled'>Next ></div>";
			if ($page > 0) {
				$prevPage = $page - 1;
				$previous = str_replace("disabled", "", "<a href='gallery.php?category=$category&page=$prevPage'>" . $previous . "</a>");
			}
			if ($imageCount > ($lastIndex + 1)) {
				$nextPage = $page + 1;
				$next = str_replace("disabled", "", "<a href='gallery.php?category=$category&page=$nextPage'>" . $next . "</a>");
			}
			$pagination = "<div class='pagination'>" . $previous . $next . "</div>";
		}
		
		return $galleryHtml . $pagination;
	}
	
	$imagesPerPage = 24;
	$categories = array('portraits', 'travel', 'nature', 'happenstances', 'food');
	if (isset($_GET['category']) && in_array($_GET['category'], $categories)) {
		$selectedTabName = $_GET['category'];
		$banner="static/images/banners/$selectedTabName.jpg";
		
		$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? intval($_GET['page']) : 0;
		$imageCount = getImageCount($selectedTabName);
		if (!$imageCount || $imageCount < $page * $imagesPerPage) {
			$galleryHtml = "<p class='error'>Whoops! Invalid page selected.</p>";
		} else {
			$firstIndex = $page * $imagesPerPage;
			$lastIndex = min($firstIndex + $imagesPerPage - 1, $imageCount - 1);
			$galleryHtml = getHtml($selectedTabName, $page, $imageCount, $imagesPerPage, $firstIndex, $lastIndex);
		}
	} else {
		$galleryHtml = "<p class='error'>Whoops! Invalid category selected.</p>";
	}
?>

<html>
	<body>
		
		<?php include('head.php'); ?>
		<div id="content">
			<?php include('header.php'); ?>
			
			<?= $galleryHtml ?>
			
		</div>
		
		<script type='text/javascript'>
			(function() {
				
				var spinner = $("<div></div>").attr("id", "floatingCirclesG");
				for (var i = 1; i < 9; i++) {
					spinner.append($("<div></div>").attr("id", "frotateG_0" + i).addClass("f_circleG"));
				} 
				
				$(".cell img").on("click", function(e) {
					var filename = e.target.src.replace(/^.*[\\\/]/, '');
					var thumbnail = $(e.target);
					var image = $(".viewport img");
					
					thumbnail.parent().append(spinner);
					thumbnail.hide();
					image.on("load", function() {
						spinner.remove();
						thumbnail.show();
					});
					image.attr("src", "static/images/originals/<?= $selectedTabName ?>/" + filename);
				});
			})();
		</script>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>