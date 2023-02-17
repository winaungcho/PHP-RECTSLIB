<?php
/******
 * RECTSLIB CLASS
 *
 * [RECTSLIB] is a utility for the group of rectangles.
 * Lib help you to construct the rectangles in various logical operations such as Union, Intersect abd Subtract.
 * This lib is free for the educational use as long as maintain this header together with this lib.
 * Author: Win Aung Cho
 * Contact winaungcho@gmail.com
 * version 1.0
 * Date: 17-02-2023
 *
 ******/

require_once ("rectslib.php");
$img_width = 640;
$img_height = 480;
 
$img = imagecreatetruecolor($img_width, $img_height);

$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);
$grey = imagecolorallocate($img, 128, 128, 128);
$red   = imagecolorallocate($img, 255, 0, 0);
$green = imagecolorallocate($img, 0, 255, 0);
$blue  = imagecolorallocate($img, 0, 200, 250);
$orange = imagecolorallocate($img, 255, 200, 0);
$brown = imagecolorallocate($img, 220, 110, 0);
$magenta = imagecolorallocate($img, 220, 0, 220);
$indego = imagecolorallocate($img, 220, 110, 110);


$rectslib = new RectsLib();
$A = $rectslib->p1p2(10, 10, 300, 200, "A", $green);
$B = $rectslib->p1p2(40, 30, 200, 300, "B", $red);
$C = $rectslib->p1p2(150, 40, 400, 150, "C", $orange);
$D = $rectslib->p1p2(140, 320, 200, 400, "D", $blue);
$E = $rectslib->p1p2(80, 160, 250, 250, "E", $brown);
$rects = [$A, $B, $C, $D, $E];
echo $rectslib->showRects($rects);

$mode = 0;
if (isset($_GET["m"]))
	$mode = $_GET["m"];
	
$rectslib->fillRects($img, $rects);
$all = $rectslib->merge($rects, $grey);
$fname = "rectsorigin.png";
echo "<div style='font-size:30px;'>";
if ($mode == 0){
	echo "Original rectangles.";
	echo "\t<a href='?m=1'>NEXT</a>";
}
if ($mode == 1){
	$rectslib->fillRects($img, $all);
	$rectslib->drawRects($img, $all, $brown);
	$fname = "rectsuniversal.png";
	echo "Universal mesh for all rectangles.";
	echo "\t<a href='?m=2'>NEXT</a>";
}
if ($mode == 2){
	$uni = $rectslib->union([$A, $B], [$C, $D]);
	$rectslib->fillRects($img, $uni);
	$rectslib->drawRects($img, $uni, $indego);
	$edges = $rectslib->genEdges($uni);
	$rectslib->drawEdges($img, $edges, $white);
	$fname = "rectsunion.png";
	echo "Union of [A B] & [C D]";
	echo "\t<a href='?m=3'>NEXT</a>";
}

if ($mode == 3){
	$Intersect = $rectslib->intersect([$A, $B, $C, $D], [$B, $E]);
	$rectslib->fillRects($img, $Intersect);
	$rectslib->drawRects($img, $Intersect, $indego);
	$edges = $rectslib->genEdges($Intersect);
	$rectslib->drawEdges($img, $edges, $white);
	$fname = "rectsintersect.png";
	echo "Intersect of [A B C D] & [B E]";
	echo "\t<a href='?m=4'>NEXT</a>";
}
if ($mode == 4){
	$subt = $rectslib->subtract([$A, $B, $C, $D], [$B, $E]);
	$rectslib->fillRects($img, $subt);
	$rectslib->drawRects($img, $subt, $indego);
	$edges = $rectslib->genEdges($subt);
	$rectslib->drawEdges($img, $edges, $white);
	$fname = "rectssubtract.png";
	echo "Subtract [B E] from [A B C D]";
}


imagePng($img, './images/'.$fname);
imagedestroy($img);
echo "</div><br/>";
echo "<img src='images/$fname?u=".time()."'/>";;
/*
A-GREEN-{10,10:300,200}
B-RED-{40,30:200,300}
C-ORANGE-{150,40:400,150}
D-BLUE-{140,320:200,400}
E-BROWN-{80,160:250,250}
*/
?>
