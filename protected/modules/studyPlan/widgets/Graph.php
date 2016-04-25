<?php

/**
 *
 * @author Dmytro Karpovych <ZAYEC77@gmail.com>
 */
class Graph extends CWidget
{
    public $readOnly = false;
    public $model;
    public $field;
    public $graph = null;
    public $studyPlan = true;
    /** @var $yearAmount int for study plan */
    public $yearAmount = 4;
    /** @var $specialityId int for work plan */
    public $specialityId;
    /** @var $studyYearId int for work plan */
    public $studyYearId;
    public $studyPlanProcessLink = '/studyPlan/plan/executeGraph';
    public $workPlanProcessLink = '/studyPlan/work/executeGraph';

    protected $list;
    protected $map=array();
    protected $graphProcessLink;
    protected $rows = array();

    public function init()
    {
        $this->list = GlobalHelper::getWeeksByMonths();
        if ($this->studyPlan) {
            $this->graphProcessLink = $this->studyPlanProcessLink;
            if (empty($this->yearAmount)) throw new CException('Years amount must be set');
            for ($i = 0; $i < $this->yearAmount; $i++) {
                $this->rows[$i+1] = $i + 1;
            }
            if (isset($this->graph)) {
                $this->map = $this->graph;
            } else {
                $this->map = PlanHelper::getDefaultPlan();
            }
        } else {
            $this->graphProcessLink = $this->workPlanProcessLink;
            if (empty($this->specialityId)) throw new CException('Speciality Id must be set');
            if (empty($this->studyYearId)) throw new CException('Study year Id must be set');
            /** @var Speciality $speciality */
            $speciality = Speciality::model()->findByPk($this->specialityId);
            $this->rows = $speciality->getGroupsByStudyYear($this->studyYearId);

            if (empty($this->graph)) {
                $this->map = PlanHelper::getDefaultWorkPlan($this->rows);
            } else {
                $this->map = $this->graph;
            }
        }
    }

    public function run()
    {
        $this->render('graph', array(
            'graphProcessLink' => $this->graphProcessLink,
            'map' => $this->map,
            'list' => $this->list,
            'rows' => $this->rows,
            'readOnly' => $this->readOnly));
    }
}