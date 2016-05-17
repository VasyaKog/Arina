<?php

/**
 * Class for generation excel documents
 * To add a new document template you need to create a method
 * with name witch begin from makeAliasOfDocument
 * and can use it:
 * <pre>
 * $excel = Yii::app()->getComponent('excel');
 * $object = new SomeObjectForDocument
 * $excel->getDocument($object, 'aliasOfDocument');
 * </pre>
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
class ExcelMaker extends CComponent
{
    /**
     * @var string alias of path to directory with templates
     */
    public $templatesPath = 'public.files.templates';
    public $tmp_id;

    /**
     * Load PHPExcel
     */
    public function init()
    {
        $phpExcelPath = Yii::getPathOfAlias('vendor.phpexcel.phpexcel.Classes');
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        spl_autoload_register(array('YiiBase', 'autoload'));
    }

    public function rome($num)
    {
        $n = intval($num);
        $res = '';

        //array of roman numbers
        $romanNumber_Array = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1);

        foreach ($romanNumber_Array as $roman => $number) {
            //divide to get  matches
            $matches = intval($n / $number);

            //assign the roman char * $matches
            $res .= str_repeat($roman, $matches);

            //substract from the number
            $n = $n % $number;
        }

        // return the result
        return $res;
    }

    /**
     * Call method for generation current document
     * @param mixed $data source for document
     * @param $name
     * @throws CException
     */
    public function getDocument($data, $name)
    {
        $methodName = 'make' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            $objPHPExcel = $this->$methodName($data);
            $docName = "$name " . date("d.m.Y G-i", time());
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $docName . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
            $objWriter->save('php://output');
        } else {
            throw new CException(Yii::t('error', 'Method "{method}" not found', array('{method}' => $methodName)));
        }
    }

    public function makeSchedule($data)
    {
        $objPHPExcel = $this->loadTemplate('schedule.xls');
        /** @var StudyYear $studyYear */
        $studyYear = StudyYear::model()->findByPk($data['id']);
        $id = $data['id'];
        $semester = $data['semester'];
        $sql = <<<SQL
SELECT
    `tim`.`id`,
    `tim`.`study_year_id`,
    `tim`.`semester`,
    `tim`.`para`,
    `tim`.`day`,
    `tim`.`group_id`,
    `tim`.`subject_id`,
    `sb`.`title` AS `subject`,
    `tim`.`teacher1_id`,
    `tim`.`teacher2_id`,
    `tim`.`audience1_id`,
    `tim`.`audience2_id`,
    `tim`.`type`
FROM
    `timetable` `tim`
        INNER JOIN
    `subject` `sb` ON `tim`.`subject_id` = `sb`.`id`
WHERE
    `tim`.`study_year_id` = $id
        AND `tim`.`semester` = "$semester"
ORDER BY `tim`.`day`, `tim`.`para` , `tim`.`type` ASC
SQL;

        $cmd = Yii::app()->db->createCommand($sql);
        $data = $cmd->queryAll();
        $sql = <<<SQL
SELECT
    `l`.`group_id`,
    `g`.`title` AS `group`,
    `l`.`course`
FROM
    `wp_plan` `pl`
        INNER JOIN
    (`subject` `sb`
    INNER JOIN (`wp_subject` `wp`
    INNER JOIN (`group` `g`
    INNER JOIN (`load` `l`
    INNER JOIN `employee` `t` ON `l`.`teacher_id` = `t`.`id`) ON `g`.`id` = `l`.`group_id`) ON `wp`.`id` = `l`.`wp_subject_id`) ON `sb`.`id` = `wp`.`subject_id`) ON `pl`.`id` = `wp`.`plan_id`
WHERE
    `l`.`study_year_id` = $id
group By `g`.`id`
ORDER BY `l`.`course` , `g`.`title` , `t`.`last_name` ASC
SQL;

        $cmd = Yii::app()->db->createCommand($sql);
        $groups = $cmd->queryAll();

        $sql = "SELECT `id`,`last_name`,`first_name`,`middle_name` FROM `employee`";
        $cmd = Yii::app()->db->createCommand($sql);
        $teachers = $cmd->queryAll();

        $sql = "SELECT `id`,`number` FROM `audience`";
        $cmd = Yii::app()->db->createCommand($sql);
        $audiences = $cmd->queryAll();

        $list = array();
        foreach ($groups as $group) {
            if (!isset($list[$group['course']])) {
                $list[$group['course']] = array();
            }
            $list[$group['course']][$group['group_id']] = $group['group'];
        }

        $arr_data = array();
        foreach ($list as $key => $ls)
            foreach ($ls as $k => $g) {
                if (!isset($arr_data[$key])) {
                    $arr_data[$key] = array();
                }

                $tmp_arr = array();
                foreach ($data as $dat) {
                    $tmp = array();
                    if ($k == $dat['group_id']){
                        $tmp['para'] = $dat['para'];
                        $tmp['day'] = $dat['day'];
                        $tmp['subject'] = $dat['subject'];
                        $teach2_perevirka = false;
                        foreach ($teachers as $teach){
                            if($dat['teacher1_id']==$teach['id']) $tmp['teacher1'] = $teach['last_name'].' '.substr($teach['first_name'],0,2).'. '.substr($teach['middle_name'],0,2).'.';

                            if(($dat['teacher2_id']==$teach['id'])&&($dat['teacher2_id']!='')) {$tmp['teacher2'] = $teach['last_name'].' '.substr($teach['first_name'],0,2).'. '.substr($teach['middle_name'],0,2).'.'; $teach2_perevirka = true;}
                        }
                        if ($teach2_perevirka==false) $tmp['teacher2'] = '';
                        $aud1_perevirka = false; $aud2_perevirka = false;
                        foreach ($audiences as $aud){
                            if(($dat['audience1_id']==$aud['id'])&&($dat['audience1_id']!='')) {$tmp['audience1'] = $aud['number']; $aud1_perevirka=true;}
                            if(($dat['audience2_id']==$aud['id'])&&($dat['audience2_id']!='')) {$tmp['audience2'] = $aud['number']; $aud2_perevirka=true;}
                        }
                        if ($aud1_perevirka==false) $tmp['audience1'] = '';
                        if ($aud2_perevirka==false) $tmp['audience2'] = '';
                        $tmp['type'] = $dat['type'];
                        $tmp['double'] = false;
                        $tmp_arr[] = $tmp;
                    }
                }

                $ind = array();
                for($i = 0; $i<count($tmp_arr)-1; $i++){
                    if(($tmp_arr[$i]['day']==$tmp_arr[$i+1]['day'])&&($tmp_arr[$i]['para']==$tmp_arr[$i+1]['para'])&&($tmp_arr[$i]['subject']==$tmp_arr[$i+1]['subject'])&&($tmp_arr[$i]['teacher1']==$tmp_arr[$i+1]['teacher1'])&&($tmp_arr[$i]['teacher2']==$tmp_arr[$i+1]['teacher2'])&&($tmp_arr[$i]['type']==0)){
                        $tmp_arr[$i]['type'] = '';
                        $tmp_arr[$i]['double'] = true;
                        $ind[] = $i+1;
                    }
                }
                foreach ($ind as $i) unset($tmp_arr[$i]);

                $arr_data[$key][$k] = $tmp_arr;
            }

       /* echo "<pre>";
        print_r($arr_data);
        echo "</pre>";
        die;*/

        $objWorkSheetBase = $objPHPExcel->getSheet(0);


        for ($i = 1; $i <= count($list); $i++) {
            $objWorkSheet1 = clone $objWorkSheetBase;
            $objWorkSheet1->setTitle($i . ' курс');
            $objPHPExcel->addSheet($objWorkSheet1);
        }
        $objPHPExcel->removeSheetByIndex(0);


        for ($i = 1; $i <= count($list); $i++) { //course
            $sheet = $objPHPExcel->setActiveSheetIndex($i - 1);
            $value = $sheet->getCell('H3')->getCalculatedValue();
            $value = str_replace('<years>', $studyYear->begin . ' - ' . $studyYear->end, $value);
            $value = str_replace('<semester>', $this->rome($semester == 'fill' ? 1 : 2), $value);
            $value = str_replace('<course>', $this->rome($i), $value);
            $sheet->setCellValue('H3', $value);
            $j = 0;
            foreach ($list[$i] as $id => $group) { //group
                $row = 12;
                $from = 4 + 3 * $j;
                $col = PHPExcel_Cell::stringFromColumnIndex($from); //E
                $end = PHPExcel_Cell::stringFromColumnIndex($from + 2); //G
                $sheet->setCellValue($col . $row, $group);
                $sheet->mergeCells("$col$row:$end$row");
                $style = $sheet->getStyle("$col$row:$end$row");
                $style->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $style->getFont()->setBold(true);
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                        )
                    )
                );
                $style->applyFromArray($styleArray);
                $row++;
                $count_para = 0;
                for($f = 0; $f<20; $f++){
                    if($count_para>3) $count_para=0;
                    switch ($count_para) {
                        case 0:
                            $para = "1-2";
                            break;
                        case 1:
                            $para = "3-4";
                            break;
                        case 2:
                            $para = "5-6";
                            break;
                        case 3:
                            $para = "7-8";
                            break;
                    }
                    $day = $this->getDay($f);
                    $tmp_data = array();
                    foreach($arr_data[$i][$id] as $dat)
                        if(($dat['day']==$day)&&($dat['para']==$para)) $tmp_data[] = $dat;

                    if(count($tmp_data)==1){
                        $sheet->setCellValue($col . $row, $tmp_data[0]['subject']);
                        $sheet->mergeCells("$col$row:$end".($row+1));
                        $sheet->getStyle("$col$row:$end".($row+1))->getFont()->setBold(true);

                        $sheet->setCellValue($col . ($row+2), $tmp_data[0]['teacher1']);
                        $sheet->getStyle($col . ($row+2))->getFont()->setItalic(true);
                        $endTeach = PHPExcel_Cell::stringFromColumnIndex($from + 1);
                        $sheet->mergeCells("$col".($row+2).":$endTeach".($row+2));

                        $sheet->setCellValue($end . ($row+2), $tmp_data[0]['audience1']);

                        $sheet->setCellValue($col . ($row+3), $tmp_data[0]['teacher2']);
                        $sheet->getStyle($col . ($row+3))->getFont()->setItalic(true);
                        $sheet->mergeCells("$col".($row+3).":$endTeach".($row+3));
                        $sheet->setCellValue($end . ($row+3), $tmp_data[0]['audience2']);

                        $style = $sheet->getStyle("$col$row:$end".($row+3));
                        $style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    }else if(count($tmp_data)==2){
                        $teacer1_names = ''; $teacer2_names='';
                        if($tmp_data[0]['teacher2']!='') $teacer1_names=stristr($tmp_data[0]['teacher1'], ' ', true).'-'.stristr($tmp_data[0]['teacher2'], ' ', true);
                        else $teacer1_names=$tmp_data[0]['teacher1'];

                        if($tmp_data[1]['teacher2']!='') $teacer2_names=stristr($tmp_data[1]['teacher1'], ' ', true).'-'.stristr($tmp_data[1]['teacher2'], ' ', true);
                        else $teacer2_names=$tmp_data[1]['teacher1'];

                        $audits1_numbers = ''; $audits2_numbers='';
                        if(($tmp_data[0]['audience1']!='')&&($tmp_data[0]['audience2']!='')) $audits1_numbers=$tmp_data[0]['audience1'].'-'.$tmp_data[0]['audience2'];
                        else $audits1_numbers=$tmp_data[0]['audience1'].$tmp_data[0]['audience2'];

                        if(($tmp_data[1]['audience1']!='')&&($tmp_data[1]['audience2']!='')) $audits2_numbers=$tmp_data[1]['audience1'].'-'.$tmp_data[1]['audience2'];
                        else $audits2_numbers=$tmp_data[1]['audience1'].$tmp_data[1]['audience2'];

                        $sheet->setCellValue($col . $row, $tmp_data[0]['subject']);
                        $sheet->getStyle("$col$row:$end$row")->getFont()->setBold(true);
                        $sheet->mergeCells("$col$row:$end$row");

                        $sheet->setCellValue($col . ($row+1), $teacer1_names);
                        $sheet->getStyle($col . ($row+1))->getFont()->setItalic(true);
                        $endTeach = PHPExcel_Cell::stringFromColumnIndex($from + 1);
                        $sheet->mergeCells("$col".($row+1).":$endTeach".($row+1));

                        $sheet->setCellValue($end . ($row+1), $audits1_numbers);
                        $sheet->getStyle("$col".($row+1).":$end".($row+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                        $sheet->setCellValue($col . ($row+2), $tmp_data[1]['subject']);
                        $sheet->getStyle($col . ($row+2))->getFont()->setBold(true);
                        $sheet->mergeCells("$col".($row+2).":$end".($row+2));

                        $sheet->setCellValue($col . ($row+3), $teacer2_names);
                        $sheet->getStyle($col . ($row+3))->getFont()->setItalic(true);
                        $sheet->mergeCells("$col".($row+3).":$endTeach".($row+3));

                        $sheet->setCellValue($end . ($row+3), $audits2_numbers);
                    }

                    $style = $sheet->getStyle("$col$row:$end".($row+3));
                    $styleArray = array(
                        'borders' => array(
                            'right' => array(
                                'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                            ),
                            'bottom' => array(
                                'style' => PHPExcel_Style_Border::BORDER_MEDIUM
                            )
                        )
                    );
                    $style->applyFromArray($styleArray);

                    $count_para++;
                    $row = $row+4;

                }

                $j++;

                //@TODO classes


            }

        }

        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

    private function getDay($id){
        $result = null;
        if (($id>=0)&&($id<4)) $result=1;
        if (($id>=4)&&($id<8)) $result=2;
        if (($id>=8)&&($id<12)) $result=3;
        if (($id>=12)&&($id<16)) $result=4;
        if (($id>=16)&&($id<20)) $result=5;
        return $result;
    }

    private function checkDenumerator(){

    }

    /**
     *
     * @param $data Load[]
     * @return PHPExcel
     */

    public function makeActualSchedule($data){
        $date = $data['date'];
        $objPHPExcel = $this->loadTemplate('replacements.xls');
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        /** @var StudyYear $studyYear */

        $sql = <<<SQL
SELECT
    `sh`.`id`,
    `sh`.`date`,
    `sh`.`para`,
    `sh`.`day`,
    `sh`.`group_id`,
    `sh`.`subject_id`,
    `sb`.`title` AS `subject`,
    `sh`.`teacher1_id`,
    `sh`.`teacher2_id`,
    `sh`.`audience1_id`,
    `sh`.`audience2_id`,
    `sh`.`type`
FROM
     `actual_shedule` `sh`
        INNER JOIN
     `subject` `sb` ON `sh`.`subject_id` = `sb`.`id`
WHERE
     `sh`.`date`  = '$date'
ORDER BY `sh`.`para`, `sh`.`type` ASC
SQL;
        $cmd = Yii::app()->db->createCommand($sql);
        $data_actualShedule = $cmd->queryAll();
        if (count($data_actualShedule)>0){
            $type = $data_actualShedule[0]['type'];
            $day = $data_actualShedule[0]['day'];

            $sql = "SELECT * FROM `study_year`";
            $cmd = Yii::app()->db->createCommand($sql);
            $arr_years = $cmd->queryAll();
            $arr_date = explode('-', $date);
            $id_stud_year = '';
            if (($arr_date[1]>=9)&&($arr_date[1]<=12)) {
                foreach($arr_years as $stud_yea)
                    if($stud_yea['begin']==$arr_date[0]) {$id_stud_year=$stud_yea['id']; break;}
                $semester = 'fill';
            }
            else{
                foreach($arr_years as $stud_yea)
                    if($stud_yea['end']==$arr_date[0]) {$id_stud_year=$stud_yea['id']; break;}
                $semester = 'spring';
            }

            $sql = <<<SQL
            SELECT
              `tim`.`id`,
              `tim`.`para`,
              `tim`.`day`,
              `tim`.`group_id`,
              `tim`.`subject_id`,
              `sb`.`title` AS `subject`,
              `tim`.`teacher1_id`,
              `tim`.`teacher2_id`,
              `tim`.`audience1_id`,
              `tim`.`audience2_id`,
              `tim`.`type`
            FROM
              `timetable` `tim`
                INNER JOIN
              `subject` `sb` ON `tim`.`subject_id` = `sb`.`id`
            WHERE
             `tim`.`study_year_id` = $id_stud_year
             AND `tim`.`semester` = "$semester"
             AND `tim`.`day` = $day
             AND `tim`.`type` = $type
              ORDER BY `tim`.`day`, `tim`.`para` , `tim`.`type` ASC
SQL;
            $cmd = Yii::app()->db->createCommand($sql);
            $arr_timetable = $cmd->queryAll();

            foreach ($data_actualShedule as $key1 => $actual){
                $data_actualShedule[$key1]['by_teacher1'] = ''; $data_actualShedule[$key1]['by_teacher2'] = '';
                $uns = false;
                foreach($arr_timetable as $key2 => $timetabl){
                    if(($timetabl['para']==$actual['para'])&&($timetabl['day']==$actual['day'])&&($timetabl['group_id']==$actual['group_id'])&&($timetabl['subject_id']==$actual['subject_id'])&&($timetabl['teacher1_id']==$actual['teacher1_id'])&&($timetabl['teacher2_id']==$actual['teacher2_id'])&&($timetabl['audience1_id']==$actual['audience1_id'])&&($timetabl['audience2_id']==$actual['audience2_id'])){
                        unset($arr_timetable[$key2]); $uns = true; break;
                    } else {
                        if(($timetabl['para']==$actual['para'])&&($timetabl['day']==$actual['day'])&&($timetabl['group_id']==$actual['group_id'])&&($timetabl['subject_id']!=$actual['subject_id'])) {
                            $data_actualShedule[$key1]['by_teacher1'] = $timetabl['teacher1_id'];  $data_actualShedule[$key1]['by_teacher2'] = $timetabl['teacher2_id'];
                            unset($arr_timetable[$key2]); break;
                        }
                        if(($timetabl['para']==$actual['para'])&&($timetabl['day']==$actual['day'])&&($timetabl['group_id']==$actual['group_id'])&&($timetabl['subject_id']==$actual['subject_id'])&&(($timetabl['audience1_id']!=$actual['audience1_id'])||($timetabl['audience2_id']!=$actual['audience2_id']))) {
                            unset($arr_timetable[$key2]); break;
                        }

                    }
                }
                if ($uns) {unset($data_actualShedule[$key1]);  continue;}
            }

            if (count($arr_timetable)>0){
                foreach($arr_timetable as $timetabl){
                    $tmp = array();
                    $tmp['date'] = $date;
                    $tmp['para'] = $timetabl['para'];
                    $tmp['day'] = $timetabl['day'];
                    $tmp['group_id'] = $timetabl['group_id'];
                    $tmp['subject_id'] = ''; $tmp['subject']="Немає пари"; $tmp['teacher1_id'] = ''; $tmp['teacher2_id'] = ''; $tmp['audience1_id'] = ''; $tmp['audience2_id'] = '';
                    $tmp['type'] = $timetabl['type']; $tmp['by_teacher1'] = $timetabl['teacher1_id'];  $tmp['by_teacher2'] = $timetabl['teacher2_id'];
                    $add = false;
                    foreach($data_actualShedule as $key1 => $actual){
                        if($timetabl['para']==$actual['para']) {
                            $add = true;
                            array_splice($data_actualShedule, $key1, 0, array($tmp));
                            break;
                        }
                    }
                    if ($add==false) $data_actualShedule[]=$tmp;
                }
            }

            $sql = <<<SQL
SELECT
    `l`.`group_id`,
    `g`.`title` AS `group`,
    `l`.`course`
FROM
    `wp_plan` `pl`
        INNER JOIN
    (`subject` `sb`
    INNER JOIN (`wp_subject` `wp`
    INNER JOIN (`group` `g`
    INNER JOIN (`load` `l`
    INNER JOIN `employee` `t` ON `l`.`teacher_id` = `t`.`id`) ON `g`.`id` = `l`.`group_id`) ON `wp`.`id` = `l`.`wp_subject_id`) ON `sb`.`id` = `wp`.`subject_id`) ON `pl`.`id` = `wp`.`plan_id`
WHERE
    `l`.`study_year_id` = $id_stud_year
group By `g`.`id`
ORDER BY `l`.`course` , `g`.`title` ASC
SQL;

            $cmd = Yii::app()->db->createCommand($sql);
            $groups = $cmd->queryAll();

            $sql = "SELECT `id`,`last_name`,`first_name`,`middle_name` FROM `employee`";
            $cmd = Yii::app()->db->createCommand($sql);
            $teachers = $cmd->queryAll();

            $sql = "SELECT `id`,`number` FROM `audience`";
            $cmd = Yii::app()->db->createCommand($sql);
            $audiences = $cmd->queryAll();

            $list = array();
            foreach ($groups as $group) {
                if (!isset($list[$group['course']])) {
                    $list[$group['course']] = array();
                }
                $list[$group['course']][$group['group_id']] = $group['group'];
            }

            foreach ($list as $key => $ls)
                foreach ($ls as $k => $g){
                    $arr_tmp=array();
                    foreach($data_actualShedule as $dat){
                        if($dat['group_id']==$k){
                            $tmp = array();
                            $tmp['group'] = $g;
                            $tmp['para'] = $dat['para'];
                            $tmp['subject'] = $dat['subject'];
                            $teacher1 = ''; $teacher2 = '';
                            $by_teacher1 = ''; $by_teacher2 = '';
                            foreach($teachers as $teach){
                                if (($dat['teacher1_id']!='')&&($dat['teacher1_id']==$teach['id'])) $teacher1=$teach['last_name'];
                                if (($dat['teacher2_id']!='')&&($dat['teacher2_id']==$teach['id'])) $teacher2=$teach['last_name'];
                                if (($dat['by_teacher1']!='')&&($dat['by_teacher1']==$teach['id'])) $by_teacher1=$teach['last_name'];
                                if (($dat['by_teacher2']!='')&&($dat['by_teacher2']==$teach['id'])) $by_teacher2=$teach['last_name'];
                            }

                            $aud1 = ''; $aud2 = '';
                            foreach($audiences as $audit){
                                if (($dat['audience1_id']!='')&&($dat['audience1_id']==$audit['id'])) $aud1=$audit['number'];
                                if (($dat['audience2_id']!='')&&($dat['audience2_id']==$audit['id'])) $aud2=$audit['number'];
                            }
                            $tmp['teach_title'] = '';
                            if ($teacher2!='') $tmp['teach_title']=$teacher1.' - '.$teacher2;
                            else $tmp['teach_title']=$teacher1;
                            $tmp['kogo'] = '';
                            if ($by_teacher2!='') $tmp['kogo']=$by_teacher1.' - '.$by_teacher2;
                            else $tmp['kogo']=$by_teacher1;
                            $tmp['auditor_title'] = '';
                            if ($aud2!='') $tmp['auditor_title']=$aud1.' - '.$aud2;
                            else $tmp['auditor_title']=$aud1;
                            $arr_tmp[]=$tmp;
                        }
                    }
                    $list[$key][$k]=$arr_tmp;
                }

            $str_mount = '';
            switch($arr_date[1]){
                case 1: $str_mount = "січня"; break; case 2: $str_mount = "лютого"; break; case 3: $str_mount = "березня"; break;
                case 4: $str_mount = "квітня"; break; case 5: $str_mount = "травня"; break; case 6: $str_mount = "червня"; break;
                case 7: $str_mount = "липня"; break; case 8: $str_mount = "серпня"; break; case 9: $str_mount = "вересня"; break;
                case 10: $str_mount = "жовтня"; break; case 11: $str_mount = "листопада"; break; case 12: $str_mount = "грудня"; break;

            }

            $str_day = '';
            $str_type = '';
            switch($day){
                case 1: $str_day = "Понеділок"; break; case 2: $str_day = "Вівторок"; break; case 3: $str_day = "Середа"; break;
                case 4: $str_day = "Четвер"; break; case 5: $str_day = "Пятниця"; break;
            }

            if ($type == 0) $str_type='Чисельник'; else $str_type='Знаменник';

            $str_date = $arr_date[2].' '.$str_mount.' '.$arr_date[0];
            $str_info = $str_day.' - '.$str_type;

            $this->setValue($sheet, 'D2', $str_date, '@value');
            $sheet->setCellValue("D3", $str_info);

            $row = 6;

            foreach ($list as $key => $ls)
                foreach ($ls as $k => $g)
                    foreach($g as $key => $tmp){
                        if($key==0) $sheet->setCellValue("B$row", $tmp['group']);
                        $sheet->setCellValue("C$row", $tmp['para']);
                        $sheet->setCellValue("D$row", $tmp['kogo']);
                        $sheet->setCellValue("E$row", $tmp['subject']);
                        $sheet->setCellValue("F$row", $tmp['teach_title']);
                        $sheet->setCellValue("G$row", $tmp['auditor_title']);
                        $sheet->insertNewRowBefore($row + 1, 1);
                        $row++;
                    }
            $sheet->removeRow($row);
        }
        return $objPHPExcel;
    }

    public function makeScheduleTeachers($data){
        $objPHPExcel = $this->loadTemplate('schedule_teachers.xls');
        /** @var StudyYear $studyYear */
        $studyYear = StudyYear::model()->findByPk($data['id']);
        $id = $data['id'];
        $semester = $data['semester'];
        $sql = <<<SQL
SELECT
    `tim`.`id`,
    `tim`.`study_year_id`,
    `tim`.`semester`,
    `tim`.`para`,
    `tim`.`day`,
    `tim`.`group_id`,
    `g`.`title` AS `group`,
    `tim`.`subject_id`,
    `sb`.`short_name` AS `subject`,
    `tim`.`teacher1_id`,
    `tim`.`teacher2_id`,
    `tim`.`audience1_id`,
    `tim`.`audience2_id`,
    `tim`.`type`
FROM
    `subject` `sb`
        INNER JOIN
     (`timetable` `tim` INNER JOIN `group` `g` ON `tim`.`group_id` = `g`.`id`) ON `tim`.`subject_id` = `sb`.`id`
WHERE
    `tim`.`study_year_id` = $id
        AND `tim`.`semester` = "$semester"
ORDER BY `tim`.`day`, `tim`.`para` , `tim`.`type` ASC
SQL;

        $cmd = Yii::app()->db->createCommand($sql);
        $data = $cmd->queryAll();

        $sql = <<<SQL
SELECT
    `t`.`id`,
    `t`.`last_name`,
    `t`.`first_name`,
    `t`.`middle_name`
FROM
    `timetable` `tim` INNER JOIN `employee` `t` ON `tim`.`teacher1_id` = `t`.`id`
WHERE
    `tim`.`study_year_id` = $id
        AND `tim`.`semester` = "$semester"
GROUP BY `t`.`id`
ORDER BY `t`.`last_name` ASC
SQL;
        $cmd = Yii::app()->db->createCommand($sql);
        $teach1 = $cmd->queryAll();

        $sql = <<<SQL
SELECT
    `t`.`id`,
    `t`.`last_name`,
    `t`.`first_name`,
    `t`.`middle_name`
FROM
    `timetable` `tim` INNER JOIN `employee` `t` ON `tim`.`teacher2_id` = `t`.`id`
WHERE
    `tim`.`study_year_id` = $id
        AND `tim`.`semester` = "$semester"
GROUP BY `t`.`id`
ORDER BY `t`.`last_name` ASC
SQL;
        $cmd = Yii::app()->db->createCommand($sql);
        $teach2 = $cmd->queryAll();

        $sql = "SELECT `id`,`number` FROM `audience`";
        $cmd = Yii::app()->db->createCommand($sql);
        $audiences = $cmd->queryAll();

        $tmp_arr = array_merge($teach1,$teach2);
        $arr_teacher = array();
        foreach ($tmp_arr as $tmp){
            $arr_teacher[$tmp['id']] = $tmp['last_name'].' '.$tmp['first_name'].' '.$tmp['middle_name'];
        }
        asort($arr_teacher);

        foreach($arr_teacher as $key=>$teach){
            $tmp_arr = array('name'=>$teach);
            $arr_predmet_numerator = array();
            $arr_predmet_denumerator = array();
            foreach($data as $key2 => $dat){
                if(($dat['teacher1_id']==$key)||($dat['teacher2_id']==$key)){
                    $tmp = array();
                    $tmp['para'] = $dat['para'];
                    $tmp['day'] = $dat['day'];
                    if($dat['teacher1_id']==$key)
                        foreach ($audiences as $auditor) if($auditor['id']==$dat['audience1_id']) {$tmp['audience'] = $auditor['number']; break;}
                    if($dat['teacher2_id']==$key)
                        foreach ($audiences as $auditor) if($auditor['id']==$dat['audience2_id']) {$tmp['audience'] = $auditor['number']; break;}
                    $tmp['subject'] = $dat['subject'];
                    $tmp['group'] = $dat['group'];
                    if($dat['type']==0) $arr_predmet_numerator[] = $tmp;
                    else $arr_predmet_denumerator[] = $tmp;
                }

            }
            $tmp_arr['data_numerator'] = $arr_predmet_numerator;
            $tmp_arr['data_denumerator'] = $arr_predmet_denumerator;
            $arr_teacher[$key] = $tmp_arr;
        }

        $objWorkSheetBase = $objPHPExcel->getSheet(0);
        $objWorkSheetBase->setTitle('Чисельник');
        $objWorkSheet1 = clone $objWorkSheetBase;
        $objWorkSheet1->setTitle('Знаменник');
        $objPHPExcel->addSheet($objWorkSheet1);

        $sheet1 = $objPHPExcel->setActiveSheetIndex(0);
        $sheet2 = $objPHPExcel->setActiveSheetIndex(1);
        $row = 6;
        foreach($arr_teacher as $teach){
            $sheet1->setCellValue("B$row", $teach['name']);
            $sheet2->setCellValue("B$row", $teach['name']);
            foreach ($teach['data_denumerator'] as $dat){
                if(($dat['para']=='1-2')&&($dat['day']==1))  {$sheet2->setCellValue("C$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("C$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==1))  {$sheet2->setCellValue("D$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("D$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==1))  {$sheet2->setCellValue("E$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("E$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==1))  {$sheet2->setCellValue("F$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("F$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==2))  {$sheet2->setCellValue("G$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("G$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==2))  {$sheet2->setCellValue("H$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("H$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==2))  {$sheet2->setCellValue("I$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("I$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==2))  {$sheet2->setCellValue("J$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("J$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==3))  {$sheet2->setCellValue("K$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("K$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==3))  {$sheet2->setCellValue("L$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("L$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==3))  {$sheet2->setCellValue("M$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("M$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==3))  {$sheet2->setCellValue("N$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("N$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==4))  {$sheet2->setCellValue("O$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("O$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==4))  {$sheet2->setCellValue("P$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("P$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==4))  {$sheet2->setCellValue("Q$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("Q$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==4))  {$sheet2->setCellValue("R$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("R$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==5))  {$sheet2->setCellValue("S$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("S$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==5))  {$sheet2->setCellValue("T$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("T$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==5))  {$sheet2->setCellValue("U$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("U$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==5))  {$sheet2->setCellValue("V$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet2->getStyle("V$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
            }

            foreach ($teach['data_numerator'] as $dat){
                if(($dat['para']=='1-2')&&($dat['day']==1))  {$sheet1->setCellValue("C$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("C$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==1))  {$sheet1->setCellValue("D$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("D$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==1))  {$sheet1->setCellValue("E$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("E$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==1))  {$sheet1->setCellValue("F$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("F$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==2))  {$sheet1->setCellValue("G$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("G$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==2))  {$sheet1->setCellValue("H$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("H$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==2))  {$sheet1->setCellValue("I$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("I$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==2))  {$sheet1->setCellValue("J$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("J$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==3))  {$sheet1->setCellValue("K$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("K$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==3))  {$sheet1->setCellValue("L$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("L$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==3))  {$sheet1->setCellValue("M$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("M$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==3))  {$sheet1->setCellValue("N$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("N$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==4))  {$sheet1->setCellValue("O$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("O$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==4))  {$sheet1->setCellValue("P$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("P$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==4))  {$sheet1->setCellValue("Q$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("Q$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==4))  {$sheet1->setCellValue("R$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("R$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }

                if(($dat['para']=='1-2')&&($dat['day']==5))  {$sheet1->setCellValue("S$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("S$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='3-4')&&($dat['day']==5))  {$sheet1->setCellValue("T$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("T$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='5-6')&&($dat['day']==5))  {$sheet1->setCellValue("U$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("U$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
                if(($dat['para']=='7-8')&&($dat['day']==5))  {$sheet1->setCellValue("V$row", $dat['group'].' '.$dat['subject'].' ('.$dat['audience'].')'); $sheet1->getStyle("V$row")->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FDD495')))); }
            }

            $sheet1->insertNewRowBefore($row + 2, 1);
            $sheet2->insertNewRowBefore($row + 2, 1);
            $row = $row+1;
        }

        $sheet1->removeRow($row); $sheet1->removeRow($row);
        $sheet2->removeRow($row); $sheet2->removeRow($row);

        return $objPHPExcel;
    }

    public function makeLoad($data)
    {
        $objPHPExcel = $this->loadTemplate('load.xls');
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $row = 6;
        $i = 1;
        foreach ($data as $load) {

            $springSemester = $load->course * 2 - 1;
            $fallSemester = $springSemester - 1;

            $sheet->setCellValue("A$row", $i);
            $subject = $load->planSubject->subject;
            $sheet->setCellValue("B$row", $subject->code);
            $sheet->setCellValue("C$row", $subject->title);
            $sheet->setCellValue("D$row", (isset($load->teacher) ? $load->teacher->getNameWithInitials() : 'непризначено'));
            $group = $load->group;
            $sheet->setCellValue("E$row", $group->getCourse($load->study_year_id));
            $sheet->setCellValue("F$row", $group->title);
            $sheet->setCellValue("G$row", $load->getStudentsCount());
            $sheet->setCellValue("H$row", $load->getBudgetStudentsCount());
            $sheet->setCellValue("I$row", $load->getContractStudentsCount());
            $sheet->setCellValue("J$row", $load->getPlanCredits());
            $sheet->setCellValue("K$row", $load->getPlanTotal());

            $sheet->setCellValue("L$row", $load->getTotal($fallSemester));
            $sheet->setCellValue("N$row", $load->getPlanClasses());
            $sheet->setCellValue("O$row", $load->getSelfwork($fallSemester));
            $sheet->setCellValue("P$row", $load->getClasses($fallSemester));
            $sheet->setCellValue("Q$row", $load->getLectures($fallSemester));
            $sheet->setCellValue("S$row", $load->getLabs($fallSemester));
            $sheet->setCellValue("U$row", $load->getPracts($fallSemester));
            $sheet->setCellValue("Y$row", $load->getProject($fallSemester));
            $sheet->setCellValue("Z$row", $load->getCheck($fallSemester));
            $sheet->setCellValue("AA$row", $load->getControl($fallSemester));
            $sheet->setCellValue("AB$row", $load->getControlWorks($fallSemester));
            $sheet->setCellValue("AC$row", $load->getDkk($fallSemester));
            $sheet->setCellValue("AD$row", $load->getConsultation($fallSemester));
            $sheet->setCellValue("AE$row", $load->getExam($fallSemester));
            $sheet->setCellValue("AF$row", $load->getTest($fallSemester));
            $sheet->setCellValue("AG$row", $load->getPay($fallSemester));

            $sheet->setCellValue("AH$row", $load->getTotal($springSemester));
            $sheet->setCellValue("AI$row", $load->getSelfWork($springSemester));
            $sheet->setCellValue("AJ$row", $load->getClasses($springSemester));
            $sheet->setCellValue("AK$row", $load->getLectures($springSemester));
            $sheet->setCellValue("AL$row", $load->getLabs($springSemester));
            $sheet->setCellValue("AM$row", $load->getPracts($springSemester));
            $sheet->setCellValue("AO$row", $load->getProject($springSemester));
            $sheet->setCellValue("AP$row", $load->getCheck($springSemester));
            $sheet->setCellValue("AQ$row", $load->getControl($springSemester));
            $sheet->setCellValue("AR$row", $load->getControlWorks($springSemester));
            $sheet->setCellValue("AS$row", $load->getDkk($springSemester));
            $sheet->setCellValue("AT$row", $load->getConsultation($springSemester));
            $sheet->setCellValue("AU$row", $load->getExam($springSemester));
            $sheet->setCellValue("AV$row", $load->getTest($springSemester));
            $sheet->setCellValue("AW$row", $load->getPay($springSemester));
            $all = $load->getPay($fallSemester) + $load->getPay($springSemester);
            $sheet->setCellValue("AX$row", $all);
            $sheet->setCellValue("AY$row", round($all * $load->getBudgetPercent() / 100));
            $sheet->setCellValue("AZ$row", round($all * $load->getContractPercent() / 100));

            $sheet->getStyle("A$row:AZ$row")->applyFromArray(self::getBorderStyle());
            $i++;
            $row++;
            $sheet->insertNewRowBefore($row + 1, 1);
        }

        $sheet->setCellValue("D$row", 'Всього');
        $last = $row - 1;
        for ($c = 6; $c < 45; $c++) {
            $index = PHPExcel_Cell::stringFromColumnIndex($c);
            $sheet->setCellValue("$index$row", "=SUM({$index}6:$index$last)");
        }
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

    /**
     * @param Group $group
     * @return mixed
     */
    public function makeSimpleGroupList($group)
    {
        $objPHPExcel = $this->loadTemplate('5.03.xls');
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setCellValue('A10', Settings::getValue('name'));
        $this->setValue($sheet, 'A13', $group->speciality->department->title);
        $this->setValue($sheet, 'A14', $group->speciality->title);
        $this->setValue($sheet, 'A15', $group->getCourse());
        $this->setValue($sheet, 'C15', $group->title);
        $this->setValue($sheet, 'C17', GlobalHelper::getCurrentYear(1), '@value1');
        $this->setValue($sheet, 'C17', GlobalHelper::getCurrentYear(2), '@value2');
        $k = $i = 40;
        foreach ($group->students as $item) {
            /**@var Student $item */
            $sheet->setCellValue("A$i", $i - $k + 1);
            $sheet->setCellValue("B$i", $item->getShortFullName());
            $sheet->insertNewRowBefore($i + 1, 1);
            $i++;
        }
        $sheet->removeRow($i);
        return $objPHPExcel;
    }

    /**
     * @param $group
     * @return PHPExcel
     */
    public function makeGroupList($group)
    {
        $objPHPExcel = $this->loadTemplate('simple_group_list.xls');
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setCellValue('A10', Settings::getValue('name'));
        $this->setValue($sheet, 'A13', $group->speciality->department->title);
        $this->setValue($sheet, 'A14', $group->speciality->title);
        $this->setValue($sheet, 'A15', $group->getCourse());
        $this->setValue($sheet, 'C15', $group->title);
        $this->setValue($sheet, 'C17', GlobalHelper::getCurrentYear(1), '@value1');
        $this->setValue($sheet, 'C17', GlobalHelper::getCurrentYear(2), '@value2');
        $k = $i = 24;
        foreach ($group->students as $item) {
            /**@var Student $item */
            $sheet->setCellValue("A$i", $i - $k + 1);
            $sheet->setCellValue("B$i", $item->getShortFullName());
            $sheet->setCellValue("C$i", ($item->contract ? 'к' : ''));
            $sheet->insertNewRowBefore($i + 1, 1);
            $i++;
        }
        $sheet->removeRow($i);
        return $objPHPExcel;
    }

    /**
     * Load template document
     * @param $alias
     * @param string $fileType version of template
     * @return PHPExcel
     * @throws CException
     */
    protected function loadTemplate($alias, $fileType = 'Excel5')
    {
        $fileName = Yii::getPathOfAlias($this->templatesPath) . DIRECTORY_SEPARATOR . $alias;
        if (file_exists($fileName)) {
            $objReader = PHPExcel_IOFactory::createReader($fileType);
            $objPHPExcel = $objReader->load($fileName);
            return $objPHPExcel;
        } else {
            throw new CException(Yii::t('error', 'Template "{name}" not found', array('{name}' => $alias)));
        }
    }

    /**
     * Find alias in cell and replace it into current value
     * @param PHPExcel_Worksheet $sheet
     * @param $cell
     * @param $value
     * @param string $alias
     */
    public function setValue($sheet, $cell, $value, $alias = '@value')
    {
        $sheet->setCellValue($cell, str_replace($alias, $value, $sheet->getCell($cell)->getCalculatedValue()));
    }

    protected static function getBorderStyle()
    {
        return array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
    }

    /**
     * @param $model GroupDocForm
     * @return PHPExcel
     */
    protected function makeExamSheet($model)
    {
        $objPHPExcel = $this->loadTemplate('exam.xls');
        $sheet = $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $sheet->setCellValue('A15', $model->teacher);
        $sheet->setCellValue('A5', $model->group->speciality->department->head->getFullName());
        $sheet->setCellValue('A11', $model->subject->title);
        $sheet->setCellValue('F14', $model->group->title);
        $sheet->setCellValue('B14', $model->semester);
        $sheet->setCellValue('D14', $model->getCourse());

        for ($i = 0; $i < count($model->group->students); $i++) {
            $sheet->setCellValue('A' . (19 + $i), $i + 1);
            $sheet->setCellValue('B' . (19 + $i), $model->group->students[$i]->fullName);
            $sheet->insertNewRowBefore($i + 20, 1);
        }
        $sheet->removeRow($i + 20);
        $sheet->setCellValue('D' . (20 + $i), '=average(D19:D' . ($i + 19) . ')');
        $sheet->setCellValue('E' . (25 + $i), $model->teacher);
        $sheet->setCellValue('B' . (27 + $i), 'Дата:' . $model->date);
        return $objPHPExcel;
    }

    /**
     * @param $data TeacherDocument
     * @return PHPExcel
     */
    protected function makeTeacherList($data)
    {
        $objPHPExcel = $this->loadTemplate('teacherList.xls');

        return $objPHPExcel;
    }

    /**
     * Generate study plan document
     * @param $plan StudyPlan
     * @return PHPExcel
     */
    protected function makeStudyPlan($plan)
    {
        $objPHPExcel = $this->loadTemplate('plan.xls');

        //SHEET #1
        $sheet = $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setCellValue("F19", $plan->speciality->number . ' ' . $plan->speciality->title);
        $sheet->setCellValue("W13", $plan->speciality->discipline);
        $sheet->setCellValue("E16", $plan->speciality->direction);
        $sheet->setCellValue("AS11", $plan->speciality->qualification);
        $sheet->setCellValue("AT13", $plan->speciality->apprenticeship);
        $sheet->setCellValue("F25", $plan->speciality->education_form);

        // table #1
        for ($i = 0; $i < count($plan->graph); $i++) {
            $char = 'B';
            for ($j = 0; $j < count($plan->graph[$i]); $j++) {
                $sheet->setCellValue($char . ($i + 32), Yii::t('plan', $plan->graph[$i][$j]));
                $char++;
            }
        }

        // table #2
        $i = 46;
        $totals = array(
            'T' => 0,
            'P' => 0,
            'DA' => 0,
            'DP' => 0,
            'H' => 0,
            'S' => 0,
            ' ' => 0,
        );
        foreach ($plan->graph as $item) {
            $result = array_count_values($item);
            foreach ($result as $key => $value) {
                $totals[$key] += $value;
            }

            $sheet->setCellValue('A' . $i, $i - 45);
            if (isset($result['S'])) {
                $sheet->setCellValue('E' . $i, $result['S']);
            }
            if (isset($result['P'])) {
                $sheet->setCellValue('G' . $i, $result['P']);
            }
            if (isset($result['DA'])) {
                $sheet->setCellValue('I' . $i, $result['DA']);
            }
            if (isset($result['DP'])) {
                $sheet->setCellValue('K' . $i, $result['DP']);
            }
            if (isset($result['T'])) {
                $sheet->setCellValue('C' . $i, $result['T']);
            }
            if (isset($result['H'])) {
                $sheet->setCellValue('M' . $i, $result['H']);
            }
            if (isset($result[' '])) {
                $sheet->setCellValue('P' . $i, 52 - $result[' ']);
            } else {
                $sheet->setCellValue('P' . $i, 52);
            }
            $sheet->getStyle("A$i:R$i")->applyFromArray(self::getBorderStyle());
            $i++;
        }
        $sheet->setCellValue('A' . $i, 'Разом');
        $sheet->setCellValue('E' . $i, $totals['S']);
        $sheet->setCellValue('G' . $i, $totals['P']);
        $sheet->setCellValue('I' . $i, $totals['DA']);
        $sheet->setCellValue('K' . $i, $totals['DP']);
        $sheet->setCellValue('C' . $i, $totals['T']);
        $sheet->setCellValue('M' . $i, $totals['H']);
        $sheet->setCellValue('P' . $i, 52 * count($plan->graph) - $totals[' ']);
        $sheet->getStyle("A$i:R$i")->applyFromArray(self::getBorderStyle());

        // table #3 / table #4
        $i = 46;
        $z = 46;
        foreach ($plan->subjects as $item) {
            if ($item->subject->practice) {
                $sheet->setCellValue('T' . $i, $item->subject->title);
                $sheet->setCellValue('AG' . $i, $item->practice_weeks);
                for ($j = 0; $j < count($item->control); $j++) {
                    if ($item->control[$j][0]) {
                        $sheet->setCellValue("AF$i", $j + 1);
                    }
                }
                $sheet->getStyle("T$i:AH$i")->applyFromArray(self::getBorderStyle());
                $i++;
            }
            for ($k = 0; $k < count($item->control); $k++) {
                $semester = $item->control[$k];
                $list = array(2 => 'ДПА', 3 => 'ДА');
                foreach ($list as $key => $name) {
                    if ($semester[$key]) {
                        $sheet->setCellValue("AJ$z", $item->subject->title);
                        $sheet->setCellValue("AT$z", $name);
                        $sheet->setCellValue("BC$z", $k + 1);
                        $sheet->getStyle("AJ$z:BC$z")->applyFromArray(self::getBorderStyle());
                        $z++;
                    }
                }
            }

        }

        //SHEET #2
        $sheet = $sheet = $objPHPExcel->setActiveSheetIndex(2);

        $j = 'N';
        $i = 8;
        foreach ($plan->semesters as $item) {
            $sheet->setCellValue($j . $i, $item);
            $j++;
        }
        $i++;
        $j = 1;
        $totals = array();
        foreach ($plan->getSubjectsByCycles() as $name => $group) {
            $sheet->setCellValue("B$i", $name);
            $sheet->insertNewRowBefore($i + 1, 1);
            $i++;
            $begin = $i;
            $jj = 1;
            foreach ($group as $item) {
                /**@var $item StudySubject */
                $sheet->setCellValue("A$i", $item->subject->code);
                $sheet->setCellValue("B$i", $item->subject->getCycle($plan->speciality_id)->id . '.' . $jj . $item->getTitle());
                $sheet->setCellValue("C$i", $item->getExamSemesters());
                $sheet->setCellValue("D$i", $item->getTestSemesters());
                $sheet->setCellValue("E$i", $item->getWorkSemesters());
                $sheet->setCellValue("F$i", $item->getProjectSemesters());
                $sheet->setCellValue("G$i", round($item->total / 54, 2));
                $sheet->setCellValue("H$i", $item->total);
                $sheet->setCellValue("I$i", $item->getClasses());
                $sheet->setCellValue("J$i", $item->lectures);
                $sheet->setCellValue("K$i", $item->labs);
                $sheet->setCellValue("L$i", $item->practs);
                $sheet->setCellValue("M$i", $item->getSelfwork());
                $char = 'N';
                foreach ($item->weeks as $key => $week) {
                    $sheet->setCellValue($char . $i, $week);
                    $char++;
                }
                $sheet->insertNewRowBefore($i + 1, 1);
                $i++;
                $jj++;
            }
            $end = $i - 1;
            $sheet->setCellValue("B$i", Yii::t('base', 'Total'));
            $totals[] = $i;
            for ($c = 'G'; $c < 'V'; $c++) {
                $sheet->setCellValue("$c$i", "=SUM($c$begin:$c$end)");
            }
            $sheet->insertNewRowBefore($i + 1, 1);
            $i++;
            $j++;
        }
        $sheet->setCellValue("B$i", Yii::t('base', 'Total amount'));
        for ($c = 'G'; $c < 'V'; $c++) {
            $sheet->setCellValue("$c$i", "=SUM($c" . implode("+$c", $totals) . ')');
        }
        return $objPHPExcel;
    }

    /**
     * @param $plan WorkPlan
     * @return PHPExcel
     */
    protected function makeWorkPlan($plan)
    {
        $objPHPExcel = $this->loadTemplate('work.xls');

        $coursesAmount = $plan->getCourseAmount();
        $groupsByCourse = $plan->speciality->getGroupsByStudyYear($plan->year_id);
        $graphOffset = 0;
        for ($i = 0; $i < $coursesAmount; $i++) {
            $groups = array();
            foreach ($groupsByCourse as $group => $course) {
                if ($course == $i + 1) {
                    $groups[] = $group;
                }
            }
            $sheet = $sheet = $objPHPExcel->setActiveSheetIndex($i);
            $this->makeWorkPlanPage($plan, $i + 1, $sheet, $groups, $graphOffset);
            $graphOffset += count($groups);
        }
        $objPHPExcel->setActiveSheetIndex(0);
        return $objPHPExcel;
    }

    /**
     * @param $plan WorkPlan
     * @param $course
     * @param $sheet PHPExcel_Worksheet
     * @param $groups
     * @param $graphOffset
     */
    protected function makeWorkPlanPage($plan, $course, $sheet, $groups, $graphOffset)
    {
        $this->setValue($sheet, 'R8', $course);

        $sheet->setCellValue('R3', Settings::getValue('name'));
        $beginYear = $plan->year->begin;
        $endYear = $plan->year->end;
        $this->setValue($sheet, 'R5', $beginYear, '@begin');
        $this->setValue($sheet, 'R5', $endYear, '@end');
        $this->setValue($sheet, 'Y17', $course);
        $this->setValue($sheet, 'AS17', $course + 1);
        $sheet->setCellValue('AP17', $plan->semesters[$course - 1]);
        $sheet->setCellValue('BJ17', $plan->semesters[$course]);
        $specialityFullName = $plan->speciality->number . ' "' . $plan->speciality->title . '"';
        $this->setValue($sheet, 'R6', $specialityFullName);
        //groups graph;
        $colNumber = PHPExcel_Cell::columnIndexFromString('G');
        for ($i = 0; $i < count($groups); $i++) {
            $rowIndex = $i + 11;
            $sheet->setCellValue("G$rowIndex", $groups[$i]);
            for ($j = 0; $j < 52; $j++) {
                $colString = PHPExcel_Cell::stringFromColumnIndex($colNumber + $j);
                $k = $i + $graphOffset;
                if (isset($plan->graph[$k][$j])) {
                    $sheet->setCellValue($colString . $rowIndex, Yii::t('plan', $plan->graph[$k][$j]));
                }
            }
            $sheet->getStyle("G$rowIndex:BG$rowIndex")->applyFromArray(self::getBorderStyle());
        }

        //hours table
        switch ($course) {
            case 1:
                $fall = 0;
                $spring = 1;
                break;
            case 2:
                $fall = 2;
                $spring = 3;
                break;
            case 3:
                $fall = 4;
                $spring = 5;
                break;
            case 4:
                $fall = 6;
                $spring = 7;
                break;
            default:
                $fall = 0;
                $spring = 1;
        }

        $row = 23;
        $i = 0;
        $id = 1;
        $totals = array();
        $subjectsGroups = $plan->getSubjectsByCycles($course);
        foreach ($subjectsGroups as $cycle => $subjects) {

            $sheet->setCellValue("C$row", $cycle);
            $sheet->setCellValue("A$row", $id++);


            $i++;
            $row++;

            $this->workPlanInsertNewLine($sheet, $row);


            $j = 0;
            $begin = $row;
            foreach ($subjects as $item) {


                /**@var $item WorkSubject */
                $sheet->setCellValue("B$row", $item->subject->code);
                $sheet->setCellValue("A$row", $id++);
                $sheet->setCellValue("C$row", $item->subject->getCycle($plan->speciality_id)->id . '.' . ($j + 1) . ' ' . $item->getTitle());
                $sum = array_sum(isset($item->subject) ? $item->total : array());
                $sheet->setCellValue("O$row", $sum / 54);
                $sheet->setCellValue("Q$row", $sum);
                //FALL
                $sheet->setCellValue("Y$row", $item->total[$fall]);
                $sheet->setCellValue("AA$row", $item->getClasses($fall));
                $sheet->setCellValue("AC$row", $item->lectures[$fall]);
                $sheet->setCellValue("AE$row", $item->labs[$fall]);
                $sheet->setCellValue("AG$row", $item->practs[$fall]);
                $sheet->setCellValue("AI$row", $item->getSelfwork($fall));
                $sheet->setCellValue("AK$row", ($item->control[$fall][4] || $item->control[$fall][5]) ? $item->project_hours : '');
                $sheet->setCellValue("AN$row", $item->weeks[$fall]);
                $sheet->setCellValue("AO$row", (($item->control[$fall][1]) ? 1 : ''));
                $sheet->setCellValue("AQ$row", (($item->control[$fall][0]) ? 1 : ''));

                //SPRING
                $sheet->setCellValue("AS$row", $item->total[$spring]);
                $sheet->setCellValue("AU$row", $item->getClasses($spring));
                $sheet->setCellValue("AW$row", $item->lectures[$spring]);
                $sheet->setCellValue("AY$row", $item->labs[$spring]);
                $sheet->setCellValue("BA$row", $item->practs[$spring]);
                $sheet->setCellValue("BC$row", $item->getSelfwork($spring));
                $sheet->setCellValue("BE$row", ($item->control[$spring][4] || $item->control[$spring][5]) ? $item->project_hours : '');
                $sheet->setCellValue("BH$row", $item->weeks[$spring]);
                $sheet->setCellValue("BI$row", (($item->control[$spring][1]) ? 1 : ''));
                $sheet->setCellValue("BK$row", (($item->control[$spring][0]) ? 1 : ''));

                //CYCLE COMMISSION

                $sheet->setCellValue("BM$row", $item->cycleCommission->title);

                $j++;
                $row++;

                $this->workPlanInsertNewLine($sheet, $row);

            }

            $end = $row - 1;

            $sheet->setCellValue("C$row", Yii::t('base', 'Total'));
            $totals[] = $row;
            for ($c = 14; $c < 45; $c++) {
                $index = PHPExcel_Cell::stringFromColumnIndex($c);
                $sheet->setCellValue("$index$row", "=SUM($index$begin:$index$end)");
            }

            $row++;

            $this->workPlanInsertNewLine($sheet, $row);
        }
        $sheet->removeRow($row);
        $sheet->setCellValue("C$row", 'Разом');

        for ($c = 14; $c < 45; $c++) {
            $index = PHPExcel_Cell::stringFromColumnIndex($c);
            $sheet->setCellValue("$index$row", "=SUM($index" . implode("+$index", $totals) . ')');
        }
        $row += 6;
        $this->setValue($sheet, "AD$row", $plan->speciality->department->head->getFullName());
    }

    /**
     * @param $sheet PHPExcel_Worksheet
     * @param $row
     */
    public function workPlanInsertNewLine($sheet, $row)
    {
        $sheet->insertNewRowBefore($row, 1);
        $sheet->mergeCells("C$row:N$row");
        $exclude = array(32, 38, 52, 58);
        for ($i = 14; $i < 66; $i += 2) {
            if (in_array($i, $exclude)) continue;
            $index1 = PHPExcel_Cell::stringFromColumnIndex($i);
            $index2 = PHPExcel_Cell::stringFromColumnIndex($i + 1);
            $sheet->mergeCells("$index1$row:$index2$row");
        }
    }
    
    public function makeEmployeeCard()
    {
        $id = Yii::app()->session['curent_id_employee'];
        unset(Yii::app()->session['curent_id_employee']);
        $employee=Employee::model()->findByPk($id);
        $objPHPExcel = $this->loadTemplate('fe.xls');
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setCellValue("A2",'Хмельницький політехнічний коледж НУ"ЛП"');

        //curent_time
        $sheet->setCellValue("A13", yii::app()->dateFormatter->format('dd - MMMM - yyyy', date('dd - MMMM - yyyy')));

        //gender
        if($employee->gender==1){ $gender = 'жіноча'; }else{$gender='чоловіча';}
        $sheet->setCellValue("CW13",$gender);

        //pib and nationality
        $sheet->setCellValue("W19", $employee->last_name);
        $sheet->setCellValue("CJ19",$employee->first_name);
        $sheet->setCellValue("FL19",$employee->middle_name);
        $sheet->setCellValue("EG20",$employee->nationality);

        //date_birth
        $sheet->setCellValue("AO20", yii::app()->dateFormatter->format('dd', $employee->birth_date) );
        $sheet->setCellValue("AX20", yii::app()->dateFormatter->format('MMMM', $employee->birth_date) );
        $sheet->setCellValue("CC20", yii::app()->dateFormatter->format('yyyy', $employee->birth_date) );

        

        //education
        if($employee->education==0){$education='Відсутня';}
        if($employee->education==1){$education='Базова вища';}
        if($employee->education==2){$education='Повна загальна середня';}
        if($employee->education==3){$education='Професіно-технічна';}
        if($employee->education==4){$education='Неповна вища';}
        if($employee->education==5){$education='Базова вища';}
        if($employee->education==6){$education='Повна вища';}
        $sheet->setCellValue("A22", $education); 
        //educations_list
        $educations_list = $employee->educations_list;
        $rows=25;
        $col=0;
        $column=0;
        $previous=0;
        for($i = 0; $i<strlen($educations_list); $i++)
        {
            if(!strcasecmp(substr($educations_list,$i,1),","))
                {
                    if($column!=1 && $column!=2 )
                    {
                        $arr[$col]=substr($educations_list, $previous, $i-$previous); 
                        $column=0; 
                        $col++;
                        $previous=$i+1;
                    } 
                    if($col<=1)$column++;
                }
            if(!strcasecmp(substr($educations_list,$i,1),"|") )
                {
                    $arr[$col]=substr($educations_list, $previous, $i-$previous);
                    $previous=$i+1; 
                    $col=0; 
                    $column=0;
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("DK".$rows,$arr[1]);
                    $sheet->setCellValue("GI".$rows,$arr[2]);    
                    $sheet->setCellValue("A".($rows+7),$arr[3]);
                    $sheet->setCellValue("DK".($rows+7),$arr[4]);
                    $sheet->setCellValue("GI".($rows+7),$arr[5]);  
                    $rows++;
                }
            if($i==(strlen($educations_list)-1))
            {
                 $arr[$col]=substr($educations_list, $previous, $i-$previous);
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("DK".$rows,$arr[1]);
                    $sheet->setCellValue("GI".$rows,$arr[2]);    
                    $sheet->setCellValue("A".($rows+7),$arr[3]);
                    $sheet->setCellValue("DK".($rows+7),$arr[4]);
                    $sheet->setCellValue("GI".($rows+7),$arr[5]);    
            }
        }



        //postgraduete training
        if($employee->postgraduate_training==1){$sheet->setCellValue("CI37","X");}
        if($employee->postgraduate_training==2){$sheet->setCellValue("DJ37","X");}
        if($employee->postgraduate_training==3){$sheet->setCellValue("EG37","X");}
        $postgraduate_trainings = $employee->postgraduate_trainings;
        $rows=41;
        $col=0;
        $column=0;
        $previous=0;
        for($i = 0; $i<strlen($postgraduate_trainings); $i++)
        {
            if(!strcasecmp(substr($postgraduate_trainings,$i,1),","))
                {
                    if($column!=1 && $column!=2 )
                    {
                        $arr[$col]=substr($postgraduate_trainings, $previous, $i-$previous); 
                        $column=0; 
                        $col++;
                        $previous=$i+1;
                    } 
                    if($col<=1)$column++;
                }
            if(!strcasecmp(substr($postgraduate_trainings,$i,1),"|") )
                {
                    $arr[$col]=substr($postgraduate_trainings, $previous, $i-$previous);
                    $previous=$i+1; 
                    $col=0; 
                    $column=0;
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("BT".$rows,$arr[1]);
                    $sheet->setCellValue("EL".$rows,$arr[2]);    
                    $sheet->setCellValue("FV".$rows,$arr[3]);
                    $rows++;
                }
            if($i==(strlen($postgraduate_trainings)-1))
            {
                 $arr[$col]=substr($postgraduate_trainings, $previous, $i-$previous);
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("BT".$rows,$arr[1]);
                    $sheet->setCellValue("EL".$rows,$arr[2]);    
                    $sheet->setCellValue("FV".$rows,$arr[3]);
            }
        }



        //experience
        $sheet->setCellValue("AV45", yii::app()->dateFormatter->format('dd', $employee->start_date) );
        $sheet->setCellValue("BE45", yii::app()->dateFormatter->format('MMMM', $employee->start_date) );
        $sheet->setCellValue("CM45", yii::app()->dateFormatter->format('yyyy', $employee->start_date) );
        $sheet->setCellValue("FL45",$employee->experience_years);
        $sheet->setCellValue("EP45",$employee->experience_months);
        $sheet->setCellValue("DZ45",$employee->experience_days);
        $sheet->setCellValue("DZ46","-");
        $sheet->setCellValue("EP46","-");
        $sheet->setCellValue("FL46","-");

        //last job
        $sheet->setCellValue("AR44",$employee->last_job);

        //position
        $sheet->setCellValue("EW44", $employee->last_job_position);

        //passport
        if(strlen($employee->passport_issued_by)<54){$sheet->setCellValue("FM63",$employee->passport_issued_by);}else
        {
            $sheet->setCellValue("FM63",substr($employee->passport_issued_by,0,54)."-");
            $sheet->setCellValue("A64",substr($employee->passport_issued_by,54,strlen($employee->passport_issued_by)));
            
        }
        $sheet->setCellValue("CX63", substr($employee->passport,0,4));
        $sheet->setCellValue("DL63", substr($employee->passport,4,strlen($employee->passport)));

        //dismissal
        $sheet->setCellValue("AA48", yii::app()->dateFormatter->format('dd', $employee->dismissal_date) );
        $sheet->setCellValue("AJ48", yii::app()->dateFormatter->format('MMMM', $employee->dismissal_date) );
        $sheet->setCellValue("BR48", yii::app()->dateFormatter->format('yyyy', $employee->dismissal_date) );        
        if($employee->dismissal_reason==1){$sheet->setCellValue("CG48", "Скорочення штатів");}
        if($employee->dismissal_reason==2){$sheet->setCellValue("CG48","За власним бажаням");}
        if($employee->dismissal_reason==3){$sheet->setCellValue("CG48","За прогули");}
        if($employee->dismissal_reason==4){$sheet->setCellValue("CG48","Інші порушення");}

        //pension
        $sheet->setCellValue("A50",$employee->pension_data);

        //family
        $sheet->setCellValue("AG51", $employee->family_status);
        $family_data = $employee->family_data;
        $rows=55;
        $col=0;
        $previous=0;
        for($i = 0; $i<strlen($family_data); $i++)
        {
            if(!strcasecmp(substr($family_data,$i,1),",")){$arr[$col]=substr($family_data, $previous, $i-$previous); $col++;$previous=$i+1;}
            if(!strcasecmp(substr($family_data,$i,1),"|") )
                {
                    $arr[$col]=substr($family_data, $previous, $i-$previous);
                    $previous=$i+1; 
                    $col=0; 
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("AY".$rows,$arr[1]);
                    $sheet->setCellValue("GC".$rows,$arr[2]);    
                    $rows++;
                }
            if($i==(strlen($family_data)-1))
            {
                    $arr[$col]=substr($family_data, $previous, $i+1);                    
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("AY".$rows,$arr[1]);
                    $sheet->setCellValue("GC".$rows,$arr[2]);    
            }
        }


        //place of residence
        $sheet->setCellValue("AF61", $employee->place_of_residence);

        //place country registration
        if(strlen($employee->place_of_registration)<172){
        $sheet->setCellValue("CF62", $employee->place_of_registration);
        }else
        {
            $sheet->setCellValue("CF62", substr($employee->place_of_registration,0,172)."-");
            $sheet->setCellValue("A63", substr($employee->place_of_registration,172,strlen($employee->place_of_registration)));

        }

        //military
        $sheet->setCellValue("X66", $employee->military_accounting_group);
        if(strlen($employee->military_accounting_category)>90)
        {
            $sheet->setCellValue("AD67", substr($employee->military_accounting_category,0,90)."-");
            $sheet->setCellValue("B68", substr($employee->military_accounting_category,90,strlen($employee->military_accounting_category)));
        }else
        {
            $sheet->setCellValue("AD67", $employee->military_accounting_category);               
        }
        $sheet->setCellValue("L69", $employee->military_composition);
        $sheet->setCellValue("AD70", $employee->military_rank);
        $sheet->setCellValue("BD71", $employee->military_accounting_speciality_number);
        if($employee->military_suitability==0){
            if($employee->gender==1){ $military_suitability="Не придатна"; }else{$military_suitability="Не придатний";}
        }else{
            if($employee->gender==1){ $military_suitability="Придатна"; }else{$military_suitability="Придатний";}
        }
        $sheet->setCellValue("FV66", $military_suitability);
        if(strlen($employee->military_accounting_category)>32)
        {
            $sheet->setCellValue("GL67", substr($employee->military_district_office_registration_name,0,32)."-");
            $sheet->setCellValue("DQ68", substr($employee->military_district_office_registration_name,32,strlen($employee->military_district_office_registration_name)));
        }else
        {
            $sheet->setCellValue("GL67",$employee->military_district_office_registration_name);
        }
        $sheet->setCellValue("DQ70", $employee->military_district_office_residence_name);

        //profession_education
        $professional_education = $employee->professional_education;
        $rows=75;
        $col=0;
        $previous=0;
        for($i = 0; $i<strlen($professional_education); $i++)
        {
            if(!strcasecmp(substr($professional_education,$i,1),",")){$arr[$col]=substr($professional_education, $previous, $i-$previous); $col++;$previous=$i+1;}
            if(!strcasecmp(substr($professional_education,$i,1),"|") )
                {
                    $arr[$col]=substr($professional_education, $previous, $i-$previous);
                    $previous=$i+1; 
                    $col=0; 
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("V".$rows,$arr[1]);
                    $sheet->setCellValue("CB".$rows,$arr[2]);   
                    $sheet->setCellValue("CY".$rows,$arr[3]);
                    $sheet->setCellValue("EF".$rows,$arr[4]);
                    $sheet->setCellValue("FH".$rows,$arr[5]);    
                    $rows++;
                }
            if($i==(strlen($professional_education)-1))
            {
                    $arr[$col]=substr($professional_education, $previous, $i+1);                    
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("V".$rows,$arr[1]);
                    $sheet->setCellValue("CB".$rows,$arr[2]);   
                    $sheet->setCellValue("CY".$rows,$arr[3]);
                    $sheet->setCellValue("EF".$rows,$arr[4]);
                    $sheet->setCellValue("FH".$rows,$arr[5]);   
            }
        }
        //apointments
        $appointments_and_transfers = $employee->appointments_and_transfers;
        $rows=91;
        $col=0;
        $previous=0;
        for($i = 0; $i<strlen($appointments_and_transfers); $i++)
        {
            if(!strcasecmp(substr($appointments_and_transfers,$i,1),",")){$arr[$col]=substr($appointments_and_transfers, $previous, $i-$previous); $col++;$previous=$i+1;}
            if(!strcasecmp(substr($appointments_and_transfers,$i,1),"|") )
                {
                    $arr[$col]=substr($appointments_and_transfers, $previous, $i-$previous);
                    $previous=$i+1; 
                    $col=0; 
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("AB".$rows,$arr[1]);
                    $sheet->setCellValue("BZ".$rows,$arr[2]);   
                    $sheet->setCellValue("DM".$rows,$arr[3]);
                    $sheet->setCellValue("EP".$rows,$arr[4]);
                    $sheet->setCellValue("FJ".$rows,$arr[5]);    
                    $rows++;
                }
            if($i==(strlen($appointments_and_transfers)-1))
            {
                    $arr[$col]=substr($appointments_and_transfers, $previous, $i+1);                    
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("AB".$rows,$arr[1]);
                    $sheet->setCellValue("BZ".$rows,$arr[2]);   
                    $sheet->setCellValue("DM".$rows,$arr[3]);
                    $sheet->setCellValue("EP".$rows,$arr[4]);
                    $sheet->setCellValue("FJ".$rows,$arr[5]);     
            }
        }

        //VACATIONS
        $vacations = $employee->vacations;
        $rows=111;
        $col=0;
        $previous=0;
        for($i = 0; $i<strlen($vacations); $i++)
        {
            if(!strcasecmp(substr($vacations,$i,1),",")){$arr[$col]=substr($vacations, $previous, $i-$previous); $col++;$previous=$i+1;}
            if(!strcasecmp(substr($vacations,$i,1),"|") )
                {
                    $arr[$col]=substr($vacations, $previous, $i-$previous);
                    $previous=$i+1; 
                    $col=0; 
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("BX".$rows,$arr[1]);
                    $sheet->setCellValue("DE".$rows,$arr[2]);   
                    $sheet->setCellValue("EW".$rows,$arr[3]);
                    $sheet->setCellValue("GL".$rows,$arr[4]);
                    $rows++;
                }
            if($i==(strlen($vacations)-1))
            {
                    $arr[$col]=substr($vacations, $previous, $i+1);                    
                    $sheet->setCellValue("A".$rows,$arr[0]);
                    $sheet->setCellValue("BX".$rows,$arr[1]);
                    $sheet->setCellValue("DE".$rows,$arr[2]);   
                    $sheet->setCellValue("EW".$rows,$arr[3]);
                    $sheet->setCellValue("GL".$rows,$arr[4]);    
            }
        }

        return $objPHPExcel;
    }

    protected function makeEmployeesList($data)
    {
        $objPHPExcel = $this->loadTemplate('teachers_list.xls');

        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        $employees = Employee::model()->with(array('position' => array('together' => true)))->findAll();

        $i = 9;
        /** @var $employee Employee */

        for($k=0; $k<count(YII::app()->session['arr_id']); $k++)
        {
            $sheet->setCellValue("A$i", $i-8);
            $sheet->setCellValue("B$i", YII::app()->session['arr_id'][$k]->getFullName());
            $sheet->setCellValue("C$i", (isset(YII::app()->session['arr_id'][$k]->position))?YII::app()->session['arr_id'][$k]->position->title:'');
            $i++;
        }

        $i += 2;
        $sheet->setCellValue("A$i", "Директор інституту, декан факультету, завідувач відділення ______________ ______________");
        $sheet->setCellValue("E$i", "     (прізвище та ініціали)");

        return $objPHPExcel;
    }
}