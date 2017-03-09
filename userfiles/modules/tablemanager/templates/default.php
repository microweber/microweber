

<div class="table-mng-default">
    <table class="mw-ui-table">

        <thead>
            <tr>
              <?php

                foreach($th as $item){

                ?>
              <th><?php print $item; ?></th>
                 <?php } ?>
            </tr>
        </thead>
        <tbody>
              <?php

                foreach($tr as $tritem){

                ?>
             <tr>

                 <?php foreach($tritem as $td){   ?>
                    <td><?php print $td; ?></td>
                 <?php } ?>

             </tr>
                 <?php } ?>
        </tbody>

    </table>


</div>
 
