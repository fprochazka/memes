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
	<style>
		body { text-align:center; }
		.container { margin-left: auto; margin-right: auto; *zoom: 1; width: 940px; }
		.container:before, .container:after { display: table; content: ""; }
		.container:after { clear: both; }
		.container .face { display:block; float:left; width: 130px; height: 130px; margin: 10px 10px 0 0; text-align:center; }
		.container a { display:block; width: 130px; height: 100px; }
		.container a img { max-height:100%; max-width:100%; }
	</style>
	<title>Rage faces</title>
</head>
<body>

	<div class="container"><?php foreach (glob(__DIR__ . '/faces/*') as $file): if(!is_image($file)) continue ?>
		<div class="face">
			<a href="faces/<?php e(basename($file)) ?>">
				<img src="faces/<?php e(basename($file)) ?>" alt="<?php e(basename($file)) ?>">
			</a>
			<?php name($file) ?>
		</div>
	<?php endforeach ?>
	</div>

</body>
</html>