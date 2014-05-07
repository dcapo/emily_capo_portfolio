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
		'portraits' => new Tab('portraits', 'gallery.php?category=portraits', 'portraits gallery'),
		'travel' => new Tab('travel', 'gallery.php?category=travel', 'travel gallery'),
		'nature' => new Tab('nature', 'gallery.php?category=nature', 'nature gallery'),
		'happenstances' => new Tab('happenstances', 'gallery.php?category=happenstances', 'happenstances gallery'),
		'blog' => new Tab('blog', 'http://ecapo.wordpress.com', 'blog'),
		'contact' => new Tab('contact', 'contact.php', 'contact')
	);
	
	if (isset($tabs[$selectedTabName])) {
		$tabs[$selectedTabName]->setIsSelected(true);
	}
	if (empty($banner)) {
		$banner = 'banners/contact.jpg';
	}
?>

<div id="header">
	<a style="display:block;" href="index.php"><img class="banner" src="<?php echo "$s3base/$banner"; ?>" border="1" /></a>
	<div class="navigation">
			<?php
				foreach ($tabs as $tab) {
					echo $tab->getHtml();
				}
			?>
	</div>
</div>