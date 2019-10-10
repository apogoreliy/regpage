<?php
function db_getSessionsAdmins(){
    $res=db_query ("SELECT member_key, session FROM admin WHERE session IS NOT NULL");

		$sessions = array ();
		while ($row = $res->fetch_object()) $sessions[]=$row;
		return $sessions;
}


function db_copySessions($adminId, $sessionId)
{
	global $db;
	$adminId = $db->real_escape_string($adminId);
	$sessionId = $db->real_escape_string($sessionId);
	db_query ("INSERT INTO admin_session (id_session, admin_key) VALUES ('$sessionId','$adminId')");
}

function db_delete_old_sessions()
{
	global $db;
  $datatime = date("Y-m-d H:i:s");
  db_query ("DELETE FROM admin_session WHERE ($datatime - `time_last_visit`)>356");
}
function db_getCustomPagesPanel(){
    $res = db_query("SELECT * FROM custom_page");

    $pages = array();
    while($row = $res->fetch_assoc()){
        $pages [$row['name']] = $row['value'];
    }

    return $pages;
}
?>
