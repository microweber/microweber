<style>

    .example {
        padding: 2rem;
        margin: 1rem 0 2rem;
        border: var(--tblr-border-width) var(--tblr-border-style) var(--tblr-border-color);
        border-radius: 3px 3px 0 0;
        position: relative;
        min-height: 12rem;
        display: flex;
        align-items: center;
        overflow-x: auto;
    }

    .example-centered {
        justify-content: center;
    }
    .example-centered .example-content {
        flex: 0 auto;
    }

    .example-content {
        font-size: 0.875rem;
        line-height: 1.4285714286;
        color: #1d273b;
        flex: 1;
        max-width: 100%;
    }
    .example-content .page-header {
        margin-bottom: 0;
    }

    .example-bg {
        background: #f1f5f9;
    }

    .example-code {
        margin: 2rem 0;
        border: var(--tblr-border-width) var(--tblr-border-style) var(--tblr-border-color);
        border-top: none;
    }
    .example-code pre {
        margin: 0;
        border: 0;
        border-radius: 0 0 3px 3px;
    }
    .example + .example-code {
        margin-top: -2rem;
    }

    .example-column {
        margin: 0 auto;
    }
    .example-column > .card:last-of-type {
        margin-bottom: 0;
    }

    .example-column-1 {
        max-width: 26rem;
    }

    .example-column-2 {
        max-width: 52rem;
    }

    .example-modal-backdrop {
        background: #1d273b;
        opacity: 0.24;
        position: absolute;
        width: 100%;
        left: 0;
        top: 0;
        height: 100%;
        border-radius: 2px 2px 0 0;
    }
</style>

<div class="container-xl">


    <?php

    $tablerFiles = scandir(mw_includes_path() . 'api/libs/mw-ui/grunt/plugins/tabler-ui/demo/docs/');

    $htmlFiles = array_filter($tablerFiles, function ($file) {
        return strpos($file, '.html') !== false;
    });

    $ready = [];

    foreach ($htmlFiles as $file) {
        $title = str_replace('.html', '', $file);
        $content = file_get_contents(mw_includes_path() . 'api/libs/mw-ui/grunt/plugins/tabler-ui/demo/docs/' . $file);
        //filter element <div class="page">

        $phpquery = phpQuery::newDocument($content);
        $content = $phpquery->find('.page-body .col-lg-9')->html();

        //url(../static/avatar
        $content = str_replace('url(../static', 'url(' . mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/tabler-ui/demo/static', $content);
        //./static/photos/
        $content = str_replace('url(./static', 'url(' . mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/tabler-ui/demo/static', $content);

        //src="../static/

        $content = str_replace('src="../static', 'src="' . mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/tabler-ui/demo/static', $content);

        $ready[] = ['title' => ucfirst($title), 'file' => $file,'content' => $content];
    }

   // dump($ready);
    ?>
    <header class="navbar navbar-expand-md navbar-light d-print-none fixed-top">
        <div class="container-xl">


            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="row align-items-stretch align-items-md-center">
                    <ul class="navbar-nav flex-wrap">

                        <?php foreach ($ready as $item): ?>
                        <li class="nav-item border m-1">
                            <a class="nav-link" href="#anchor-<?php  print  ($item['file']) ?>">

                                <span class="nav-link-title">
                                <?php  print  ($item['title']) ?>
                              </span>

                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>



    <?php foreach ($ready as $item): ?>

    <h1 id="anchor-<?php  print  ($item['file']) ?>"><?php  print ($item['title']) ?></h1>
    <pre><?php  print  ($item['file']) ?></pre>
   <div>
         <?php print $item['content'] ?>
    </div>


    <?php endforeach; ?>
</div>
