<?php
include("config.php");
$s = new Game();
if($_GET['val'])
{
	$searchVal = $s->clean_sql($_GET['val']);
	$query = "SELECT u.uname, ud.uid, r.r_name as race, COALESCE(rk.overall, 0) as rank 
	         FROM users u 
	         JOIN userdata ud ON ud.uid = u.uid 
	         JOIN race r ON r.rid = ud.rid 
	         LEFT JOIN rank rk ON rk.uid = ud.uid 
	         WHERE u.uname ILIKE ".$searchVal."|| '%' 
	         ORDER BY rk.overall";
	$q = $s->query($query);
	$str = "{ \"result\": [";
	if ($q) {
		while ($data = $q->fetch(PDO::FETCH_OBJ))
		{
			$str .= "[\"$data->uname\", \"$data->race\", \"$data->rank\",\"$data->uid\" ],";
		}
	}
	$str .= "[]]}";
	echo $str;
}

?>