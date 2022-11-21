<div>
    <div class="container-fluid justify-content-center m-4 text-center">

        <h3>Welcome to the Improt\Export Tool!</h3>
        <h4>Import and export the website content quick and easy!</h4>
        <br />
        <br />
        <h4>Let's get started!</h4>
        <br />

        <button type="button" wire:click="install" class="btn btn-lg btn-outline-success text-uppercase justify-content-center" style="width:200px;">
           <i class="fa fa-rocket"></i> Install
        </button>

        {{$this->log}}

        @if($this->showInstaller)

             <div class="progress mt-2" style="width:500px;margin:0 auto;">
                <div class="progress-bar" role="progressbar" aria-label="Install..."
                     style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>

            <script>
                $(document).ready(function() {

                    window.Livewire.emit('startInstalling');

                    var progress = 0;
                    var progressInterval = setInterval(function () {
                        if (progress > 99) {
                            clearInterval(progressInterval);
                            return;
                        }
                        progress++;
                        $('.progress-bar').css('width', progress+'%');
                        $('.progress-bar').attr('aria-valuenow', progress);
                        $('.progress-bar').html(progress + '%');
                    }, 40);
                });
            </script>
        @endif

    </div>
</div>
