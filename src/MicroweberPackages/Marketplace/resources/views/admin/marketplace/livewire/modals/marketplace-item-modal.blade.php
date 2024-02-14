<div>

    <div class="card-header d-flex align-items-center justify-content-between px-3">

        @if (isset($package['description']))
            <div>
                <h3 class="main-pages-title mt-3">{{$package['description']}}</h3>
            </div>
            <br/>

        @endif

        <button type="button" class="btn-close" aria-label="Close"
                wire:click="$emit('closeModal')"></button>
    </div>

    @if (!isset($package['description']))
        <div class="alert alert-danger">{{'Please select a package'}}</div>
    @else
        <script>mw.require('admin_package_manager.js');</script>


        <div class="marketplace-template-img-wrapper" style="max-height: 250px; overflow:hidden">
            @if (isset($package['extra']['_meta']['screenshot_large']))
                <img src="{{$package['extra']['_meta']['screenshot_large']}}"/>
            @elseif(isset($package['extra']['_meta']['screenshot']))
                <img src="{{$package['extra']['_meta']['screenshot']}}"/>
            @else
                <div class="card-img-top text-center">
                    <i class="mdi mdi-view-grid-plus text-muted"
                       style="opacity:0.5;font-size:126px;margin-left: 15px;"></i>
                </div>
            @endif
        </div>

        <div class="px-3 mt-3">

            @if (isset($package['version']))
            <div class="tblr-body-color my-2">
                {{'Latest Version'}}: {{$package['version']}}
            </div>
            @endif


            <div class="row align-items-center">
                @if (isset($package['versions']) && !empty($package['versions']))
                <div class="col-4">
                    <select class="form-select form-select-sm" wire:model="installVersion" class="form-control">
                        @foreach($package['versions'] as $version)
                            <option value="{{$version}}">{{$version}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-auto px-0">
                    @if(isset($package['has_update']) && $package['has_update'] && $installVersion == $package['version'])
                        <a vkey="{{$installVersion}}" href="javascript:;"
                           id="js-install-package-action"
                           onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                           class="btn btn-warning btn-sm js-package-install-btn">
                            {{_e('Update to')}} {{$installVersion}}
                        </a>
                    @elseif(isset($package['current_install']) && $package['current_install'] && $installVersion < $package['version'])
                        <a vkey="{{$installVersion}}" href="javascript:;"
                           id="js-install-package-action"
                           onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                           class="btn btn-danger btn-sm js-package-install-btn">
                            {{_e('Downgrade to')}} {{$installVersion}}
                        </a>
                    @elseif(isset($package['current_install']) && $package['current_install'])
                        <a vkey="{{$installVersion}}" href="javascript:;"
                           id="js-install-package-action"
                           onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                           class="btn btn-danger btn-sm js-package-install-btn">
                            {{_e('Reinstall')}}
                        </a>
                    @else

                        <a vkey="{{$installVersion}}" href="javascript:;"
                           id="js-install-package-action"
                           onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                           class="btn btn-success btn-sm js-package-install-btn">
                            {{_('Install')}}
                        </a>

                    @endif
                </div>

                <div class="col-auto">
                    @if (isset($package['demo_link']) AND $package['demo_link'])
                    <a target="_blank" href="{{$package['demo_link']}}"
                       class="btn btn-dark btn-sm marketplace-template-img-btn">
                        {{'Preview'}}
                    </a>
                    @endif
                </div>
            </div>

            <br/>
            <div class="table-responsive mb-5">
                <table class="table card-table table-vcenter fs-5 m-0">
                    <tbody>

                    <tr>
                        <td><?php _e('License'); ?></td>
                        <td>
                                <?php _e('N/A'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Help'); ?></td>
                        <td>
                                <?php _e('Calendly is a popular online scheduling tool that allows individuals or businesses to schedule meetings or appointments with others.'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Homepage'); ?></td>
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
                                    {{$author['name']}}  <a
                                        href="mailto:{{$author['email']}}">{{$author['email']}}</a>
                                @endforeach
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td><?php _e('Released'); ?></td>
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

    @endif
</div>
