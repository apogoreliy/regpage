<?php
// DATA BASE QUERY

// Youth
function db_getYouthList($adminId, $sort_field, $sort_type){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $sort_field = $db->real_escape_string($sort_field);
    $sort_type = $db->real_escape_string($sort_type);

    $res=db_query ("SELECT DISTINCT * FROM
        (SELECT DISTINCT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality,
        m.email as email, m.cell_phone as cell_phone, m.changed>0 as changed, m.admin_key as admin_key,
        (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as admin_name, m.active, m.locality_key,
        DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, m.birth_date,
        m.school_comment, m.college_comment, m.college_start, m.college_end, m.school_start, m.school_end,
        m.comment, co.name as college_name, m.category_key, m.attend_meeting,
        co.short_name as college, r.name as region, c.name as country,
        /*(SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region,the next 2 lines are Romans code ver 5.0.1
        (SELECT cn.name FROM country cn INNER JOIN region re ON cn.key=re.country_key WHERE l.region_key=re.key) as country,*/
        (SELECT lo.name FROM locality lo WHERE co.locality_key = lo.key ) as college_locality,
        CASE WHEN m.category_key='SC' OR m.category_key='PS' THEN 1 ELSE 0 END as school,
        CASE WHEN m.school_start>0 THEN YEAR(NOW()) - m.school_start + 1 ELSE 0 END as school_level,
        CASE WHEN m.college_start>0 THEN YEAR(NOW()) - m.college_start + 1 ELSE 0 END as college_level
        FROM access as a
        LEFT JOIN country c ON c.key = a.country_key
        LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
        INNER JOIN member m ON m.locality_key = l.key
        LEFT JOIN college co ON co.key = m.college_key
        WHERE a.member_key='$adminId' AND ( m.category_key = 'ST' OR m.category_key = 'SC' OR ( (m.category_key = 'BL' OR m.category_key = 'SN' ) AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) < 25 AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) > 17))
        UNION
        SELECT DISTINCT m.key as id, m.name as name,
        (SELECT lo.name FROM locality lo WHERE m.locality_key = lo.key ) as locality,
        m.email as email, m.cell_phone as cell_phone, m.changed>0 as changed, m.admin_key as admin_key,
        (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as admin_name, m.active, m.locality_key,
        DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, m.birth_date,
        m.school_comment, m.college_comment, m.college_start, m.college_end, m.school_start, m.school_end,
        m.comment, co.name as college_name, m.category_key, m.attend_meeting,
        co.short_name as college, r.name as region, c.name as country,
        /*(SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region, Roman! Bug was HERE!!! the next 2 lines are Romans code ver 5.0.1
        (SELECT cn.name FROM country cn INNER JOIN region re ON cn.key=re.country_key WHERE l.region_key=re.key) as country,*/
        (SELECT lo.name FROM locality lo WHERE co.locality_key = lo.key ) as college_locality,
        CASE WHEN m.category_key='SC' OR m.category_key='PS' THEN 1 ELSE 0 END as school,
        CASE WHEN m.school_start>0 THEN YEAR(NOW()) - m.school_start + 1 ELSE 0 END as school_level,
        CASE WHEN m.college_start>0 THEN YEAR(NOW()) - m.college_start + 1 ELSE 0 END as college_level
        FROM access as a
        LEFT JOIN country c ON c.key = a.country_key
        LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
        INNER JOIN member m ON m.locality_key = l.key
        INNER JOIN college co ON co.key = m.college_key
        WHERE a.member_key='$adminId' AND ( m.category_key = 'ST' OR m.category_key = 'SC' OR ((m.category_key = 'BL' OR m.category_key = 'SN' ) AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) < 25 AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) > 17 ) )) q ORDER BY q.active DESC, $sort_field $sort_type");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}
?>
