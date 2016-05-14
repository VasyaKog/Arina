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
 * @var string $subject
 * @var string $teacherName
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


    #back{
        background-color: gainsboro;
    }

    .record{
        text-orientation:upright;
    }

    .mytable{
        width: auto;
        display: block;
    }

    #center{
        text-align: center;
    }
    .prokrutka{
        height: 50%; /* Just for the demo          */
        overflow-y: auto;    /* Trigger vertical scroll    */
        overflow-x: auto;  /* Hide the horizontal scroll */
    }
</style>
<h3><?php echo Yii::t('subject','Subject').': '.$subject.'  '.Yii::t('teacher','Teacher').': '.$teacherName;?></h3>
<div class="prokrutka">
<table class="journal mytable table ">
        <tr>
            <td rowspan="2"><?echo Yii::t('terms','N p/p');?></td>
            <td class="name" rowspan="2"><? echo Yii::t('terms','Surname and initials');?></td>
            <td colspan="<? echo count($list)+$k; ?> " id="center"><?echo Yii::t('terms','Day, month') ?></td>
        </tr>
    <tr>
    <?php
    $krecords=0;
    foreach($list as $key){
        $krecords++;?>
        <td class="oc record" align="center"><?php echo $key?></td>
    <?
    }
    if($t){echo '<td class="record">'.CHtml::link(Yii::t('journal','Create JournalRecord'),array('journalRecord/create/'.$load_id)).'</td>';}
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
    <? for($j=0;$j<$krecords;$j++) {
        if($map[$i][$j]=='Відраховано')
        {
            echo '<td class="oc" id="back" align="center">';
        }else{?>
        <td class="oc" align="center">

            <?echo $map[$i][$j]?>
        <?}?>
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
    </div>
<script>
    var $table = $('.prokrutka'),
        $bodyCells = $table.find('table tr:first').children(),
        colWidth;

    // Adjust the width of thead cells when window resizes
    $(window).resize(function() {
        // Get the tbody columns width array
        colWidth = $bodyCells.map(function() {
            return $(this).width();
        }).get();

        // Set the width of thead columns
        $table.find('thead tr').children().each(function(i, v) {
            $(v).width(colWidth[i]);
        });
    }).
</script>
