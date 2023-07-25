<div>

    <script>

        window.addEventListener('livewire:load', function () {
            var liveEditIframeWindow = (mw.top().app.canvas.getWindow());

            Livewire.emit('onLoaded', {
                    'aaa': 'bbb'
                }
            );

        });

    </script>
    <div>



        <div>
            @dump(rand())
            @dump($modulesData)
        </div>


    </div>

</div>
