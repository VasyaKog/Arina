<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 15.05.2016
 * Time: 0:21
 */
class PagePresent extends CWidget
{
    public $readOnly = true;
    public $date = null;
    public $load_id;
    public $month;
    public $year;

    /**
     * @property $load Load;
     * @property $group Group;
     */

    public $load;
    public $loaded;
    public $list=array();
    public $npp=array();

    /**
     * @var $students Student[]
     */

    public $students;
    public $rows=array();

    /**
     * @var $records JournalRecord[]
     * @var $teacher Teacher
     */

    public $records=array();
    public $map;
    public $teacher;
    public $teacherName=array();
    public $subject=array();

    public $t=true;

    /**
     * @param $a Student
     * @param $b Student
     * @return int
     * @var $studyMonthId
     */

    public $group;



    function cmp($a, $b)
    {
        return strcmp($a->getFullName(), $b->getFullName());
    }

    public function init()
    {
        $this->load = Load::model()->findByPk($this->load_id);
        $this->group = Group::model()->findByPk($this->load->group_id);
        $this->students = JournalStudents::getAllStudentsInList($this->load);

        /**
         * @var $item Student
         */

        foreach ($this->students as $item) {
            array_push($this->rows, $item->getLink());
        }

        $recordsall=JournalRecord::model()->findAll(array('order'=>'date'));

        //1$this->records = JournalRecord::model()->findAll();
        foreach ($recordsall as $record) {
            $record->date = date("n",strtotime($record->date));

            if ($record->date == $this->month) {
                array_push($this->records,$record);
            }
        }

                foreach ($this->records as $item) {

                    array_push($this->list, $item->date);
                }

                foreach ($this->records as $item) {
                    array_push($this->npp, $item->numer_in_day);
                }

                foreach ($this->records as $item) {
                    $this->teacher = Teacher::model()->findByPk($item->teacher_id);
                    if (!isset($this->teacher)) {
                        array_push($this->teacherName, Yii::t('base', 'Not selected'));
                    } else {
                        array_push($this->teacherName, $item->teacher->getNameWithInitials());
                    }
                }

                foreach ($this->records as $item) {
                    $this->load = Load::model()->findByPk($item->load_id);
                    array_push($this->subject, WorkSubject::getNameSubject(isset($item->load->wp_subject_id) ? $item->load->wp_subject_id : '0'));
                }

                $i = 0;
                $j = 0;
                $this->map = array();

                /**
                 * $student Student
                 */

                foreach ($this->students as $student) {
                    array_push($this->map, array());
                    /**
                     * @var $study JournalRecord;
                     */

                    foreach ($this->records as $study) {
                        if (Mark::model()->getLink($study->id, $student->id)) {
                            array_push($this->map[$i], Mark::model()->getLink($study->id, $student->id));
                        } elseif (!in_array($this->load_id, $student->getListArray($study->date))) {
                            array_push($this->map[$i], 'Відраховано');
                        } else {
                            array_push($this->map[$i], '');
                        }
                        $j++;
                    }
                    $i++;
                }
            }
        //}
    //}

    public function run(){
        $this->render('pagePresent', array(
            'teacherName'=>$this->teacherName,
            'subject'=>$this->subject,
            'map'=> $this->map,
            'list'=>$this->list,
            'rows'=>$this->rows,
            'readOnly'=>$this->readOnly,
            't' =>$this->t,
            'load_id'=>$this->load_id,
            'month'=>$this->month,
            'group'=>$this->group->title,
            'npp'=>$this->npp,
            ));
    }

}