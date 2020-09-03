<?php
// DATA BASE QUERY
// PRACTICES
// Create the new string
function db_newDayPractices($memberId){
  global $db;
  $memberId = $db->real_escape_string($memberId);
  $currentDate = date("Y-m-d");
  $result = 0;
  $res=db_query ("SELECT `id` FROM practices WHERE `member_id` = '$memberId' AND `date_practic` = '$currentDate'");
  while ($row = $res->fetch_assoc()) $result=$row;
  if (!$result) {
    db_query("INSERT INTO practices (`date_create`, `member_id`, `date_practic`) VALUES (NOW(), '$memberId', '$currentDate')");
  }
}
// Update the string
function db_updateTodayPractices($memberId, $userData){
  global $db;
  $mr = $db->real_escape_string($userData['mr']);
  $pp = $db->real_escape_string($userData['pp']);
  $pc = $db->real_escape_string($userData['pc']);
  $rb = $db->real_escape_string($userData['rb']);
  $rm = $db->real_escape_string($userData['rm']);
  $gspl = $db->real_escape_string($userData['gspl']);
  $fl = $db->real_escape_string($userData['fl']);
  $cnt = $db->real_escape_string($userData['cnt']);
  $svd = $db->real_escape_string($userData['svd']);
  $meet = $db->real_escape_string($userData['meet']);
  $wake = $db->real_escape_string($userData['wake']);
  $hang = $db->real_escape_string($userData['hang']);
  $oth = $db->real_escape_string($userData['oth']);
  $currentDate = date("Y-m-d");

  db_query ("UPDATE practices SET `m_revival` = '$mr', `p_pray` = '$pp', `co_pray` = '$pc', `r_bible` = '$rb', `r_ministry` = '$rm', `evangel` = '$gspl', `flyers` = '$fl', `contacts` = '$cnt', `saved` = '$svd', `meetings` = '$meet', `wakeup` = '$wake', `hangup` = '$hang', `other` = '$oth' WHERE `member_id` = '$memberId' AND `date_practic` = '$currentDate'");

  return 1;
}

function db_updatePracticesByAdmin($stringId, $userData){
  global $db;
  $stringId = $db->real_escape_string($stringId);
  $mr = $db->real_escape_string($userData['mr']);
  $pp = $db->real_escape_string($userData['pp']);
  $pc = $db->real_escape_string($userData['pc']);
  $rb = $db->real_escape_string($userData['rb']);
  $rm = $db->real_escape_string($userData['rm']);
  $gspl = $db->real_escape_string($userData['gspl']);
  $fl = $db->real_escape_string($userData['fl']);
  $cnt = $db->real_escape_string($userData['cnt']);
  $svd = $db->real_escape_string($userData['svd']);
  $meet = $db->real_escape_string($userData['meet']);
  $wake = $db->real_escape_string($userData['wake']);
  $hang = $db->real_escape_string($userData['hang']);
  $oth = $db->real_escape_string($userData['oth']);
  $currentDate = date("Y-m-d");

  db_query ("UPDATE practices SET `m_revival` = '$mr', `p_pray` = '$pp', `co_pray` = '$pc', `r_bible` = '$rb', `r_ministry` = '$rm', `evangel` = '$gspl', `flyers` = '$fl', `contacts` = '$cnt', `saved` = '$svd', `meetings` = '$meet', `wakeup` = '$wake', `hangup` = '$hang', `other` = '$oth' WHERE `id` = '$stringId'");

  return 1;
}

// Get Practices for user
function db_getPractices($memberId){
    global $db;
    $currentDate = date("Y-m-d", strtotime("-7 days"));
    $memberId = $db->real_escape_string($memberId);
    $res=db_query ("SELECT * FROM practices WHERE `member_id` = '$memberId' AND `date_practic` > '$currentDate' ORDER BY `date_practic` DESC");
    $practices = array ();
    while ($row = $res->fetch_assoc()) $practices[]=$row;
    return $practices;
}

// Get all Practices
function  db_getPracticesAll (){
  global $db;
  $currentDate = date("Y-m-d", strtotime("-7 days"));
  //$memberId = $db->real_escape_string($memberId);
  $res=db_query ("SELECT * FROM practices WHERE `date_practic` > '$currentDate' ORDER BY `date_practic` DESC");
  $practices = array ();
  while ($row = $res->fetch_assoc()) $practices[]=$row;
  return $practices;
}

// Get Practices for user today
function db_getPracticesToday($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $currentDate = date("Y-m-d");
    $res=db_query ("SELECT * FROM practices WHERE `member_id` = '$memberId' AND `date_practic` = '$currentDate'");
    $practices = array ();
    while ($row = $res->fetch_assoc()) $practices[]=$row;
    return $practices;
}

function db_getPracticesForAdmin($userData){
  global $db;
    $localities = $db->real_escape_string($userData['localities']);
    $period = $db->real_escape_string($userData['period']);
    $sortType = $db->real_escape_string($userData['sort']);
    if ($period === '7' ) {
      $currentDate = date("Y-m-d", strtotime("-7 days"));
    } else {
      $currentDate = date("Y-m-d", strtotime("-30 days"));
    }

  //$memberId = $db->real_escape_string($memberId);
  $practices = array ();
  if ($sortType == 'name_down') {
    $res=db_query ("SELECT p.id, p.date_practic, p.member_id, p.m_revival, p.p_pray, p.co_pray, p.r_bible,
      p.r_ministry, p.evangel, p.flyers, p.contacts, p.saved, p.meetings, p.wakeup, p.hangup, p.other, m.name, m.serving, m.locality_key, l.name AS loc_name
      FROM practices AS p
      INNER JOIN member m ON m.key = p.member_id
      INNER JOIN locality l ON l.key = m.locality_key
      WHERE `date_practic` > '$currentDate' AND `serving` <> '' AND (m.locality_key = ".$localities.")
      ORDER BY m.name DESC, p.date_practic ASC");

    while ($row = $res->fetch_assoc()) $practices[]=$row;

  } elseif ($sortType == 'name_up') {
    $res=db_query ("SELECT p.id, p.date_practic, p.member_id, p.m_revival, p.p_pray, p.co_pray, p.r_bible,
      p.r_ministry, p.evangel, p.flyers, p.contacts, p.saved, p.meetings, p.wakeup, p.hangup, p.other, m.name, m.serving, m.locality_key, l.name AS loc_name
      FROM practices AS p
      INNER JOIN member m ON m.key = p.member_id
      INNER JOIN locality l ON l.key = m.locality_key
      WHERE `date_practic` > '$currentDate' AND `serving` <> '' AND (m.locality_key = ".$localities.")
      ORDER BY m.name ASC, p.date_practic ASC");

    while ($row = $res->fetch_assoc()) $practices[]=$row;

  } /*elseif ($sortType == 'serviceones_up') {
    $res=db_query ("SELECT p.id, p.date_practic, p.member_id, p.m_revival, p.p_pray, p.co_pray, p.r_bible,
      p.r_ministry, p.evangel, p.flyers, p.contacts, p.saved, p.meetings, p.wakeup, p.hangup, p.other, m.name, m.serving, m.locality_key, l.name AS loc_name
      FROM practices AS p
      INNER JOIN member m ON m.key = p.member_id
      INNER JOIN locality l ON l.key = m.locality_key
      WHERE `date_practic` > '$currentDate' AND `serving` <> '' AND (m.locality_key = ".$localities.")
      ORDER BY m.name ASC, p.date_practic ASC");

    while ($row = $res->fetch_assoc()) $practices[]=$row;

  }*/
    return $practices;
}


?>
