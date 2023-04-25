

<?php if(isset($filters) and $filters): ?>

<?php
$setActiveContent = false;
$setActiveCategory = false;
if(isset($filters['pageAndParent'])){
$setActiveContent = intval($filters['pageAndParent']);
}
if(isset($filters['page'])){
$setActiveContent = intval($filters['page']);
}
if(isset($filters['category'])){
$setActiveCategory = intval($filters['category']);
}
?>


<script>



    document.addEventListener('livewire:load', function () {

        setTimeout(() => {

            <?php if(isset($setActiveCategory) and $setActiveCategory): ?>
            if (typeof pagesTree !== 'undefined' && pagesTree !== null) {
                pagesTree.select(<?php print $setActiveCategory ?>, 'category', true);
            }
            <?php endif; ?>

            <?php if(isset($setActiveContent) and $setActiveContent): ?>
            if (typeof pagesTree !== 'undefined' && pagesTree !== null) {
                pagesTree.select(<?php print $setActiveContent ?>, 'page', true);
            }
            <?php endif; ?>


        }, 500);




    })



</script>





<?php endif; ?>


