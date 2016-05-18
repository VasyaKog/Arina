<?php
/**
 * Created by PhpStorm.
 * User: Arhangel
 * Date: 15.05.2016
 * Time: 20:38

 * @var boolean $readOnly
 * @var Graph $this
 * @var array $list
 * @var array $rows
 * @var array $map
 * @var array $npp
 * @var boolean $t
 * @var int $load_id
 * @var string $subject
 * @var array $teacherName
 * @var string $group
 * @var string $month
 */
if($t) $k =0; else $k=1;
?>
<style>
    .name td {
        width: 200px;
        align-content: center;
    }
    .oc td {
        width: 50px;
        text-align: center;
        align-items: center;
    }

    .journal  {
        width: auto;
    }
    #back{
        background-color: gainsboro;
    }

    .record{
        text-orientation:upright;
    }

    #center{
        text-align: center;
    }
</style>
<h3><?php echo Yii::t('present','Month').': '.Yii::t('present', $month).'  '.Yii::t('present','Group').': '.$group;?></h3>
<table class="journal graph table items table-striped table-condensed table-bordered table-hover">
    <tr>
        <td id="center" rowspan="5"><?echo Yii::t('terms','N p/p'); ?></td>
        <td id="center" class="name" rowspan="5"><? echo Yii::t('terms','Surname and initials');?></td>
        <?php
        $krecords=0;
        foreach($subject as $key) {
            $krecords++;
            ?>
            <td class="oc record" align="center"><?php echo $key?></td>
            <?
        }
        ?>
    </tr>
    <tr>
        <?php
        $krecords=0;
        foreach($teacherName as $key) {
            $krecords++;
            ?>
            <td class="oc record" align="center"><?php echo $key?></td>
            <?
        }
        ?>
    </tr>
    <tr>
        <td id="center"><?echo Yii::t('terms','aud') ?></td>
    </tr>
    <tr>
        <?php
        $krecords=0;
        foreach($list as $key){
            $krecords++; ?>
            <td class="oc record" align="center"><?php echo $key?></td>
            <?
        }
        if($t){echo '<td class="record">'.CHtml::link(Yii::t('terms','create'),array('journalRecord/create/'.$load_id)).'</td>';}
        ?>
    </tr>
    <tr>
        <?php
        $krecords=0;
        foreach($npp as $key) {
            $krecords++;
        ?>
            <td class="oc record" align="center"><?php echo $key?></td>
            <?
        }
        ?>
    </tr>

    <?
    $i=0;
    foreach($rows as $row) {
    ?>
    <tr>
        <td>
            <? echo $i + 1; ?>
        </td>
        <td class="name">
            <? echo $row ?>
        </td>
        <? for ($j = 0; $j < $krecords; $j++) {
            if ($map[$i][$j] == 'Відраховано') {
                echo '<td class="oc" id="back" align="center">';
            } else {?>
                <td class="oc" align = "center" >
            <? echo $map[$i][$j] ?>
        <?
        } ?>
        </td>

        <?

        }
            if($t)echo '<td></td>';
            ?>
        </tr>
        <?

        $i++;
    }
    ?>
</table>

