<?php
header("Content-type: text/css");

echo "#sce_emoji_button {
	margin: 0px;
	padding: 0 5px;
}\r\n";
echo "#sce_emoji_button img {
	height: 80%;
	width: auto;
	margin: -3px 0px 0px;
	padding: 0px;
}\r\n";

foreach(glob("../emojis/*.*") as $sce_emoji) {
	list($width, $height) = getimagesize($sce_emoji);
	$file_name = str_replace(array("../emojis/","_anim",".png"),array("","",""), $sce_emoji);
	if (strpos($file_name, "@") === false ) {

echo ".sce_emoji-".$file_name." {
	display:inline-block;
	height: ".$width."px;
	width: ".$width."px;
	background-image: url(\"".$sce_emoji."\");
	background-size: ".$width."px auto;
	animation: sce-".$height." ".round($height / $width / 25, 2)."s steps(".$height / $width.") infinite;
}\r\n";

	}
}