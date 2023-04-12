<div>
    <div class="modal-header">
        <h5 class="modal-title">
            {{$package['description']}}
        </h5>
        <button type="button" class="btn-close" wire:click="$emit('closeModal')" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row row-cols-2">
            <div style="max-height:400px;overflow:hidden">
                @if (isset($package['extra']['_meta']['screenshot_large']))
                    <img src="{{$package['extra']['_meta']['screenshot_large']}}" />
                @elseif(isset($package['extra']['_meta']['screenshot']))
                    <img src="{{$package['extra']['_meta']['screenshot']}}" />
                @else
                <div class="card-img-top text-center">
                    <i class="mdi mdi-view-grid-plus text-muted" style="opacity:0.5;font-size:126px;margin-left: 15px;"></i>
                </div>
                @endif
            </div>
            <div>
                <h1>{{$package['description']}}</h1>
                <div class="text-muted">
                    Latest Version: {{$package['version']}}
                </div>
                <br />

                <select class="form-control">
                    <option></option>
                </select>

                <br />
                <div>
                    <table cellspacing="0" cellpadding="0" class="table table-striped bg-white m-0" width="100%">
                                <tbody>

                                <tr>
                                    <td><?php _e('License'); ?></td>
                                    <td>
                                        <?php _e('N/A'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?php _e('Website'); ?></td>
                                    <td>
                                        <?php if (isset($package['homepage'])): ?>
                                        <a href="<?php print $package['homepage']; ?>" target="_blank"
                                           class="mw-blue package-ext-link"><?php print $package['homepage']; ?></a>
                                        <?php else: ?>
                <?php _e('N/A'); ?>
            <?php endif; ?>
                                    </td>
                                </tr>

                                @if(isset($package['authors']) && !empty($package['authors']))
                                    <tr>
                                        <td><?php _e('Author'); ?></td>
                                        <td>
                                            @foreach($package['authors'] as $author)
                                                {{$author['name']}}  {{$author['email']}}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td><?php _e('Release date'); ?></td>
                                    <td><?php print date('d M Y', strtotime($package['time'])) ?></td>
                                </tr>

                                <tr>
                                    <td><?php _e('Keywords'); ?></td>
                                    <td>
                                        <?php if (isset($package['keywords'])): ?>
                <?php print implode(", ", $package['keywords']); ?>
            <?php endif; ?>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">

    </div>
</div>
