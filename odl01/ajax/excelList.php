<?php
include_once '../db.php';
include_once '../PHPExcel.php';
include_once '../PHPExcel/IOFactory.php';
include_once '../FirePHP.class.php';

if(isset($_GET['file'])) {
    $fileSearch = basename($_GET['file']);

    if (!$fileSearch) {
        die('Файл не найден');
    } else {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileSearch);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileSearch));
        readfile($fileSearch);
        exit;
    }
}

if(isset($_POST['data'])){
    $file = $_POST['data'];
    unlink($file);
}
else if ($_POST['page'] == 'reg' && isset($_POST ['parking'])) {
    $memberslength = $_POST ['memberslength'];
    $membersAll = json_decode($_POST ['members'], TRUE);

    $title =  'Данные о парковке';
    $num=1;

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("PHP")
        ->setLastModifiedBy("reg-page")
        ->setTitle("Office 2007 XLSX")
        ->setSubject("Office 2007 XLSX")
        ->setDescription("Office 2007 XLSX, сгенерированный PHPExcel.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Тестовый файл");

    $active_sheet = $objPHPExcel->getActiveSheet();
    $active_sheet->setTitle($title);
    $active_sheet->getColumnDimension('A')->setWidth(6);
    $active_sheet->getColumnDimension('B')->setWidth(20);
    $active_sheet->getColumnDimension('C')->setWidth(20);
    $active_sheet->getColumnDimension('D')->setWidth(30);
    $active_sheet->getColumnDimension('E')->setWidth(20);
    $active_sheet->getColumnDimension('F')->setWidth(20);
    $active_sheet->getColumnDimension('G')->setWidth(20);

    $style_wrap = array(
        'borders'=>array(
            'outline' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN
            ),
            'allborders'=>array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb'=>'696969'
                )
            )
        ),
        'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color'=>array(
                'rgb' => 'EEEEEE'
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        )
    );

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '№')
        ->setCellValue('B1', 'Номер')
        ->setCellValue('C1', 'Автомобиль')
        ->setCellValue('D1', 'ФИО')
        ->setCellValue('E1', 'Телефон')
        ->setCellValue('F1', 'Дата приезда')
        ->setCellValue('G1', 'Дата отъезда');

    for($i=2, $m=0; $m < $memberslength; $m++){
        if($membersAll[$m]['parking']){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", $num)
                ->setCellValue("B$i", $membersAll[$m]['avtomobile_number'])
                ->setCellValue("C$i", $membersAll[$m]['avtomobile'])
                ->setCellValue("D$i", $membersAll[$m]['name'])
                ->setCellValue("E$i", $membersAll[$m]['cell_phone'])
                ->setCellValue("F$i", $membersAll[$m]['arr_date'])
                ->setCellValue("G$i", $membersAll[$m]['dep_date']);
            $i++;
            $num ++;
        }
    }

    $active_sheet->getStyle('A1:G1')->applyFromArray($style_wrap);

    //$admin = isset($_POST['adminId']) ? $_POST['adminId'] : '';
    $event = 'parking';

    $date = date('Y-m-d');
    $time = date('H:i');
    $file = $date.'_'.$event.' ('.$time.')';

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($file.'.xlsx');

    $filename = $file.'.xlsx';
    echo $filename;
}
else if (isset($_POST ['members']) && isset ($_POST ['memberslength']) && isset($_POST['adminId']) && ($_POST['page'] == 'reg_aid')) {
    $memberslength = $db->real_escape_string($_POST ['memberslength']);
    $membersAll = json_decode($_POST ['members'], TRUE);
    $fields = isset($_POST['fields']) ? explode(',', $db->real_escape_string($_POST['fields'])) : NULL;
    $fieldsCount = count($fields);
    $suplemValues = isset($_POST['general_values']) ? json_decode($_POST['general_values'], TRUE) : NULL;

    $title = 'Финансовая помощь';
    $num=1;

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("PHP")
        ->setLastModifiedBy("reg-page")
        ->setTitle("Office 2007 XLSX")
        ->setSubject("Office 2007 XLSX")
        ->setDescription("Office 2007 XLSX, сгенерированный PHPExcel.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Тестовый файл");
    $objPHPExcel->getActiveSheet()->setTitle($title);

    $active_sheet = $objPHPExcel->getActiveSheet();

    $active_sheet->getColumnDimension('A')->setWidth(4);
    $active_sheet->getColumnDimension('B')->setWidth(40);

    $ind_count = 'C';
    for ($d=0; $d < $fieldsCount; $d++) {
        $active_sheet->getColumnDimension($ind_count)->setWidth(30);
        $ind_count ++;
    }

    $style_wrap = array(
        'borders'=>array(
            'outline' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN
            ),
            'allborders'=>array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb'=>'696969'
                )
            )
        ),
        'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color'=>array(
                'rgb' => 'EEEEEE'
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        )
    );

    $active_sheet->getStyle('A1:'.$ind_count.'1')->applyFromArray($style_wrap);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '№')
        ->setCellValue('B1', 'Участник');

    $ind = 'C';
    for ($d=0; $d < $fieldsCount; $d++) {
        switch ($fields[$d]) {
            case 'locality':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Город');
                break;
            case 'contr_amount':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'На мероприятие');
                break;
            case 'trans_amount':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'На транспорт');
                break;
            case 'aid_paid':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Покрыто');
                break;
        }
        $ind ++;
    }

    for($i=2, $m=0; $m<$memberslength; $m++){
        for($j=0; $j < $fieldsCount; $j++) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", $num)
                ->setCellValue("B$i", $membersAll[$m]['name']);

            $ind = 'C';
            foreach ($fields as $d) {
                switch ($d) {
                    case 'locality':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['locality']);
                        break;
                    case 'contr_amount':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['contr_amount']);
                        break;
                    case 'trans_amount':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['trans_amount']);
                        break;
                    case 'aid_paid':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['aid_paid'] == -1 ? 'Отказано' : $membersAll[$m]['aid_paid']);
                        break;
                }
                $ind ++;
            }
        }
        $i++;
        $num++;
    }

    $numField = $memberslength + 4;
    $active_sheet->getStyle('A'.$numField.':F'.$numField)->applyFromArray($style_wrap);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A$numField", '')
        ->setCellValue("B$numField", $suplemValues[0]['field'])
        ->setCellValue("C$numField", $suplemValues[1]['field'])
        ->setCellValue("D$numField", $suplemValues[2]['field'])
        ->setCellValue("E$numField", $suplemValues[3]['field'])
        ->setCellValue("F$numField", $suplemValues[4]['field']);

    $numValues = $numField + 1;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A$numValues", '')
        ->setCellValue("B$numValues", $suplemValues[0]['amount'])
        ->setCellValue("C$numValues", $suplemValues[1]['amount'])
        ->setCellValue("D$numValues", $suplemValues[2]['amount'])
        ->setCellValue("E$numValues", $suplemValues[3]['amount'])
        ->setCellValue("F$numValues", $suplemValues[4]['amount']);

    $admin = $_POST['adminId'];
    $event = '-aid';
    $file = $admin.$event;

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($file.'.xlsx');

    $filename = $file.'.xlsx';
    echo $filename;
}
else if (isset($_POST ['members']) && isset ($_POST ['memberslength']) && isset($_POST['adminId']) && ($_POST['page'] == 'members')) {
    $memberslength = $db->real_escape_string($_POST ['memberslength']);
    $membersAll = json_decode($_POST ['members'], TRUE);
    $fields = isset($_POST['document']) ? explode(',', $db->real_escape_string($_POST['document'])) : NULL;
    $fieldsCount = count($fields);

    $title = 'Список';
    $num=1;

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("PHP")
        ->setLastModifiedBy("reg-page")
        ->setTitle("Office 2007 XLSX")
        ->setSubject("Office 2007 XLSX")
        ->setDescription("Office 2007 XLSX, сгенерированный PHPExcel.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Тестовый файл");
    $objPHPExcel->getActiveSheet()->setTitle($title);

    $active_sheet = $objPHPExcel->getActiveSheet();

    $active_sheet->getColumnDimension('A')->setWidth(4);
    $active_sheet->getColumnDimension('B')->setWidth(40);

    $ind_count = 'B';
    for ($d=0; $d < $fieldsCount; $d++) {
        $active_sheet->getColumnDimension($ind_count)->setWidth(30);
        $ind_count ++;
    }

    $style_wrap = array(
        'borders'=>array(
            'outline' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN
            ),
            'allborders'=>array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb'=>'696969'
                )
            )
        ),
        'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color'=>array(
                'rgb' => 'EEEEEE'
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        )
    );

    $active_sheet->getStyle('A1:'.$ind_count.'1')->applyFromArray($style_wrap);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '№')
        ->setCellValue('B1', 'Участник');

    $ind = 'B';
    for ($d=0; $d < $fieldsCount; $d++) {
        switch ($fields[$d]) {
            case 'name':
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Участник');
              break;
            case 'locality':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Город');
                break;
            case 'cell_phone':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Телефон');
                break;
            case 'email':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Email');
                break;
            case 'birth-date':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Дата рождения');
                break;
            case 'age':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Возраст');
                break;
            case 'male':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Пол');
                break;
            case 'attend_meeting':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Посещает собрание');
                break;
            case 'school':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Школа');
                break;
            case 'school_start':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Год начала учебы в школе');
                break;
            case 'school_end':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Год окончания учебы в школе');
                break;
            case 'school_level':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Класс');
                break;
            case 'school_comment':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Примечание о школе');
                break;
            case 'college':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Учебное заведение');
                break;
            case 'college_start':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Год начала учебы в учебном заведении');
                break;
            case 'college_end':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Год окончания учебы в учебном заведении');
                break;
            case 'college_level':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Курс');
                break;
            case 'college_comment':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Примечание об учебном заведении');
                break;
            case 'comment':
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Комментарий');
                break;
        }
        $ind ++;
    }

    for($i=2, $m=0; $m<$memberslength; $m++){
        if($membersAll[$m]['active'] === "0") {
            continue;
        }

        for($j=0; $j < $fieldsCount; $j++) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", $num)
                ->setCellValue("B$i", $membersAll[$m]['name']);

            $ind = 'B';
            foreach ($fields as $d) {
                switch ($d) {
                    case 'locality':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['locality']);
                        break;
                    case 'cell_phone':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['cell_phone']);
                        break;
                    case 'email':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['email']);
                        break;
                    case 'birth-date':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['birth_date'] ? $membersAll[$m]['birth_date'] : 'Не указан');
                        break;
                    case 'age':
                        $birthDate = (int)$membersAll[$m]['age'];
                        /*
                        $suffix = '';

                        if($membersAll[$m]['age']){
                            $end = $birthDate%10;
                            $suffix = ' лет';
                            if($end === 1 && $birthDate !== 11){
                                $suffix = " год";
                            }
                            else if(($end === 2 && $birthDate !== 12) || ( $end === 3 && $birthDate !== 13) || ( $end === 4 && $birthDate !== 14) ){
                                $suffix = " года";
                            }
                        }
                        */

                        $birthDate = $birthDate ? $birthDate : 'Не указан';
                        // $birthDate = $membersAll[$m]['age'] ? $birthDate.$suffix : 'Не указан';
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $birthDate);
                        break;
                    case 'male':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['male'] == 1 ? "Брат" : "Сестра");
                        break;
                    case 'attend_meeting':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['attend_meeting'] == 1 ? "Посещает" : "");
                        break;
                    case 'school':
                        $category = $membersAll[$m]['category_key'];
                        $age = (int)$membersAll[$m]['age'];
                        $isSchool = $category == "SC" || $category == "PS" || ( $age < 18 && $age > 6 );
                        $isCollege = $category == "ST" || ($category == "SC" && $age > 15) ;

                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $isCollege ? "" : ( $isSchool ? "Школа" : ""));
                        break;
                    case 'school_start':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['school_start']>0 ? $membersAll[$m]['school_start']: "");
                        break;
                    case 'school_end':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['school_end']>0 ? $membersAll[$m]['school_end']: "");
                        break;
                    case 'school_level':
                        $schoolLevel = $membersAll[$m]['school_level'];
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $schoolLevel > 0 && $schoolLevel <12 ? $schoolLevel : "");
                        break;
                    case 'school_comment':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['school_comment']);
                        break;
                    case 'college':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['college_name']);
                        break;
                    case 'college_start':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['college_start'] >0 ? $membersAll[$m]['college_start'] : "");
                        break;
                    case 'college_end':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['college_end']>0 ? $membersAll[$m]['college_end']: "");
                        break;
                    case 'college_level':
                        $currentYear = (int)date("Y");
                        $collegeStart = $membersAll[$m]['college_start'];
                        $collegeEnd = $membersAll[$m]['college_end'];
                        $courseLevel = $collegeStart ? $currentYear - $collegeStart + 1 : "";

                        if($collegeStart && $collegeEnd){
                            if($currentYear < $collegeStart){
                                $courseLevel = "планирует поступить";
                            }
                            else if($currentYear == $collegeEnd){
                                $courseLevel = $courseLevel + " курс, окончание в этом году";
                            }
                            else if($currentYear > $collegeEnd){
                                $courseLevel = "учёба завершена";
                            }
                            else{
                                $courseLevel = $courseLevel+" курс";
                            }
                        }

                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $courseLevel);
                        break;
                    case 'college_comment':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['college_comment']);
                        break;
                    case 'comment':
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['comment']);
                        break;
                }
                $ind ++;
            }
        }
        $i++;
        $num++;
    }

    $admin = $_POST['adminId'];
    $event = '-members';

    $file = $admin.$event;

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($file.'.xlsx');

    $filename = $file.'.xlsx';
    echo $filename;
}
else if (isset ($_POST ['members']) && isset ($_POST ['memberslength']) && isset($_POST['adminId']) && $_POST['page'] == 'reg' && !isset($_POST ['parking'])){
    $memberslength = $_POST ['memberslength'];
    $membersAll = json_decode($_POST ['members'], TRUE);
    $fields = isset($_POST['document']) ? json_decode( $_POST['document'], TRUE) : NULL;

    $fieldsCount = $fields['name']['arr_date'] ? count($fields)+1 : count($fields);
    $eventType = $_POST["event_type"];

    $title = (isset($_POST ['coord']))? ('Список координаторов') : (isset($_POST ['service'])? 'Список служащих' : 'Полный список');
    $num=1;

    $shouldTranslateFields =  $_POST['need_translation'] == 'yes';

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("PHP")
                    ->setLastModifiedBy("reg-page")
                    ->setTitle("Office 2007 XLSX")
                    ->setSubject("Office 2007 XLSX")
                    ->setDescription("Office 2007 XLSX, сгенерированный PHPExcel.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Тестовый файл");
    $objPHPExcel->getActiveSheet()->setTitle($title);

    $active_sheet = $objPHPExcel->getActiveSheet();

    $ind_count = 'C';
    if(isset($_POST ['all'])){
        $active_sheet->getColumnDimension('A')->setWidth(4);
        $active_sheet->getColumnDimension('B')->setWidth(40);

        for ($d=0; $d < $fieldsCount; $d++) {
            $active_sheet->getColumnDimension($ind_count)->setWidth(20);
            $ind_count ++;
        }
    }
    else{
        $active_sheet->getColumnDimension('A')->setWidth(4);
        $active_sheet->getColumnDimension('B')->setWidth(33);
        $active_sheet->getColumnDimension('C')->setWidth(15);
        $active_sheet->getColumnDimension('D')->setWidth(17);
        $active_sheet->getColumnDimension('E')->setWidth(23);
        $active_sheet->getColumnDimension('F')->setWidth(17);
        $active_sheet->getColumnDimension('G')->setWidth(15);
        $active_sheet->getColumnDimension('H')->setWidth(25);
        $active_sheet->getColumnDimension('I')->setWidth(14);
        $active_sheet->getColumnDimension('J')->setWidth(14);
        $active_sheet->getColumnDimension('K')->setWidth(14);
        $active_sheet->getColumnDimension('L')->setWidth(14);
        $active_sheet->getColumnDimension('M')->setWidth(30);
    }

    $style_wrap = array(
        'borders'=>array(
            'outline' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN
            ),
            'allborders'=>array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb'=>'696969'
                )
            )
        ),
        'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color'=>array(
                'rgb' => 'EEEEEE'
            )
        ),
        'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        )
    );

    $active_sheet->getStyle('A1:'.$ind_count.'1')->applyFromArray($style_wrap);

    function translitWords($str){
        $rus = array('/Rossiya/', '/Ukraina/', '/Moskva/', '/Sankt-Peterburg/', '/Rostov-na-Donu/', '/Украина/',
            '/Россия/', '/Армения/', '/Азербайджан/', '/Белоруссия/', '/Bulgaria/', '/Грузия/', '/Израиль/',
            '/Казахстан/', '/Киргизия/', '/Крым/', '/Латвия/', '/Литва/', '/Молдова/', '/Монголия/',
            '/Румыния/', '/Туркмения/', '/Узбекистан/',
            '/Эстония/', '/g\./', '/Krym/'
        );
        $lat = array('Russia','Ukraine','Moscow', 'St. Petersburg', 'Rostov-on-Don', 'Ukraine', 'Russia', 'Armenia',
           'Azerbaijan', 'Belarus', 'Bulgaria', 'Georgia', 'Israel', 'Kazakhstan', 'Kyrgyzstan', 'Crimea',
            'Latvia', 'Lithuania', 'Moldova','Mongolia','Romania', 'Turkmenistan', 'Uzbekistan', 'Estonia','', 'Crimea');
        return preg_replace ( $rus , $lat, $str);
    }

    function translitLetters($str) {
        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
        $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Kh', 'Ts', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'Eh', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'kh', 'ts', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'eh', 'yu', 'ya');
        return str_replace($rus, $lat, $str);
    }

    if(isset($_POST ['all'])){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '№')
            ->setCellValue('B1', $shouldTranslateFields ? 'Member' : 'ФИО');

        $ind = 'C';
        for ($d=0; $d < $fieldsCount; $d++) {
            switch ($fields[$d]['name']) {
                case 'birth_date':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Birth date' : 'Дата рождения');
                    break;
                case 'age':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Возраст');
                    break;
                case 'locality':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Locality' : 'Город');
                    break;
                case 'region':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Region' : 'Область');
                    break;
                case 'country':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Country' :'Страна');
                    break;
                case 'service':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Служение');
                    break;
                case 'coord':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Координатор');
                    break;
                case 'cell_phone':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Cellphone' :'Телефон');
                    break;
                case 'email':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Email');
                    break;
                case 'post':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Post address' : 'Почтовый адрес');
                    break;
                case 'arr_date':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Arrival date' : 'Дата приезда');
                    $ind ++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Departure date' :'Дата отъезда');
                    break;
                case 'arr_time':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Arrival time' : 'Время приезда');
                    $ind ++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Departure time' :'Время отъезда');
                    break;
                case 'regstate':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Состояние регистрации');
                    break;
                case 'mate':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Разместить с');
                    break;
                case 'status':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Статус');
                    break;
                case 'custom_item':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $fields[$d]['value']);
                    break;
                case 'document':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Паспортные данные');
                    break;
                case 'tp':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Name in a passport' : 'Имя в загранпаспорте'); $ind ++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Passport number' : 'Номер загранпаспорта'); $ind ++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Country of issue' : 'Страна выдачи загранпаспорта'); $ind ++;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Period of validity' : 'Срок действия загранпаспорта');
                    break;
                case 'english':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'English level' : 'Уровень английского');
                    break;
                case 'flight-arr':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Arrival flight' : 'Авиарейс прибытия');
                    break;
                case 'flight-dep':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Departure flight' : 'Авиарейс вылета');
                    break;
                case 'visa':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', $shouldTranslateFields ? 'Visa' : 'Виза');
                    break;
                case 'accom':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Размещение');
                    break;
                case 'transport':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Поездка (транспорт)');
                    break;
                case 'hotel':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Гостиница');
                    break;
                case 'admin-comment':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Комментарий администратора');
                    break;
                case 'comment':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Комментарий участника');
                    break;
                case 'paid':
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.'1', 'Внесённый взнос');
                    break;
            }
            $ind ++;
        }
    }
    else {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '№')
            ->setCellValue('B1', 'Участник')
            ->setCellValue('C1', 'Дата рождения')
            ->setCellValue('D1', 'Город')
            ->setCellValue('E1', 'Служение')
            ->setCellValue('F1', 'Координатор')
            ->setCellValue('G1', 'Телефон')
            ->setCellValue('H1', 'Email')
            ->setCellValue('I1', 'Дата приезда')
            ->setCellValue('J1', 'Дата отъезда')
            ->setCellValue('K1', 'Время приезда')
            ->setCellValue('L1', 'Время отъезда')
            ->setCellValue('M1', 'Состояние регистрации');
    }

    for($i=2, $m=0; $m<$memberslength; $m++){
        if((isset($_POST ['coord']))? ($membersAll[$m]['coord']=='1') : (isset($_POST ['service'])? $membersAll[$m]['service']!='' : "true")) {
            for($j=0; $j<9; $j++) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$i", $num)
                    ->setCellValue("B$i", $membersAll[$m]['name']);

                if ($membersAll[$m]['coord'] == "1") {
                    $coordinate = "Рекомендуется";
                } else {
                    $coordinate = "";
                }

                switch ($membersAll[$m]['regstate']) {
                    case '01':
                        $registrate = 'ожидание подтверждения';
                        break;
                    case '02':
                        $registrate = 'ожидание подтверждения';
                        break;
                    case '03':
                        $registrate = 'ожидание отмены';
                        break;
                    case '04':
                        $registrate = 'регистрация подтверждена';
                        break;
                    case '05':
                        $registrate = 'регистрация отменена';
                        break;
                    default :
                        $registrate = 'не зарегистрирован';
                }

                switch ($membersAll[$m]['visa']) {
                    case '1':
                        $visa = 'Не требуется';
                        break;
                    case '2':
                        $visa = 'Уже есть или получу для другой поездки';
                        break;
                    case '3':
                        $visa = 'Получу для этой поездки';
                        break;
                    default :
                        $visa = 'Не заполнено';
                }

                switch ($membersAll[$m]['english']) {
                    case '0':
                        $english = 'не владеет';
                        break;
                    case '1':
                        $english = 'начальный уровень';
                        break;
                    case '2':
                        $english = 'хороший уровень';
                        break;
                    default :
                        $english = 'не заполнено';
                }

                if (isset($_POST ['all'])) {
                    $ind = 'C';
                    foreach ($fields as $d) {
                      $abcdes = strval($membersAll[$m]['mate_key']);
                      $namesmembers = db_getMemberNameMate($abcdes);
                      $getStatus = db_getStatus($membersAll[$m]['status_key']);
                        $locality = explode(':', $membersAll[$m]['locality'])[0];
                        switch ($d['name']) {
                            case 'birth_date':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['birth_date']);
                                break;
                            case 'age':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, getAge($membersAll[$m]['birth_date']));
                                break;
                            case 'locality':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $shouldTranslateFields ? translitWords(translitLetters($locality)) : $locality);
                                break;
                            case 'region':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $shouldTranslateFields ? translitLetters($membersAll[$m]['region']) : $membersAll[$m]['region']);
                                break;
                            case 'country':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $shouldTranslateFields ? translitWords($membersAll[$m]['country']) : $membersAll[$m]['country']);
                                break;
                            case 'service':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['service']);
                                break;
                            case 'coord':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $coordinate);
                                break;
                            case 'cell_phone':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['cell_phone']);
                                break;
                            case 'email':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['email']);
                                break;
                            case 'post':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $shouldTranslateFields ? translitWords(translitLetters($membersAll[$m]['address'])) : $membersAll[$m]['address']);
                                break;
                            case 'arr_date':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['arr_date']); $ind ++;
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['dep_date']);
                                break;
                            case 'arr_time':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['arr_time']); $ind ++;
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['dep_time']);
                                break;
                            case 'regstate':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $registrate);
                                break;
                            case 'mate':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $namesmembers);
                                break;
                            case 'status':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $getStatus);
                                break;
                            case 'custom_item':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['reg_list_name']);
                                break;
                            case 'document':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['document_name']." ". $membersAll[$m]['document_num']." ".$membersAll[$m]['document_auth']." ".$membersAll[$m]['document_date']);
                                break;
                            case 'visa':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $visa);
                                break;
                            case 'accom':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['accom'] == '0' ? 'не требуется' : 'требуется');
                                break;
                            case 'comment':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['comment']);
                                break;
                            case 'paid':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['prepaid']);
                                break;
                            case 'admin-comment':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['admin_comment']);
                                break;
                            case 'hotel':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['note']);
                                break;
                            case 'transport':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['transport'] == '0' ? 'не требуется' : 'требуется');
                                break;
                            case 'flight-arr':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['flight_num_arr']);
                                break;
                            case 'flight-dep':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['flight_num_dep']);
                                break;
                            case 'english':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $english);
                                break;
                            case 'tp':
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['tp_name']); $ind ++;
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['tp_num']); $ind ++;
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['tp_auth']); $ind ++;
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ind.''.$i, $membersAll[$m]['tp_date']);
                                break;
                        }
                        $ind ++;
                    }
                }
                else{
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("C$i", $membersAll[$m]['birth_date'])
                        ->setCellValue("D$i", $membersAll[$m]['locality'])
                        ->setCellValue("E$i", $membersAll[$m]['service'])
                        ->setCellValue("F$i", $coordinate)
                        ->setCellValue("G$i", $membersAll[$m]['cell_phone'])
                        ->setCellValue("H$i", $membersAll[$m]['email'])
                        ->setCellValue("I$i", $membersAll[$m]['arr_date'])
                        ->setCellValue("J$i", $membersAll[$m]['dep_date'])
                        ->setCellValue("K$i", $membersAll[$m]['arr_time'])
                        ->setCellValue("L$i", $membersAll[$m]['dep_time'])
                        ->setCellValue("M$i", $registrate);
                }
            }
        $i++;
        $num++;
        }
    }

    $admin = $_POST['adminId'];
    $event = (isset($_POST ['coord']))? ('-coord') : (isset($_POST ['service'])? '-service' : '-all');

    $date = date('Y-m-d');
    $time = date('H:i');
    $file = $date.'_'.$eventType.' ('.$time.')';
    //$file = $admin.$event;

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($file.'.xlsx');

    $filename = $file.'.xlsx';
    echo $filename;

}
else if (isset($_POST ['members']) && ($_POST['page'] == 'meeting_members')) {
    $members = json_decode($_POST ['members'], TRUE);

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("PHP")
        ->setLastModifiedBy("reg-page")
        ->setTitle("Office 2007 XLSX")
        ->setSubject("Office 2007 XLSX")
        ->setDescription("Office 2007 XLSX, сгенерированный PHPExcel.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Тестовый файл");

    $active_sheet = $objPHPExcel->getActiveSheet();
    $active_sheet->setTitle('Статистика посещения');
    $active_sheet->getColumnDimension('A')->setWidth(4);
    $active_sheet->getColumnDimension('B')->setWidth(40);
    $active_sheet->getColumnDimension('C')->setWidth(15);

    $style_wrap = array(
        'borders'=>array(
            'outline' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN
            ),
            'allborders'=>array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb'=>'696969'
                )
            )
        ),
        'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color'=>array(
                'rgb' => 'EEEEEE'
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        )
    );

    $meetingsDates = [];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '№')
        ->setCellValue('B1', 'ФИО')
        ->setCellValue('C1', 'Местность');

    $rowIndex = 'D';
    $columnNumber=1;
    $rowNumber=2;

    $members_count = isset($_POST['members_count']) ? (int)$_POST['members_count'] : 0;
    $list_count = isset($_POST['list_count']) ? (int)$_POST['list_count'] : 0;
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

    // get dates for headers.
    foreach($members as $member){
        $meetings = explode(',', $member['meetings']);

        foreach($meetings as $meeting){
            $meetingDate = substr($meeting, 3);
            if(!in_array($meetingDate, $meetingsDates)){
                $meetingsDates [] = $meetingDate;
            }
        }
    }

    // sort dates by desc
    usort($meetingsDates, "sortFunction");

     // add dates to a spreadsheet's header
    foreach($meetingsDates as $meetingDate){
        $active_sheet->getColumnDimension($rowIndex)->setWidth(12);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($rowIndex.'1', $meetingDate);
        $rowIndex ++;
    }

    // add user info to a spreadsheet
    foreach($members as $member){
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$rowNumber", $columnNumber)
                ->setCellValue("B$rowNumber", $member['memberName'])
                ->setCellValue("C$rowNumber", $member['locality']);

        $meetings = explode(',', $member['meetings']);

        $rowIndexMeeting = 'D';
        foreach($meetingsDates as $meetingDate){
            foreach($meetings as $meeting){
                if($meetingDate == substr($meeting, 3)){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($rowIndexMeeting.''.$rowNumber, substr($meeting, 0,2) == 'LT' ? 'Трапеза' : 'Молитвенное');
                }
            }
            $rowIndexMeeting ++;
        }
        $rowNumber++;
        $columnNumber++;
    }
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowNumber, "По списку — ".$list_count." чел.");
    $rowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowNumber, "Участвовали в собраниях — ".$members_count." чел.");
    $rowNumber++;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowNumber, "Период (".$startDate." — ".$endDate." )");

    $active_sheet->getStyle('A1:'.$rowIndex.'1')->applyFromArray($style_wrap);

    $admin = isset($_POST['adminId']) ? $_POST['adminId'] : '';
    $event = '-meeting_members';
    $file = $admin.$event;

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($file.'.xlsx');

    $filename = $file.'.xlsx';
    echo $filename;
}
else if (isset($_POST ['list']) && ($_POST['page'] == 'meeting_general')) {
    $list = json_decode($_POST ['list'], TRUE);

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("PHP")
        ->setLastModifiedBy("reg-page")
        ->setTitle("Office 2007 XLSX")
        ->setSubject("Office 2007 XLSX")
        ->setDescription("Office 2007 XLSX, сгенерированный PHPExcel.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Тестовый файл");

    $active_sheet = $objPHPExcel->getActiveSheet();
    $active_sheet->setTitle('Статистика посещения');
    $active_sheet->getColumnDimension('A')->setWidth(12);
    $active_sheet->getColumnDimension('B')->setWidth(20);
    $active_sheet->getColumnDimension('C')->setWidth(25);
    $active_sheet->getColumnDimension('D')->setWidth(60);

    $style_wrap = array(
        'borders'=>array(
            'outline' => array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN
            ),
            'allborders'=>array(
                'style'=>PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb'=>'696969'
                )
            )
        ),
        'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color'=>array(
                'rgb' => 'EEEEEE'
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
        )
    );

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Дата')
        ->setCellValue('B1', 'Местность')
        ->setCellValue('C1', 'Вид собрания')
        ->setCellValue('D1', 'Количество святых');

    $meetingsDates = [];

    // get dates for headers.
    foreach($list as $item){
        $meetingsDates [] = $item['date'];
    }

    // sort dates by desc
    usort($meetingsDates, "sortFunction");

    $rowNumber=2;

    // add dates to a spreadsheet's column
    foreach($meetingsDates as $meetingDate){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$rowNumber", $meetingDate);
        $rowNumber ++;
    }

    $rowNumber=2;
    // add user info to a spreadsheet
    foreach($list as $item){
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B$rowNumber", $item['locality_name'])
                ->setCellValue("C$rowNumber", $item['meeting_name']);

        foreach($meetingsDates as $meetingDate){
            if($meetingDate == $item['date']){
                $fulltimersInSaintsList =  (int)array_reduce(explode(',', $item['fulltimers_in_list']), add);
                $listCount = (int)array_reduce(explode(',', $item['list_count']), add) + (int)($item['add_list_count']);
                $saintsCount = (int)array_reduce(explode(',', $item['saints_count']), add) - (int)$fulltimersInSaintsList;
                $guestCount = (int)array_reduce(explode(',', $item['guests_count']), add);
                $childrenCount = (int)array_reduce(explode(',', $item['children_count']), add);

                $fulltimersCount = 0;
                $traineesCount = 0;

                if($item.show_additions == '1'){
                    $fulltimersCount = (int)array_reduce(explode(',', $item['fulltimers_count']), add);
                    $traineesCount = (int)array_reduce(explode(',', $item['trainees_count']), add);
                }

                $countMembers = $saintsCount + $guestCount + $fulltimersCount + $traineesCount;

                $value = "Общее количество: ".$countMembers." "." Количество по списку: ".$listCount." "." Количество святых из списка: ".$saintsCount." Количество гостей: ".$guestCount." Количество детей: ".$childrenCount." Количество служащих: ".$fulltimersCount." Количество обучающихся: ".$traineesCount;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$rowNumber, $value);
            }
        }
        $rowNumber++;
    }

    $active_sheet->getStyle('A1:D1')->applyFromArray($style_wrap);

    $admin = isset($_POST['adminId']) ? $_POST['adminId'] : '';
    $event = '-meeting_members';
    $file = $admin.$event;

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($file.'.xlsx');

    $filename = $file.'.xlsx';
    echo $filename;
}
else if(isset($_GET['upload_file'])){
    if ( 0 < $_FILES['file']['error'] ) {
        echo json_encode(array ("res"=>'error'));
    }
    else {
        $path_to_file = $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $path_to_file);
        $res = handle_excel_file($path_to_file, $_GET['admin_id']);
        unlink ($path_to_file);

        echo json_encode(array ("res"=>$res));
    }
    exit;
}

function handle_excel_file($path_to_file, $adminId){
    $objPHPExcel = PHPExcel_IOFactory::load($path_to_file);

    //$list = [];
    try{
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;

            $desiredFields = ['Фамилия', 'Имя', 'Отчество', 'Пол', 'Дата рождения', 'Местность', 'Состояние', 'Трапеза', 'Дата крещения'];
            $nameFields = ['Фамилия', 'Имя', 'Отчество'];
            $desiredFieldIndexes = [];

            for ($row = 1; $row <= $highestRow; ++ $row) {
                $row_data = [];
                $member = [];
                $fullMemberName = [];

                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();

                    if($row == 1 and in_array(trim($val), $desiredFields)){
                        $desiredFieldIndexes[$col] = $val;
                    }
                    else if(array_key_exists ($col, $desiredFieldIndexes)){
                        if(in_array($desiredFieldIndexes[$col], $nameFields)){
                            array_push($fullMemberName, $val);
                        }
                        $member[$desiredFieldIndexes[$col]] = $val;
                    }
                }

                if(count($member) > 0){
                    $member['ФИО'] = join($fullMemberName, ' ');
                    handleMember($member, $adminId);
                }
            }
        }
        return true;
    }
    catch (Exception $e){
        return false;
    }
}

function handleMember($member, $adminId){
    $name = null;
    $locality_key = null;
    $locality_name = null;
    $gender = null;
    $birth_date = null;
    $category_key = null;
    $attend_meeting = null;
    $baptize_date = null;

    foreach ($member as $key => $value) {
        if($key == 'ФИО'){
            $name = $value;
        }
        else if($key == 'Местность'){
            $locality_key = db_getLocalityKeyByName($value);
            if($locality_key == null){
                $locality_name = $value;
            }
        }
        else if($key == 'Пол'){
            $gender = $value == 'сестра' || $value == 'Сестра' ? 0 : 1;
        }
        else if($key == 'Дата крещения'){
            $baptize_date = trim($value) ? strftime("%Y-%m-%d",strtotime ($value)) : null;
        }
        else if($key == 'Дата рождения'){
            $birth_date = trim($value) ? strftime("%Y-%m-%d",strtotime ($value)): null;
        }
        else if($key == 'Состояние'){
            if($value == 'В церковной жизни'){
                $age = getAge($member['Дата рождения']);
                if($age < 17){
                    $category_key = 'SC';
                }
                else{
                    $category_key = 'SN';
                }
            }
            else if($value == 'Остыл' || $value == 'Контакт'){
                $category_key = 'BL';
            }
            else if($value == 'ПВОМ'){
                $category_key = 'FT';
            }
            else{
                $category_key = 'OT';
            }
        }
        else if($key == 'Трапеза'){
            $attend_meeting = $value == 'да' || $value == 'Да' || $value == 1 || $value == '1' ? 1 : 0;
        }
        $citizenship = 'UA';
    }

    db_addNewMember ($name, $locality_key, $gender, $birth_date, $category_key, $attend_meeting, $locality_name, $citizenship,
        $baptize_date, $adminId);
}

function add($a, $b) {
    return (int)($a) + (int)($b);
}

function sortFunction( $a, $b ) {
    return strtotime($b) - strtotime($a);
}
