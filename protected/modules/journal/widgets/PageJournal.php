<?php

/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 15.04.16
 * Time: 16:45
 */
class PageJournal extends CWidget
{
    public $readOnly = true;
    public $date = null;
    public $load_id;
    /**
     * @property $load Load;
     */
    public $load;
    public $list=array();
    /**
     * @var $students Student[]
     */
    public $students;
    public $rows=array();
    /**
     * @var $records JournalRecord[]
     */
    public $records=array();
    public $map;
    /**
     * @var $teacher Teacher
     */
    public $teacher;
    public $teacherName;

    public $t=true;

    public function init(){
        $this->load=Load::model()->findByPk($this->load_id);
        $group =Group::model()->findByPk($this->load->group_id);
        $this->students=JournalStudents::getAllStudentsInList($this->load);
        $this->teacher=Teacher::model()->findByPk($this->load->teacher_id);
        if(!isset($this->teacher)) {
            $this->teacherName=Yii::t('base','Not selected');
        }
        else
        {
            $this->teacherName=$this->teacher->getNameWithInitials();
        }
        /*
             * @var $item Student
        */

        foreach ($this->students as $item){
            array_push($this->rows,$item->getLink());
        }
        $this->records=JournalRecord::model()->findAllByAttributes(array('load_id'=> $this->load_id));
        foreach($this->records as $item){
            array_push($this->list,$item->getLink());
        }
        $i=0;
        $j=0;
        $this->map=array();
        /**
         * $student Student
         */
        foreach($this->students as $student) {
            array_push($this->map, array());
            /**
             * @var $study JournalRecord;
             */
            foreach ($this->records as $study) {
                if (Mark::model()->getLink($study->id, $student->id)) {
                    array_push($this->map[$i], Mark::model()->getLink($study->id, $student->id));
                } elseif(!in_array($this->load_id,$student->getListArray($study->date))){
                    array_push($this->map[$i], 'Відраховано');
                } elseif ($this->t) {
                    array_push($this->map[$i], CHtml::link(Yii::t('journal','Create'),array("/journal/mark/create/","student_id"=>$student->id,"journal_record_id"=>$study->id)));
                } else {
                    array_push($this->map[$i], '');
                }
                $j++;
            }
            $i++;
        }
    }

    public function run(){
        $this->render('pageJournal', array(
            'teacherName'=>$this->teacherName,
            'subject'=>WorkSubject::getNameSubject($this->load->wp_subject_id),
            'map'=> $this->map,
            'list'=>$this->list,
            'rows'=>$this->rows,
            'readOnly'=>$this->readOnly,
            't' =>$this->t,
            'load_id'=>$this->load_id
        ));
    }

}