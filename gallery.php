<?php
    require_once 'S3.php';
    $s3base = '//s3.amazonaws.com/emily_capo_portfolio/images';
    
	function getImageCount($category) {
	    $publicKey = getenv('S3_PUBLIC_KEY');
	    $privateKey = getenv('S3_PRIVATE_KEY');
	    if (!$publicKey || !$privateKey) {
	        $credentials = parse_ini_file('s3.ini');
	        $publicKey = $credentials['publicKey'];
	        $privateKey = $credentials['privateKey'];
        }
	    $credentials = parse_ini_file('s3.ini');
	    $s3 = new S3($publicKey, $privateKey);
		$images = $s3->getBucket("emily_capo_portfolio", "images/originals/$category/");
		return count($images) - 1;
	}

	function addCellAtIndex($cell, $i, &$leftColumn, &$rightColumn) {
		if ($i % 4 < 2) {
			$leftColumn .= $cell;
		} else {
			$rightColumn .= $cell;
		}
	}
	
	function getHtml($category, $page, $imageCount, $imagesPerPage, $firstIndex, $lastIndex) {
	    global $s3base;
		$galleryHtml = "<div id='gallery'>";
		$leftColumn = "<div class='left column'>";
		$rightColumn = "<div class='right column'>";
		for ($i = $firstIndex; $i <= $lastIndex; $i++) {
			addCellAtIndex("<div class='cell'><img src='$s3base/thumbnails/$category/$i.jpg' /></div>", $i, $leftColumn, $rightColumn);
		}
		if ($i % 2 == 1) {
			addCellAtIndex("<div class='cell'></div>", $i, $leftColumn, $rightColumn);
		}
		$leftColumn .= "</div>";
		$rightColumn .= "</div>";
		$viewport = "<div id='viewport'>".
	                    "<img src='$s3base/originals/$category/$firstIndex.jpg' />".
                        // "<img class='spinner' src='$s3base/spinner.gif' />".
		            "</div>";
		$galleryHtml .= $leftColumn . $viewport . $rightColumn . "</div>";
		
		$pagination = "";
		if ($imageCount > $imagesPerPage) {
			$previous = "<div class='previous paginator disabled'>< Prev Page</div>";
			$next = "<div class='next paginator disabled'>Next Page ></div>";
			if ($page > 0) {
				$prevPage = $page - 1;
				$previous = str_replace("disabled", "", "<a href='gallery.php?category=$category&page=$prevPage'>" . $previous . "</a>");
			}
			if ($imageCount > ($lastIndex + 1)) {
				$nextPage = $page + 1;
				$next = str_replace("disabled", "", "<a href='gallery.php?category=$category&page=$nextPage'>" . $next . "</a>");
			}
			$pagination = "<div class='pagination'>" . $previous . "<span class='paginator disabled'>|</span>" . $next . "</div>";
		}
		
		return $galleryHtml . $pagination;
	}
	
	$imagesPerPage = 24;
	$categories = array('portraits', 'travel', 'nature', 'happenstances');
	if (isset($_GET['category']) && in_array($_GET['category'], $categories)) {
		$selectedTabName = $_GET['category'];
		$banner="banners/$selectedTabName.jpg";
		
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
		<script type="text/javascript" src='static/spin.min.js'></script>
		<div id="content">
			<?php include('header.php'); ?>
			
			<?php echo $galleryHtml; ?>
			
		</div>
		
		<script type='text/javascript'>
			(function() {
			    var viewport = document.getElementById('viewport');
			    var spinner = new Spinner({
                    lines: 11,
                    length: 6,
                    width: 4,
                    radius: 10,
                    corners: 1,
                    rotate: 0,
                    direction: 1,
                    color: '#FFF',
                    speed: 1,
                    trail: 60,
                    shadow: false,
                    hwaccel: false,
                    className: 'spinner',
                    zIndex: 2e9,
                    top: '50%',
                    left: '50%'
                });
			    
			    var imageCount = $('.cell img').length;
			    var imagesPerPage = <?php echo $imagesPerPage; ?>
			    
			    // register center image click behavior
			    $("#viewport img").on("load", function(e) {
			        $(this).off('click');
			        spinner.stop();
			        
		            var src = $(this).attr("src");
			        var filename = src.replace(/^.*[\\\/]/, '');
			        var baseUrl = src.slice(0, src.indexOf(filename));

				    var imageIndex = parseInt(filename.substr(0, filename.indexOf('.')));
					if ((imageIndex % imagesPerPage) < (imageCount - 1)) {
					    $(this).on("click", function() {
					        spinner.spin(viewport);
					        $(this).attr("src", baseUrl + (imageIndex + 1) + filename.slice(filename.indexOf('.')));
				        });
				    } else if (!$('.next.paginator').hasClass('disabled')) {
				        $(this).on("click", function() {
				            $('.next.paginator').trigger('click');
			            });
			        }
		        });

                // register thumbnail image click behavior
				$(".cell img").on("click", function(e) {
				    var thumbnail = $(e.target);
					var filename = thumbnail.attr('src').replace(/^.*[\\\/]/, '');
					var image = $("#viewport img");
					if (image.attr("src").replace(/^.*[\\\/]/, '') !== filename) {
					    spinner.spin(viewport);
						image.attr("src", "<?php echo $s3base; ?>/originals/<?php echo $selectedTabName; ?>/" + filename);
					}
				});
			})();
		</script>
		
		<?php include('footer.php'); ?>
		
	</body>
</html>