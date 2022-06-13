
<script>
    $(document).ready(function() {
        $('.collapse', '.js-form-entry-{{ $id }}').on('shown.bs.collapse', function () {
            $('.js-form-entry-{{ $id }}').prop('disabled',true).removeAttr('data-toggle');
        });
    });
</script>

<div class="js-form-entry-{{ $id }} card mb-3 not-collapsed-border collapsed card-order-holder card-bubble <?php if ($is_read): ?>bg-silver<?php else: ?>card-success<?php endif; ?>"
     data-bs-toggle="collapse" data-target="#notif-entry-item-{{ $id }}" aria-expanded="false"
     aria-controls="collapseExample">
    <div class="card-body">
        @if(!empty($module_name) && $module_name == 'admin/notifications')
            <div class="row align-items-center mb-3">
                <div class="col text-start text-left">
                    <span class="text-primary text-break-line-2"><?php _e("New form entry"); ?></span>
                </div>
            </div>
        @endif

            <div class="row align-items-center" data-bs-toggle="collapse" data-target="#notif-entry-item-{{$id}}" >
            <div class="col" style="max-width:55px;">
                <i class="mdi mdi-email text-primary mdi-24px"></i>
            </div>
            <div class="col-10 col-sm item-id"><span class="text-primary">#{{ $id }}</span>
            </div>

            <div class="col-6 col-sm">
                {{ date('M d, Y', strtotime($created_at))  }}
                <small class="text-muted">{{ date('h:s', strtotime($created_at)) }}h</small>
            </div>

            <div class="col-6 col-sm">{{$ago}}</div>
        </div>

        <div class="collapse" id="notif-entry-item-{{$id}}">
            <hr class="thin"/>
            <div class="row">
                @if(!empty($vals))
                    @foreach($vals->split(2) as $row)
                        <div class="col-md-6">
                            <h6>{!! $loop->iteration == 1 ? '<strong>Fields</strong>' : '' !!}</h6>
                            @if(!empty($row))
                                @foreach($row as $key => $val)

                                    @php
                                        if (empty($val)) {
                                            continue;
                                        }
                                    @endphp

                                    @if(!is_array($val))
                                        <div>
                                            <small class="text-muted">{{ str_replace('_', ' ', $key) }}:</small>
                                            <p>{{ $val }}</p>
                                        </div>
                                    @else
                                        @if($key == 'uploads')
                                        @include('form::admin.notifications.uploads_listing_partial', $val)
                                        @else
                                            <small class="text-muted">{{ str_replace('_', ' ', $key) }}:</small>
                                            @foreach ($val as $valInner)
                                                <p>{{ $valInner }} <br /> </p>
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
