<?php

$pdf = get_option('pdf', $params['id']);
$download = get_option('download', $params['id']);
$border = get_option('border', $params['id']);

if ($pdf == false) {
    $pdf = module_url() . 'files/default.pdf';
}

if ($border == false) {
    $borderStyle = '';
} else {
    $borderStyle = 'border: 1px solid ' . $border . ';';
}
?>


    <script>
        mw.moduleJS('<?php print module_url(); ?>js/pdf.js');

        // If absolute URL from the remote server is provided, configure the CORS
        // header on that server.
        var url = '<?php print $pdf; ?>';

        // Disable workers to avoid yet another cross-origin issue (workers need
        // the URL of the script to be loaded, and dynamically loading a cross-origin
        // script does not work).
        // PDFJS.disableWorker = true;

        // The workerSrc property shall be specified.
        PDFJS.workerSrc = '<?php print module_url(); ?>js/pdf.worker.js';

        // Asynchronous download of PDF
        var loadingTask = PDFJS.getDocument(url);
        loadingTask.promise.then(function (pdf) {
            //console.log('PDF loaded');

            // Fetch the first page
            var pageNumber = 1;
            pdf.getPage(pageNumber).then(function (page) {
              //  console.log('Page loaded');

                var scale = 1.5;
                var viewport = page.getViewport(scale);

                // Prepare canvas using PDF page dimensions
                var canvas = document.getElementById('pdf-<?php print $params['id'] ?>');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);
                renderTask.then(function () {
                  //  console.log('Page rendered');
                });
            });
        }, function (reason) {
            // PDF loading error
            //console.error(reason);
        });

    </script>


    <style scoped="scoped">
        #pdf-<?php print $params['id'] ?> {
            max-width: 100%;
        <?php echo $borderStyle; ?>
        }
    </style>


    <canvas id="pdf-<?php print $params['id'] ?>"></canvas>

    <div style="clear: both;"></div>

    <?php if ($download == 'true'): ?>
        <a href="<?php echo $pdf; ?>" target="_blank"><?php _e('File download'); ?></a>
    <?php endif; ?>