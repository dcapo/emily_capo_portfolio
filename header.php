<?php
	class Tab {
		public $name;
		public $href;
		public $class;
		public $isSelected;
		private $html;
		
		function __construct($name, $href, $class, $isSelected=false) {
			$this->name = $name;
			$this->href = $href;
			$this->isSelected = $isSelected;
			$this->class = $class;
			$this->setHtml();
		}
		
		public function setIsSelected($isSelected) {
			$this->isSelected = $isSelected;
			if ($isSelected) {
				$this->class .= " selected";
			} else {
				$this->class = str_replace($this->class, " selected", "");
			}
			$this->setHtml();
		}
		
		public function setHtml($html=null) {
			if ($html) {
				$this->html = $html;
			} else {
				$this->html = "<a href='$this->href'><div class='$this->class tab'>$this->name</div></a>";
			}
		}
		
		public function getHtml() {
			return $this->html;
		}
	}
	
	$tabs = array(
		'home' => new Tab('Home', 'index.php', 'home'),
		'portraits' => new Tab('Portraits', 'gallery.php?category=portraits', 'portraits gallery'),
		'travel' => new Tab('Travel', 'gallery.php?category=travel', 'travel gallery'),
		'nature' => new Tab('Nature', 'gallery.php?category=nature', 'nature gallery'),
		'happenstances' => new Tab('Happenstances', 'gallery.php?category=happenstances', 'happenstances gallery'),
		'food' => new Tab('Food, Glorious Food', 'gallery.php?category=food', 'food gallery'),
		'blog' => new Tab('Blog', 'http://ecapo.wordpress.com', 'blog'),
		'contact' => new Tab('Contact', 'contact.php', 'contact')
	);
	
	if (isset($tabs[$selectedTabName])) {
		$tabs[$selectedTabName]->setIsSelected(true);
	}
	if (empty($banner)) {
		$banner = 'static/images/banners/contact.jpg';
	}
?>

<div id="header">
	<img src="<?= $banner ?>" border="1" />
	<div class="navigation">
			<?php
				foreach ($tabs as $tab) {
					echo $tab->getHtml();
				}
			?>
	</div>
</div>