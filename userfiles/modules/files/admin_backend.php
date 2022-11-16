<div id="files-manager"></div>
<script>
    mw.require('uploader.js');
    mw.require('filemanager.js');
</script>
<script>
    mw.FileManager({
        element: document.getElementById('files-manager'),
        canSelectFolder: true,
        selectable: true,
        multiselect: true,
        stickyHeader: 68,
        options: true,
        selectableRow: false,
    });
</script>
