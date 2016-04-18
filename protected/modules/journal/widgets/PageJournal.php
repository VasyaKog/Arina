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
    /*
     * @var $load Load;
     */
    public $load;
    public $list=array('Para1','para2');
    /*
     * @var $students Student[]
     */
    public $students;
    public $rows=array('Student1','Student2');
    /*
     * @var $records JournalRecord[]
     */
    public $records=array();

    public function init(){
        $this->load=Load::model()->findByPk($this->load_id);
        var_dump($this->load);
        $group = Group::model()->findByPk($this->load->group_id);
        $this->students=$group->getStudentArray();
        /*
             * @var $item Student
        */
        var_dump($this->students);
        foreach ($this->students as $item){
            array_push($this->rows,$item->getLink());
        }
        $this->records=JournalRecord::model()->findAllByAttributes(array('load_id'=> $this->load_id));
        foreach($this->records as $item){
            array_push($this->list,$item->getLink());
        }
    }

    public function run(){
        $this->render('pageJournal', array(
            'map'=> null,
            'list'=>$this->list,
            'rows'=>$this->rows,
            'readOnly'=>$this->readOnly,
        ));
    }

}