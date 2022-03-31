<style>
    body {
        padding:50px;
    }
</style>

<body>

<link type="text/css" rel="stylesheet" href="//demo.microweber.org/demo/userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/css/main_with_mw.css" >

<?php
$printedTags = [];
?>
<table class="table table-bordered">
    <?php foreach($structure as $tag): ?>

        <?php
        $skipTag = false;
        if ($tag['level'] == $content_parent_tag_level && $tag['tag'] == end($content_parent_tag)) {
            $skipTag = true;
        }
        if ($tag['level'] > $content_parent_tag_level) {
            $skipTag = true;
        }

        if ($skipTag) {

            if(isset($printedTags[$tag['tag']])){
                continue;
            }

            $printedTags[$tag['tag']] = true;
            ?>


        <tr>
            <td>
                <div class="px-<?php echo $tag['level']; ?>">
                    <?php if($tag['type'] == 'open') :  ?>
                    &lt;<?php echo $tag['tag']; ?>&gt;
                    <?php elseif ($tag['type'] == 'complete'): ?>
                    &lt;<?php echo $tag['tag']; ?>&gt; <?php if(isset($tag['value'])) {echo $tag['value']; } ?> &lt;/<?php echo $tag['tag']; ?>&gt;
                    <?php else: ?>
                    &lt;/<?php echo $tag['tag']; ?>&gt;
                    <?php endif; ?>
                </div>
            </td>
            <td style="width:30%">
                <?php
                $showDropdownSelection = true;
                if ($tag['tag'] == end($content_parent_tag)) {
                    $showDropdownSelection = false;
                }
                ?>
                <?php if ($showDropdownSelection): ?>
                <select class="form-control">
                    <option name="title">Title</option>
                    <option name="description">Description</option>
                    <option name="images">Images</option>
                    <option name="price">Price</option>
                    <option name="sku">sku</option>
                </select>
                <?php endif; ?>
            </td>
        </tr>


        <?php
            continue;
        }
        ?>

        <tr>
            <td>
                <div class="px-<?php echo $tag['level']; ?>">
                    <?php if($tag['type'] == 'open') :  ?>
                        &lt;<?php echo $tag['tag']; ?>&gt;
                    <?php elseif ($tag['type'] == 'complete'): ?>
                        &lt;<?php echo $tag['tag']; ?>&gt; <?php if(isset($tag['value'])) {echo $tag['value']; } ?> &lt;/<?php echo $tag['tag']; ?>&gt;
                    <?php else: ?>
                        &lt;/<?php echo $tag['tag']; ?>&gt;
                    <?php endif; ?>
                </div>
            </td>
            <td>--</td>
        </tr>

    <?php endforeach; ?>
</table>
</body>
