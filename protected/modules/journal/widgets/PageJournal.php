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
    public $list;
    public $students;
    public $rows=sud

    public function init(){
        $this->load=Load::model()->findByPk($this->load_id);
        $this->students = Group::getArrayStudentByGroupId($this->load->getGroupId(),$this->date);
    }

}