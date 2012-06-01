<?php
function e($s, $attr = FALSE) {
	echo htmlSpecialChars($s, $attr ? ENT_QUOTES : ENT_NOQUOTES);
}
function is_image($file) {
	return !is_dir($file) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'jpeg', 'png', 'gif'));
}
function name($file) {
	echo trim(preg_replace_callback('/(^|\\-)+(.)/', function ($match) { return ' '.strtoupper($match[2]); }, pathinfo($file, PATHINFO_FILENAME)));
}

?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="robots" content="index, follow, all">

	<title>Rage faces</title>

	<link href="bootstrap.min.css" type="text/css" rel="stylesheet" />
	<style>
		.container { margin-top: 40px; text-align:center; width:1000px; }
		.container .face { display:block; float:left; width: 130px; height: 130px; margin: 10px 10px 0 0; text-align:center; }
		.container a { display:block; width: 130px; height: 100px; }
		.container a img { max-height:100%; max-width:100%; }
		.search-query { width: 600px; height:30px; font-size: 20px; text-align:center; }
	</style>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="bootstrap.min.js"></script>

	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-12182518-8']);
	  _gaq.push(['_setDomainName', 'hosiplan.com']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
</head>
<body>



	<div class="container">

		<form class="well form-search">
			<input type="text" id="searchQuery" class="input-medium search-query" placeholder="type please.." />
		</form>

	<?php foreach (glob(__DIR__ . '/faces/*') as $file): if(!is_image($file)) continue ?>
		<div class="face">
			<a href="faces/<?php e(basename($file)) ?>">
				<img src="faces/<?php e(basename($file)) ?>" alt="<?php e(basename($file)) ?>">
			</a>
			<?php name($file) ?>
		</div>
	<?php endforeach ?>
	</div>

	<script>
		$(function () {
			var faces = {
				displayRandom: 21,
				carouselDelay: 2000,
				faces: $('.face'),
				input: $('#searchQuery'),
				init: function () {
					this.faces.hide();
					this.input.on('keyup', $.proxy(this.search, this));
					this.input.focus();
					this.createIndex();
					this.carouselPlay();
				},
				index: {},
				createIndex: function () {
					var me = this;
					this.faces.each(function () {
						me.index[$.trim($(this).text()).toLowerCase()] = $(this);
					});
				},
				search: function () {
					var searching = $.trim(this.input.val()).toLowerCase();
					if (searching == "") {
						this.faces.hide();
						this.carouselPlay();

					} else {
						this.carouselStop();

						$.each(this.index, function (key, val) {
							if (key.indexOf(searching) != -1) {
								val.show();
							} else {
								val.hide();
							}
						});
					}
				},
				carouselTimer: null,
				carouselPlay: function () {
					if (this.carouselTimer) {
						this.carouselStop();
					}

					this.carouselTimer = setInterval($.proxy(this.carousel, this), this.carouselDelay);
					this.randomFaces(this.displayRandom).show();
				},
				carouselStop: function () {
					clearInterval(this.carouselTimer);
					this.carouselTimer = false;
					this.faces.stop();
					this.faces.hide();
				},
				carousel: function () {
					var hidden = this.randomHidden();
					var visible = this.randomVisible();

					visible.before(hidden);
					visible.fadeOut('slow', $.proxy(function () {
						hidden.fadeIn('slow');
					}, this));
				},
				randomVisible: function (){
					return this.randomFaces(1, this.faces.find(':visible')).closest('.face');
				},
				randomHidden: function (){
					return this.randomFaces(1, this.faces.find(':hidden')).closest('.face');
				},
				randomFaces: function (num, faces) {
					if (typeof num === 'undefined') {
						num = 1;
					}
					if (typeof faces === 'undefined') {
						faces = this.faces;
					}

					var max = faces.length - num;
					var start = Math.floor(Math.random() * max);
					return faces.slice(start, num + start);
				}
			};

			faces.init();
		});
	</script>

</body>
</html>