<?php
error_reporting(E_ERROR | E_PARSE);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<title>Test search</title>
<!--<meta http-equiv="x-ua-compatible" content="IE=11">-->
<meta http-equiv="x-ua-compatible" content="IE=edge">
<link rel="icon" href="favicon.ico">
</head>
<body>
<style>
/*ul {
 list-style-type: disc;
 list-style-position: inside;
}*/
li {
   list-style: none;
	width:intrinsic;
}
</style>
<form action="" method="GET">
<table width="400" border="0">
<tr><td colspan="2">All ROUTES WITHOUT PARAMETERS IN LINK</td></tr>
<tr><td>INITIAL NODE</td><td><input type="text" name="nodi" value="<?=$_REQUEST['nodi']?>" size="6" style="text-align:right;"></td></tr>
<tr><td>FINAL NODE</td><td><input type="text" name="nodf" value="<?=$_REQUEST['nodf']?>" size="6" style="text-align:right;"></td></tr>
<tr><td>VIEW NODE PERSPECTIVE</td><td><input type="text" name="node" value="<?=$_REQUEST['node']?>" size="6" style="text-align:right;"></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="search"></td></tr>
</table>
</form>
<?php
//permutations
function perkn($arr, $k) {
    for ($i = 0;$i < count($arr);$i++) {
        $arr_init[$i][0] = $arr[$i];
    }
    $cnt = 1;
    //echo "<pre>".print_r($arr_init,1)."</pre>";
    while ($cnt < $k) {
        for ($i = 0;$i < count($arr_init);$i++) {
            for ($j = 0;$j < count($arr);$j++) {
                if (array_search($arr[$j], $arr_init[$i]) === false) {
                    $arr_perm[] = $arr_init[$i];
                    array_push($arr_perm[count($arr_perm) - 1], $arr[$j]);
                }
            }
        }
        $arr_init = $arr_perm;
        unset($arr_perm);
        $cnt++;
    }
    return $arr_init;
}
//permutations
function per22($arr) {
    $arr_init[] = array($arr[0],$arr[1]);
	$arr_init[] = array($arr[1],$arr[0]);
    return $arr_init;
}
//combinations
function combkn($arr, $k) {
    for ($i = 0;$i < count($arr);$i++) {
        $arr_init[$i][0] = $arr[$i];
    }
    $cnt = 1;
    while ($cnt < $k) {
        for ($i = 0;$i < count($arr_init);$i++) {
            for ($j = 0;$j < count($arr);$j++) {
                if (array_search($arr[$j], $arr_init[$i]) === false) {
                    $arr_perm[] = $arr_init[$i];
                    array_push($arr_perm[count($arr_perm) - 1], $arr[$j]);
                }
            }
        }
        $arr_init = $arr_perm;
        unset($arr_perm);
        $cnt++;
    }
    for ($i = 0;$i < count($arr_init);$i++) {
        sort($arr_init[$i]);
        $arr_pu[$i] = implode(",", $arr_init[$i]);
    }
    $arr_au = array_values(array_unique($arr_pu));
    unset($arr_init);
    for ($i = 0;$i < count($arr_au);$i++) {
        $arr_init[$i] = explode(",", $arr_au[$i]);
    }
    return $arr_init;
}
function nodes($arr_graph){
	$arr_ret = array();
	for($i=0;$i<count($arr_graph);$i++){
		for($j=0;$j<count($arr_graph[$i]);$j++){
			array_push($arr_ret,$arr_graph[$i][$j]);
		}
	}
	$arr_ret = array_values(array_unique($arr_ret));
	return $arr_ret;
}
function intersect($arr_graph){
	for($i=0;$i<count($arr_graph);$i++){
		for($j=0;$j<count($arr_graph[$i]);$j++){
			if(is_null($arr_inter[$arr_graph[$i][$j]])){
				$arr_inter[$arr_graph[$i][$j]] = 1;
			}
			else{
				$arr_inter[$arr_graph[$i][$j]]++;
			}
		}
	}
	$arr_ret = array();
	foreach($arr_inter as $key => $value){
		if($value > 1){
			array_push($arr_ret,$key);
		}
	}
	return $arr_ret;
}
function roads($arr_graph,$arr_non = null){
	for($j=0;$j<count($arr_graph);$j++){
		sort($arr_graph[$j]);
		$arr_graph[$j] = ",".implode(",",$arr_graph[$j]).",";
	}	
	$arr_graph = array_values(array_unique($arr_graph));
	if($arr_non){
		for($j=0;$j<count($arr_non);$j++){
			$arr_non[$j] = ",".implode(",",$arr_non[$j]).",";
		}	
		$arr_non = array_values(array_unique($arr_non));
	}
	for($i=0;$i<count($arr_graph);$i++){
		$arr_graph[$i] = explode(",",substr($arr_graph[$i],1,-1));
	}
	$arr_int = intersect($arr_graph);
	//punem rutele care au ambele capete intersectii
	for($i=0;$i<count($arr_graph);$i++){
		if(array_search($arr_graph[$i][0],$arr_int) !== false && array_search($arr_graph[$i][1],$arr_int) !== false){
			$arr_ri[] = $arr_graph[$i];
		}
	}
	for($i=0;$i<count($arr_ri);$i++){
		$arr_perm = per22($arr_ri[$i]);
		for($j=0;$j<count($arr_perm);$j++){
			$arr_brs[] = ",".implode(",",$arr_perm[$j]).",";
		}
	}
	if($arr_non){
		$arr_brs = array_values(array_diff($arr_brs,$arr_non));
	}
	for($j=0;$j<count($arr_brs);$j++){
		$v_br = explode(",",substr($arr_brs[$j],1,-1));
		if(is_null($arr_cs[$v_br[0]])){
			$arr_cs[$v_br[0]][0] = $arr_brs[$j];
		}
		else{
			$arr_cs[$v_br[0]][] = $arr_brs[$j];
		}
		if(is_null($arr_cd[$v_br[1]])){
			$arr_cd[$v_br[1]][0] = $arr_brs[$j];
		}
		else{
			$arr_cd[$v_br[1]][] = $arr_brs[$j];
		}
	}
	$cnt = 1;
	$arr_init = $arr_brs;
	//facem toate comparararile de intersectii si crestem rutele din ele
	$arr_ret = $arr_init;
	//while($cnt <= ceil(count($arr_init)/2)-1){
	//numaram de cate ori apar capetele de interesectii si scadem 1 pentru fiecare mai mare de 2
	/*$arr_cpv = array();
	for($i=0;$i<count($arr_ri);$i++){
		$arr_cpv = array_merge($arr_cpv,$arr_ri[$i]);
	}
	$arr_cv = array_values(array_count_values($arr_cpv));*/
	//$nps = 0;
	/*for($i=0;$i<count($arr_cv);$i++){
		if($arr_cv[$i]>2){
			$nps += 1;//$arr_cv[$i]-2;
		}
	}*/
	//while($cnt <= (count($arr_ri)-1-$nps)){
	while($cnt <= (count($arr_ri)-1)){	
		for($i=0;$i<count($arr_ret);$i++){
			$v_ln = explode(",",substr($arr_ret[$i],1,-1));
			for($j=0;$j<count($arr_cd[$v_ln[0]]);$j++){
				$v_br = explode(",",substr($arr_cd[$v_ln[0]][$j],1,-1));
				if(array_search($v_br[0],$v_ln) === false){
					$arr_ret1[] = ",".$v_br[0].$arr_ret[$i];
				}
				unset($v_br);
			}
			for($j=0;$j<count($arr_cs[$v_ln[count($v_ln)-1]]);$j++){
				$v_br = explode(",",substr($arr_cs[$v_ln[count($v_ln)-1]][$j],1,-1));
				if(array_search($v_br[1],$v_ln) === false){
					$arr_ret1[] = $arr_ret[$i].$v_br[1].",";
				}
				unset($v_br);
			}
			unset($v_ln);
		}
		$arr_ret = array_merge($arr_ret,$arr_ret1);
		//sort($arr_ret);
		$arr_ret = array_values(array_unique($arr_ret));
		unset($arr_ret1);
		$cnt++;
	}
	unset($arr_cs,$arr_cd);
	$arr_init = $arr_ret;
	//punem si rutele in afara celor de intersectii
	for($i=0;$i<count($arr_graph);$i++){
		$arr_perm = per22($arr_graph[$i]);
		for($j=0;$j<count($arr_perm);$j++){
			$arr_init[] = ",".implode(",",$arr_perm[$j]).",";
			$arr_graph_new[] = ",".implode(",",$arr_perm[$j]).",";
		}
	}
	//ma uit la graf si scot intersectiile
	//$arr_init = array_values(array_diff($arr_init,$arr_brs));
	if($arr_non){
		$arr_init = array_values(array_diff($arr_init,$arr_non));
	}
	$arr_gfi = array_values(array_diff($arr_graph_new,$arr_brs));
	if($arr_non){
		$arr_gfi = array_values(array_diff($arr_gfi,$arr_non));
	}
	for($j=0;$j<count($arr_gfi);$j++){
		$v_br = explode(",",substr($arr_gfi[$j],1,-1));
		if(is_null($arr_cs[$v_br[0]])){
			$arr_cs[$v_br[0]][0] = $arr_gfi[$j];
		}
		else{
			$arr_cs[$v_br[0]][] = $arr_gfi[$j];
		}
		if(is_null($arr_cd[$v_br[1]])){
			$arr_cd[$v_br[1]][0] = $arr_gfi[$j];
		}
		else{
			$arr_cd[$v_br[1]][] = $arr_gfi[$j];
		}
	}
	//lipim marginile stanga
	$arr_roads_all = $arr_init;
	$arr_roads = $arr_init;
	for($i=0;$i<count($arr_roads);$i++){
		$v_ln = explode(",",substr($arr_roads[$i],1,-1));
		for($j=0;$j<count($arr_cd[$v_ln[0]]);$j++){
			$v_br = explode(",",substr($arr_cd[$v_ln[0]][$j],1,-1));
			if(array_search($v_br[0],$v_ln) === false){
				$arr_roads_all[] = ",".$v_br[0].$arr_roads[$i];
			}
			unset($v_br);
		}
	}
	//lipim marginile dreapta
	$arr_roads = $arr_roads_all;
	for($i=0;$i<count($arr_roads);$i++){
		$v_ln = explode(",",substr($arr_roads[$i],1,-1));
		for($j=0;$j<count($arr_cs[$v_ln[count($v_ln)-1]]);$j++){
			$v_br = explode(",",substr($arr_cs[$v_ln[count($v_ln)-1]][$j],1,-1));
			if(array_search($v_br[1],$v_ln) === false){
				$arr_roads_all[] = $arr_roads[$i].$v_br[1].",";
			}
			unset($v_br);
		}
	}	
	sort($arr_roads_all);
	$arr_routes_new = array_values(array_unique($arr_roads_all));
	//$arr_routes_new = $arr_roads_all;
	unset($arr_roads);
	$arr_roads['in'] = $arr_routes_new;
	for($i=0;$i<count($arr_routes_new);$i++){
		$arr_roads['roads'][$i] = str_replace(",","->",substr($arr_routes_new[$i],1,-1));
	}
	return $arr_roads;
}
function graph_to_array3($arr_graph,$arr_roads,$node){
	//$arr_roads = roads($arr_graph);
	if(is_null($node)){
		$node = $arr_graph[0][0];
	}
	$arr_str_graph = array();
	$arr_sr = $arr_roads['in'];
	sort($arr_sr);
	//echo "<pre>".print_r($arr_sr,1)."</pre>";
	for($i=0;$i<count($arr_sr);$i++){
		$str = substr($arr_sr[$i],1,-1);
		array_push($arr_str_graph,"$"."arr_graph_new[".str_replace(",","][",$str)."] = array(0);");
	}
	eval(implode(" ",$arr_str_graph));
	$arr_ret = array($node => $arr_graph_new[$node]);
	RecursiveCategories($arr_ret);
	//array_walk_recursive($arr_ret, 'print_array');
	//return array($node => $arr_graph_new[$node]);
}
function print_array($item, $key){
//if(!is_null($key)){
	if(is_array($item)){
		echo "->$key\n";
		print_array($item, $key);
	}
	else{
		echo "->.\n";
	}

//}
}
function RecursiveCategories($array) {
	echo "\n<ul>\n";
	foreach ($array as $key => $vals) {
		if($vals != 0){
			echo "<li style='border-top:1px solid black;border-right:1px solid black;width:30px;margin-bottom:10px;'>".$key;
			//echo "<li>".$key;
			if (count($vals)) {
				RecursiveCategories($vals);
			}
			echo "</li>\n";
		}
	}
	echo "</ul>\n";
}
function trimming($data) {
	if (gettype($data) == 'array')
		return array_map("trimming", $data);
	else
		return trim($data);
}
function microtime_float(){
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
//enter here the graph each branch is an array of 2 nodes to verify the number of routes n*n-1 - n = nr of nodes

$br[0][0] = 21;
$br[0][1] = 12;
$br[1][0] = 21;
$br[1][1] = 2;
$br[2][0] = 12;
$br[2][1] = 11;
$br[3][0] = 9;
$br[3][1] = 7;
$br[4][0] = 9;
$br[4][1] = 20;
$br[5][0] = 2;
$br[5][1] = 20;
$br[6][0] = 13;
$br[6][1] = 2;
$br[7][0] = 18;
$br[7][1] = 20;
$br[8][0] = 1;
$br[8][1] = 2;
$br[9][0] = 1;
$br[9][1] = 41;
$br[10][0] = 40;
$br[10][1] = 1;
$br[11][0] = 11;
$br[11][1] = 3;
$br[12][0] = 11;
$br[12][1] = 10;
$br[13][0] = 11;
$br[13][1] = 14;
$br[14][0] = 5;
$br[14][1] = 9;
$br[15][0] = 100;
$br[15][1] = 20;
$br[16][0] = 400;
$br[16][1] = 100;
$br[17][0] = 101;
$br[17][1] = 20;
$br[18][0] = -1;
$br[18][1] = 41;
$br[19][0] = -8;
$br[19][1] = 41;
$br[20][0] = -14;
$br[20][1] = 20;
$br[21][0] = 40;
$br[21][1] = 39;
$br[22][0] = 40;
$br[22][1] = 33;
$br[23][0] = 36;
$br[23][1] = 33;
$br[24][0] = 34;
$br[24][1] = 33;
$br[25][0] = 35;
$br[25][1] = 33;
$br[26][0] = 500;
$br[26][1] = 33;
$br[27][0] = 501;
$br[27][1] = 33;
$br[28][0] = 501;
$br[28][1] = 502;
$br[29][0] = 501;
$br[29][1] = 503;
$br[30][0] = 501;
$br[30][1] = 504;
$br[31][0] = 501;
$br[31][1] = 505;
$br[32][0] = 502;
$br[32][1] = 506;
$br[33][0] = 502;
$br[33][1] = 507;
$br[34][0] = 505;
$br[34][1] = 508;
$br[35][0] = 505;
$br[35][1] = 509;
$br[36][0] = 509;
$br[36][1] = 510;
$br[37][0] = 509;
$br[37][1] = 511;
$br[38][0] = 509;
$br[38][1] = 512;
$br[39][0] = 512;
$br[39][1] = 513;
$br[40][0] = 512;
$br[40][1] = 0;
$br[41][0] = 40;
$br[41][1] = 7000;

/*
$br[0][0] = 2;
$br[0][1] = 1;
$br[1][0] = 1;
$br[1][1] = 3;
$br[2][0] = 1;
$br[2][1] = 4;
$br[3][0] = 5;
$br[3][1] = 1;
$br[4][0] = 6;
$br[4][1] = 2;
$br[5][0] = 2;
$br[5][1] = 7;
$br[6][0] = 8;
$br[6][1] = 1;
$br[7][0] = 9;
$br[7][1] = 1;
$br[8][0] = 7;
$br[8][1] = -1;
$br[9][0] = 7;
$br[9][1] = -8;
$br[10][0] = 10;
$br[10][1] = 1;
$br[11][0] = 11;
$br[11][1] = 1;
$br[12][0] = 12;
$br[12][1] = 2;
*/
/*
$br[0][0] = 20;
$br[0][1] = 2;
$br[1][0] = 20;
$br[1][1] = 3;
$br[2][0] = 20;
$br[2][1] = 4;
$br[3][0] = 3;
$br[3][1] = 5;
$br[4][0] = 3;
$br[4][1] = 6;
$br[5][0] = 3;
$br[5][1] = 7;
$br[6][0] = 5;
$br[6][1] = 8;
$br[7][0] = 5;
$br[7][1] = 9;
$br[8][0] = 9;
$br[8][1] = 10;
$br[9][0] = 10;
$br[9][1] = 11;
$br[10][0] = 10;
$br[10][1] = 12;*/
/*
$s[] = '1,2,3,4,5,6,7';
$s[] = '8,9,10,4,11,5,12,7';
$s[] = '4,13,14,12,15,16,17';
$s[] = '18,19,20,15,21,22';
$s[] = '23,24,25,21,26,7';
$s[] = '27,28,29,4,30,31';
$s[] = '32,4,33,34,7,35,36';
$s[] = '37,4,38,39,12,40';

$k = 0;
for($i=0;$i<count($s);$i++){
	$v_line = explode(",",$s[$i]);
	for($j=0;$j<(count($v_line)-1);$j++){
		$br[$k][0] = $v_line[$j];
		$br[$k][1] = $v_line[$j+1];
		$k++;
	}	
}
*/
/*
$br[0] = array(1,2);
$br[1] = array(1,6);
$br[2] = array(2,3);
$br[3] = array(2,7);
$br[4] = array(3,4);
$br[5] = array(3,8);
$br[6] = array(4,5);
$br[7] = array(4,9);
$br[8] = array(5,10);
$br[9] = array(6,7);
$br[10] = array(6,11);
$br[11] = array(7,8);
$br[12] = array(7,12);
$br[13] = array(8,9);
$br[14] = array(8,13);
$br[15] = array(9,10);
$br[16] = array(9,14);
$br[17] = array(10,15);
$br[18] = array(11,12);
$br[19] = array(12,13);
$br[20] = array(13,14);
$br[21] = array(14,15);
*/
/*
$br[0] = array(10,5);
$br[1] = array(5,4);
$br[2] = array(4,9);
$br[3] = array(4,3);
$br[4] = array(3,2);
$br[5] = array(3,8);
$br[6] = array(2,1);
$br[7] = array(2,7);
$br[8] = array(1,6);
$br[9] = array(10,9);
$br[10] = array(9,15);
$br[11] = array(15,14);
$br[12] = array(14,13);
$br[13] = array(13,12);
$br[14] = array(12,11);
*/
/*
$br[0] = array(1,2);
$br[1] = array(2,3);
$br[2] = array(3,4);
$br[3] = array(4,5);
$br[4] = array(5,6);
$br[5] = array(6,7);
$br[6] = array(7,8);
$br[7] = array(8,9);
$br[8] = array(9,10);
$br[9] = array(10,11);
$br[10] = array(11,12);
$br[11] = array(12,13);
$br[12] = array(13,14);
$br[13] = array(14,15);
$br[14] = array(15,16);
$br[15] = array(16,17);
$br[16] = array(17,18);
$br[17] = array(18,19);
$br[18] = array(19,20);
$br[19] = array(20,21);
$br[20] = array(21,22);
$br[21] = array(22,23);
$br[22] = array(23,24);
$br[23] = array(24,25);
$br[24] = array(25,26);
$br[25] = array(26,27);
$br[26] = array(27,28);
$br[27] = array(28,29);
$br[28] = array(29,30);
$br[29] = array(30,31);
$br[30] = array(31,32);
$br[31] = array(32,33);
$br[32] = array(33,34);
$br[33] = array(34,35);
$br[34] = array(35,36);
$br[35] = array(36,37);
$br[36] = array(37,38);
$br[37] = array(38,39);
$br[38] = array(39,40);
$br[39] = array(40,41);
$br[40] = array(41,42);
$br[41] = array(42,43);
$br[42] = array(43,44);
$br[43] = array(44,45);
$br[44] = array(45,46);
$br[45] = array(46,47);
$br[46] = array(47,48);
$br[47] = array(48,49);
$br[48] = array(49,50);
$br[49] = array(50,51);
$br[50] = array(51,52);
$br[51] = array(52,53);
$br[52] = array(53,54);
$br[53] = array(54,55);
$br[54] = array(55,56);
$br[55] = array(56,57);
$br[56] = array(57,58);
$br[57] = array(58,59);
$br[58] = array(59,60);
$br[59] = array(60,61);
$br[60] = array(61,62);
$br[61] = array(62,63);
$br[62] = array(63,64);
$br[63] = array(64,65);
$br[64] = array(65,66);
$br[65] = array(66,67);
$br[66] = array(67,68);
$br[67] = array(68,69);
$br[68] = array(69,70);
$br[69] = array(70,71);
$br[70] = array(71,72);
$br[71] = array(72,73);
$br[72] = array(73,74);
$br[73] = array(74,75);
$br[74] = array(75,76);
$br[75] = array(76,77);
$br[76] = array(77,78);
$br[77] = array(78,79);
$br[78] = array(79,80);
$br[79] = array(80,81);
$br[80] = array(81,82);
$br[81] = array(82,83);
$br[82] = array(83,84);
$br[83] = array(84,85);
$br[84] = array(85,86);
$br[85] = array(83,29);
*/
/*$br[0] = array(1,2);
$br[1] = array(1,6);
$br[2] = array(2,3);
$br[3] = array(2,7);
$br[4] = array(3,4);
$br[5] = array(3,8);
$br[6] = array(4,5);
$br[7] = array(4,9);
$br[8] = array(5,10);
$br[9] = array(6,7);
$br[10] = array(6,11);
$br[11] = array(7,8);
$br[12] = array(7,12);
$br[13] = array(8,9);
$br[14] = array(8,13);
$br[15] = array(9,10);
$br[16] = array(9,14);
$br[17] = array(10,15);
$br[18] = array(11,12);
$br[19] = array(12,13);
$br[20] = array(13,14);
$br[21] = array(14,15);*/
/*
$br[0] = array(1,2);
$br[1] = array(3,4);
$br[2] = array(8,2);
$br[3] = array(1,6);
$br[4] = array(7,2);
$br[5] = array(3,8);
$br[6] = array(9,2);
*/
/*
$br[0] = array(1,4);
$br[1] = array(1,5);
$br[2] = array(2,8);
$br[3] = array(4,8);
$br[4] = array(5,16);
$br[5] = array(1,9);
$br[6] = array(3,9);
*/
//end graph
$start = microtime_float();
$arr_roads = roads($br);//,array(array(19,18)));
$stop = microtime_float();
echo "<br>GRAPH EXECUTED IN : ".($stop-$start);
echo "<br>MEMORY :".(round(memory_get_peak_usage(false)/1024,2))."KB";
echo "<br><pre>THE GRAPH SEEN FROM NODE ".($_REQUEST['node']?$_REQUEST['node']:'FIRST DEFINED NODE')." : ".print_r(graph_to_array3($br,$arr_roads,$_REQUEST['node']),1)."</pre>";

if($_REQUEST['nodi']){
	$arr_roadr = array();
	for($i=0;$i<count($arr_roads['in']);$i++){
		if(substr($arr_roads['in'][$i],0,strlen(','.$_REQUEST['nodi'].',')) == ','.$_REQUEST['nodi'].',' && substr($arr_roads['in'][$i],-strlen(','.$_REQUEST['nodf'].',')) == ','.$_REQUEST['nodf'].','){
			array_push($arr_roadr,$arr_roads['roads'][$i]);
		}
	}
	echo "<br><pre>ROADS CONTAINING : ".$_REQUEST['nodi']." ...-> ... ".$_REQUEST['nodf']." ".print_r($arr_roadr,1)."</pre>";
}
else{
	echo "<br><pre>ROADS FOR A GRAPH : ".print_r($arr_roads['roads'],1)."</pre>";
}
?>
</body>
</html>