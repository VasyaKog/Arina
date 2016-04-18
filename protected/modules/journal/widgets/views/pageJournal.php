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
 */
?>
<table id="graph" class="graph items table table-striped table-condensed table-bordered table-hover">
    <tr>
        <td>as</td>
    <?php
    $krecords=0;
    foreach($list as $key){
        $krecords++;?>
        <td><?php echo $key?></td>
    <?
    }
    ?>
    </tr>
        <?
    foreach($rows as $row) {
    ?>
    <tr>
        <td>
            <?echo $row?>
        </td>
    <? for($i=0;$i<$krecords;$i++) {?>
        <td>
            <?echo $row?>
      </td>

    <?
    }
    ?>
    </tr>
    <?

}
?>
</table>

