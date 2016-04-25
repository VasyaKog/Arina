<?php
/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 15.04.16
 * Time: 18:40
 */
/**
 * @var boolean $readOnly
 * @var Graph $this
 * @var array $list
 * @var array $rows
 * @var array $map
 * @var boolean $t
 * @var int $load_id
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
</style>
<table class="journal graph table items table-striped table-condensed table-bordered table-hover">
    <tr>
        <td rowspan="2"><?echo Yii::t('terms','N p/p');?></td>
        <td class="name" rowspan="2"><? echo Yii::t('terms','Surname and initials');?></td>
        <td colspan="<? echo count($list)+$k; ?> " align="center"><?echo Yii::t('terms','Day, month') ?></td>
    </tr>
    <tr>
    <?php
    $krecords=0;
    foreach($list as $key){
        $krecords++;?>
        <td class="oc" align="center"><?php echo $key?></td>
    <?
    }
    if($t){echo '<td>'.CHtml::link(Yii::t('terms','create'),array('journalRecord/create/'.$load_id)).'</td>';}
    ?>
    </tr>
        <?
        $i=0;
    foreach($rows as $row) {
    ?>
    <tr>
        <td>
            <?echo $i+1;?>
        </td>
        <td class="name">
            <?echo $row?>
        </td>
    <? for($j=0;$j<$krecords;$j++) {?>
        <td class="oc" align="center">
            <?echo $map[$i][$j]?>
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

