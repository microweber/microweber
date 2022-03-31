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
<table border="1">
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
                    &lt;<?php echo $tag['tag']; ?>&gt; <?php echo $tag['value']; ?> &lt;/<?php echo $tag['tag']; ?>&gt;
                    <?php else: ?>
                    &lt;/<?php echo $tag['tag']; ?>&gt;
                    <?php endif; ?>
                </div>
            </td>
            <td>drodown</td>
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
                        &lt;<?php echo $tag['tag']; ?>&gt; <?php echo $tag['value']; ?> &lt;/<?php echo $tag['tag']; ?>&gt;
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
