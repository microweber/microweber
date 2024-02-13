<div>

    <script>
        window.addEventListener('livewire:load', function () {
            mw.top().app.on('layoutsListCategoryFilter', function (category) {

            });
        });
    </script>

    <style>
        .masonry-container {
            display: flex;
            flex-wrap: wrap;
        }
        .aadee {
            pointer-events:none;width: 333%;transform: scale(0.3);transform-origin: 0 0;
        }
        .manqk {
            width:32%;height:300px;overflow:hidden;
        }
        .aadee:hover {
            box-shadow: 0 0 0 3px #000;
        }
    </style>

    <div class="masonry-container">
        @foreach($layouts as $layout)

            <?php
            $layoutFile = $layout['layout_file'];
            $layoutFile = str_replace('.php', '', $layoutFile);
            ?>

            <div class="manqk">
                <div class="aadee">
                    <module id="<?php echo md5($layoutFile); ?>-layout" type="layouts" template="<?php echo $layoutFile; ?>" />
                </div>
            </div>

        @endforeach
    </div>

    <div class="d-flex justify-content-center mb-3">
        {{ $paginator->links("livewire-tables::specific.bootstrap-4.pagination") }}
    </div>

</div>
