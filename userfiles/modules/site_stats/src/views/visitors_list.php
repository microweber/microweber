<?php
if (!$data) {
    return;
}


?>
<table>
    <?php foreach ($data as $item): ?>

        <tr>
            <td>
                <?php if (isset($item['views_data'])): ?>
                    <ul>
                        <?php foreach ($item['views_data'] as $view): ?>
                            <li> <?php print $view['url'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </td>
            <td>  <?php print $item['browser_agent'] ?>            </td>
            <td>  <?php print $item['updated_at'] ?>            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php foreach ($data as $item): ?>

    <?php
//dd($item->views);
    // print_r($item->views());
//    if($item){
//
//    }


    //

    ?>
<?php endforeach;


print_r($data);
?>
