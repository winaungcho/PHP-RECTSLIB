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
	
class RectsLib {
    function ___construct(){
        $this->X0 = 10;
		$this->Y0 = 10;
		$this->mesh = [];
    }

	function point($x, $y) {
		return [
			x => $x,
			y => $y
		];
	}
	
	function p1p2($x1, $y1, $x2, $y2, $name = "result", $color = "Grey") {
		return [
			name => $name,
			color => $color,
			s => "in",
			p1 => $this->point($x1, $y1),
			p2 => $this->point($x2, $y2)
		];
	}
	
	function samePoint($a, $b) {
		return ($a[x] == $b[x]) && ($a[y] == $b[y]);
	}

	function sameEdge($e1, $e2) {
		$r = (($this->samePoint($e1[p1], $e2[p1]) && $this->samePoint($e1[p2], $e2[p2])) || 
			($this->samePoint($e1[p2], $e2[p1]) && $this->samePoint($e1[p1], $e2[p2])) ||
			($this->samePoint($e1[p1], $e2[p2]) && $this->samePoint($e1[p2], $e2[p1])));
		return $r;
	}

	function containEdge($edges, $e) {
		$n = count($edges);
		for($i = 0; $i < $n; $i++) {
			$edge = $edges[$i];
			if($this->sameEdge($edge, $e)) return $i;
		}
		return -1;
	}

	function validRect($A) {
		return $A[p2][x] > $A[p1][x] && $A[p2][y] > $A[p1][y];
	}

	function innerRect($rects, $r) {
		if(!is_array($rects)) $rects = [rects];
		$n = count($rects);
		for($i = 0; $i < $n; $i++) {
			$rect = $rects[$i];
			if(($r[p1][x] >= $rect[p1][x] && $r[p1][x] <= $rect[p2][x]) && 
				($r[p2][x] >= $rect[p1][x] && $r[p2][x] <= $rect[p2][x]) && 
					($r[p1][y] >= $rect[p1][y] && $r[p1][y] <= $rect[p2][y]) && 
						($r[p2][y] >= $rect[p1][y] && $r[p2][y] <= $rect[p2][y]))
							return true;
		}
		return false;
	}
	
	function merge($rects, $c) {
		$x = [];
		$y = [];
		$mesh = [];

		foreach($rects as $rect) {
			$x[] = $rect[p1][x];
			$x[] = $rect[p2][x];
			$y[] = $rect[p1][y];
			$y[] = $rect[p2][y];
		};
		
		sort($x);
		sort($y);
		$n = count($x);
		
		for($i = 0; $i < $n - 1; $i++) {
			for($j = 0; $j < $n - 1; $j++) {
				$r = $this->p1p2($x[$j], $y[$i], $x[$j + 1], $y[$i + 1], "result",$c);
				$r[s] = "in";
				$mesh[] = $r;
			}
		}
		$this->mesh = $mesh;
		return $mesh;
	}
	
	function union($rects, $rs) {
		$ans = [];
		foreach($this->mesh as $rect) {
			if($this->innerRect($rects, $rect) || $this->innerRect($rs, $rect)){
				$rect[s] = "in";
				$ans[] = $rect;
			}
		};
		return $ans;
	}

	function subtract($rects, $rs) {
		$n = count($this->mesh);
		$ans = [];
		for($i = 0; $i < $n; $i++) {
			$rect = $this->mesh[$i];
			if($this->innerRect($rects, $rect)){
				$rect[s] = "in";
				if($this->innerRect($rs, $rect)){
					$rect[s] = "out";
				}
				$ans[] = $rect;
			}
		};
		return $ans;
	}

	function intersect($rects, $rs) {
		$n = count($this->mesh);
		$ans = [];
		for($i = 0; $i < $n; $i++) {
			$rect = $this->mesh[$i];
			if($this->innerRect($rs, $rect)){
				if($this->innerRect($rects, $rect)){
					$rect[s] = "in";
					$ans[] = $rect;
				}
			}
		};
		return $ans;
	}

	function edgesRect($rect) {
		$es = [];
		$es[] = $this->p1p2($rect[p1][x], $rect[p1][y], $rect[p1][x], $rect[p2][y]);
		$es[] = $this->p1p2($rect[p1][x], $rect[p2][y], $rect[p2][x], $rect[p2][y]);
		$es[] = $this->p1p2($rect[p2][x], $rect[p2][y], $rect[p2][x], $rect[p1][y]);
		$es[] = $this->p1p2($rect[p2][x], $rect[p1][y], $rect[p1][x], $rect[p1][y]);
		return $es;
	}

	function genEdges($rects) {
		$edges = [];
		
		foreach($rects as $rect) {
			if($rect[s] == 'in') {
				$es = $this->edgesRect($rect);
				foreach($es as $ed) {
					//echo $ed[p1][x].",".$ed[p1][y].",".$ed[p2][x].",".$ed[p2][y];
					$index = $this->containEdge($edges, $ed);
					if($index >= 0) {
						array_splice($edges, $index, 1);
					
					} else {
						$edges[] = $ed;
					}
				};
			}
		};
		return $edges;
	}

	function drawEdges($ctx, $edges, $c) {
		if(!is_array($edges)) $edges = [$edges];
		foreach($edges as $edge) {
			imagesetthickness($ctx, 8);
			imageline($ctx, $edge[p1][x] + $this->X0, $edge[p1][y] + $this->Y0,
			    $edge[p2][x] + $this->X0, $edge[p2][y] + $this->Y0, $c);
		};
	}

	function drawRects($ctx, $rects, $c) {
		if(!is_array($rects)) $rects = [$rects];
		foreach($rects as $rect) {
			if(!$rect[s] || $rect[s] == 'in') {
				imagesetthickness($ctx, 2);
				imagerectangle($ctx, $rect[p1][x] + $this->X0, $rect[p1][y] + $this->Y0, $rect[p2][x] + $this->X0, $rect[p2][y] + $this->Y0, $c);
			}
		};
	}

	function fillRects($ctx, $rects) {
		if(!is_array($rects)) $rects = [$rects];
		foreach($rects as $rect) {
			if(!$rect[s] || $rect[s] == 'in') {
			    $c = $rect[color];
				imagesetthickness($ctx, 2);
				imagefilledrectangle($ctx, $rect[p1][x] + $this->X0, $rect[p1][y] + $this->Y0, $rect[p2][x] + $this->X0, $rect[p2][y] + $this->Y0, $c);
			}
		};
	}

	function showRects($rects) {
		$html = "";
		if(!is_array($rects)) $rects = [$rects];
		foreach($rects as $rect) {
			$html .= "<h3>" . $rect[name] . "-" . $rect[color] . "-" . "{" . $rect[p1][x] . "," . $rect[p1][y] . ":" . $rect[p2][x] . "," . $rect[p2][y] . "}" . "</h3>";
			//html .= "<br/>";
		};
		
		return $html;
	}
	
}

?>
