<div id="files-manager"></div>
<script>
    mw.require('uploader.js');

</script>
<script>
    mw.FileManager({
        element: document.getElementById('files-manager'),
        canSelectFolder: true,
        selectable: true,
        multiselect: true,
        stickyHeader: false,
        options: true,
        selectableRow: false,
    });
</script>
