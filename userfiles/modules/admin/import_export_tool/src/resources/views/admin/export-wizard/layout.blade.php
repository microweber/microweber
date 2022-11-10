@extends('import_export_tool::admin.module-layout')

@section('module-content')

<style>
.import-wizard {
    list-style: none;
    text-align: center;
    padding: 25px 0;
    border-bottom: 1px solid #e5e5e5;
}

.import-wizard__item {
    display: inline-block;
    -webkit-transition: all .4s ease;
    -o-transition: all .4s ease;
    -moz-transition: all .4s ease;
    transition: all .4s ease;
    padding: 0 13px;
    margin-top:10px;
}

.import-wizard__link {
    font-size: 15px;
    color: #fff;
    display: inline-block;
    -webkit-border-radius: 22.5px;
    -moz-border-radius: 22.5px;
    border-radius: 22.5px;
    background: #999;
    width: 185px;
    text-align: left;
}

.import-wizard__link .step {
    display: inline-block;
    height: 45px;
    width: 45px;
    line-height: 45px;
    text-align: center;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    background: #666;
    font-size: 18px;
    margin-right: 10px;
}

.import-wizard__link .desc-box {
    display: inline-block;
    width: 126px;
    float: right;
    font-size: 12px;
    padding-top: 5px;
}
.import-wizard__link .desc-icon {
    width: 33px;
    position: absolute;
    margin-top: -42px;
    margin-left: 135px;
    opacity: 0.4;
}

.import-wizard__link .desc {
    text-transform: capitalize;

}
.import-wizard__link .small-desc {
    text-transform: capitalize;
    display: block;
    color: #eee;
    font-size: 10px;
}

.import-wizard .active .import-wizard__link {
    background: #4592ff;
    font-weight: bold;
}

.import-wizard .active .import-wizard__link .step {
    background: #0000001a;
}

.import-wizard .import-wizard__link:hover {
    color: #fff;
    text-decoration: none;
}

a.import-wizard-select-type {
    color: #3e3e3e;
}

a.import-wizard-select-type:hover {
    text-decoration: none;
}

.import-wizard-select-type {
    width: 200px;
    background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-right: 10px;
    cursor: pointer;
    text-align: center;
    padding-top: 20px;
    padding-bottom: 25px;
}

.import-wizard-select-type:hover {
    background: #4592ff;
    border: 1px solid #3e83e5;
    color: #fff;
}
</style>

@if (session()->has('successMessage'))
    <script id="js-success-message-{{time()}}">
        mw.notification.success('{{ session('successMessage') }}');
    </script>
@endif

@if (session()->has('errorMessage'))
    <script id="js-error-message-{{time()}}">
        mw.notification.error('{{ session('errorMessage') }}');
    </script>
@endif

<div class="wizard-container">

   <div class="row">
       <div class="mx-auto col-md-10">
            <div class="mt-4">
                <div class="d-flex">
                    <div class="h3">Export Wizard  @if($export_feed['name']) - @endif </div>
                    @if($export_feed['name'])
                        @if($this->еьпорт_feed_edit_name)
                        <div class="h3 ml-2" style="margin-top:-10px">
                            <input type="text"
                                   wire:blur="closeEditName"
                                    wire:keydown.escape="closeEditName"
                                    wire:keydown.enter="closeEditName"
                                   wire:model="еьпорт_feed.name" class="form-control form-control-lg" />
                        </div>
                        @else
                        <div class="h3 ml-2">{{$export_feed['name']}}</div>
                       <div class="ml-2">
                           <a href="#" wire:click="editName" style="font-size: 17px"><i class="fa fa-pencil-alt"></i></a>
                       </div>
                        @endif
                    @endif
                </div>
                <p>Please follow the wizard to export new feed.</p>
            </div>
        </div>
    </div>

    <ul class="import-wizard justify-content-center">
        <li class="import-wizard__item @if($tab =='type') active @endif">
            <a href="#" wire:click="showTab('type')" class="import-wizard__link">
                <span class="step">1</span>
                @if($export_feed['export_type'])
                <div class="desc-box">
                    <span class="desc">Export</span>
                    <span class="small-desc">{{$export_feed['export_type']}}</span>
                </div>
                <div class="desc-icon">
                    <x-import_export_tool::icon width="38px" name="{{$export_feed['export_type']}}" />
                </div>
                @else
                    <span class="desc">Type</span>
                @endif
            </a>
        </li>
        <li class="import-wizard__item @if($tab =='format') active @endif">
            <a href="#" wire:click="showTab('format')" class="import-wizard__link">
                <span class="step">2</span>
                @if($export_feed['export_format'])
                    <div class="desc-box">
                        <span class="desc">Export</span>
                        <span class="small-desc">{{$export_feed['export_format']}}</span>
                    </div>
                    <div class="desc-icon">
                        <x-import_export_tool::icon width="38px" name="{{$export_feed['export_format']}}" />
                    </div>
                @else
                    <span class="desc">Format</span>
                @endif
            </a>
        </li>
        <li class="import-wizard__item @if($tab =='export') active @endif">
            <a href="#" wire:click="showTab('export')" class="import-wizard__link">
                <span class="step">3</span>
                <span class="desc">Export</span>
            </a>
        </li>
    </ul>
    <div class="mb-5">
        @yield('content')
    </div>
</div>
@endsection
