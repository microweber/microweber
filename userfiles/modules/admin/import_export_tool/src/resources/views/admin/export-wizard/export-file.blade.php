<div class="container">
    <div class="d-flex justify-content-center">

        <table class="table">
            <tbody>

            <tr>
                <td><label for="feed_parts"><b>Export parts</b></label><br>
                    <small>Split exporting</small>

                </td>
                <td>
                    @php
                        $recomendedSipltToParts = 10;
                        if (isset($import_feed['count_of_contents'])) {
                            $recomendedSipltToParts = \MicroweberPackages\Export\SessionStepper::recomendedSteps($import_feed['count_of_contents']);
                        }
                    @endphp
                    <select class="form-control" id="feed_parts" wire:model="export_feed.split_to_parts">
                        <option value="1">1 part(s)</option>
                        <option value="2">2 part(s)</option>
                        <option value="3">3 part(s)</option>
                        <option value="4">4 part(s)</option>
                        <option value="5">5 part(s)</option>
                        <option value="6">6 part(s)</option>
                        <option value="7">7 part(s)</option>
                        <option value="8">8 part(s)</option>
                        <option value="9">9 part(s)</option>
                        <option value="10">10 part(s)  @if($recomendedSipltToParts == 10) (Recommended) @endif</option>
                        <option value="20">30 part(s) @if($recomendedSipltToParts == 30) (Recommended) @endif</option>
                        <option value="30">60 part(s) @if($recomendedSipltToParts == 60) (Recommended) @endif</option>
                        <option value="100">100 part(s) @if($recomendedSipltToParts == 100) (Recommended) @endif</option>
                        <option value="200">200 part(s) @if($recomendedSipltToParts == 200) (Recommended) @endif</option>
                        <option value="500">500 part(s) @if($recomendedSipltToParts == 500) (Recommended) @endif</option>
                    </select>

                </td>
                <td>
                    <button class="btn btn-primary btn-rounded"
                            wire:click="$emit('openModal', 'import_export_tool::start_exporting_modal',{exportFeedId:{{$export_feed['id']}}})">
                        <i class="fa fa-file-export"></i> Start Exporting
                    </button>
                </td>
            </tr>

            </tbody>
        </table>

      {{--  <div class="input-group" style="width:500px">
            <input class="form-control" value="{{route('admin.import-export-tool.export-wizard-file', $export_feed['id'])}}">
            <div class="input-group-append">
                <a href="{{route('admin.import-export-tool.export-wizard-file', $export_feed['id'])}}" target="_blank" class="btn btn-outline-success">
                    <i class="fa fa-download"></i> Download
                </a>
            </div>
        </div>--}}
    </div>

</div>
