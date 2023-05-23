<div>
    <script>mw.require('admin_package_manager.js');</script>

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

                <div>
                   <div>
                       Install version:
                       <select wire:model="installVersion" class="form-control">
                           @foreach($package['versions'] as $version)
                               <option value="{{$version}}">{{$version}}</option>
                           @endforeach
                       </select>
                   </div>
                    <div class="mt-2">

                        @if($package['has_update'] && $installVersion == $package['version'])
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-warning js-package-install-btn">
                                <i class="mdi mdi-rocket"></i> {{_e('Update to')}} {{$installVersion}}
                            </a>
                        @elseif($package['current_install'] && $installVersion < $package['version'])
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-danger js-package-install-btn">
                                <i class="mdi mdi-arrow-down"></i> {{_e('Downgrade to')}} {{$installVersion}}
                            </a>
                        @elseif($package['current_install'])
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-danger js-package-install-btn">
                                <i class="mdi mdi-refresh"></i> {{_e('Reinstall')}}
                            </a>
                        @else
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-success js-package-install-btn">
                                <i class="mdi mdi-download"></i>
                                {{_('Install')}}
                            </a>
                        @endif


                    </div>
                </div>

                <br />
                <div>
                    <table cellspacing="0" cellpadding="0" class="table table-striped   m-0" width="100%">
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
