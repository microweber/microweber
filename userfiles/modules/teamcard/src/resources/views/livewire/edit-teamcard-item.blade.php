<?php


?>
<form wire:submit.prevent="submit">

    <div class="form-group">
        <label for="exampleInputName"><?php _e("Name") ?></label>
        <x-microweber-ui::input type="text" placeholder="Enter Name" wire:model="name"/>
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>


    <div class="form-group">
        <label for="exampleInputEmail"><?php _e("Bio") ?></label>
        <x-microweber-ui::input type="text" placeholder="Enter bio" wire:model="bio"/>
        @error('bio') <span class="text-danger">{{ $message }}</span> @enderror
    </div>


    <div class="form-group">
        <label for="exampleInputEmail">Role</label>
        <x-microweber-ui::input type="text" placeholder="Enter role" wire:model="role"/>
        @error('role') <span class="text-danger">{{ $message }}</span> @enderror
    </div>


    <div class="form-group">
        <label for="exampleInputEmail">Image</label>
        <x-microweber-ui::input type="text" placeholder="Enter file" wire:model="file"/>
        @error('file') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <button x-on:click="showEditTab = 'main'" class="btn btn-outline-secondary" type="button">@lang('Cancel')</button>
    <button type="submit" class="btn btn-primary">@lang('Save')</button>
</form>
