<div>
    <div class="table-responsive py-3">
        <table class="table table-vcenter card-table fs-4">
            <thead>
            <tr>
                <th class="border-0">
                    @include('content::admin.content.livewire.table-includes.select-all-checkbox')
                </th>
                <th class="border-0 font-weight-bold">ID</th>
                <th class="border-0 font-weight-bold"><?php _e('Client'); ?></th>
                <th class="border-0 font-weight-bold"><?php _e('E-mail'); ?></th>
                <th class="border-0 font-weight-bold"><?php _e('Phone'); ?></th>
                <th class="border-0 font-weight-bold"><?php _e('City / Country'); ?></th>
                <th class="border-0 font-weight-bold text-center"><?php _e('Action'); ?></th>
            </tr>
            </thead>
            <tbody>
            @foreach($contents as $customer)
                <tr class=" ">
                    <th>

                        <div class="custom-control custom-checkbox my-2">
                            <input type="checkbox" value="{{ $customer->id }}" id="customers-{{ $customer->id }}"  class="js-select-posts-for-action form-check-input"  wire:model="checked">
                            <label for="customers-{{ $customer->id }}" class="custom-control-label"></label>
                        </div>

                    </th>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->getFullName() }}</td>
                    <td>{{ $customer->getEmail() }}</td>
                    <td>{{ $customer->getPhone() }}</td>
                    <td>
                        {{ $customer->cityAndCountry() }}
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.customers.destroy', $customer->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="tblr-body-color me-2 text-decoration-none" data-bs-toggle="tooltip" aria-label="Edit client" data-bs-original-title="Edit client">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M180-180h44l443-443-44-44-443 443v44Zm614-486L666-794l42-42q17-17 42-17t42 17l44 44q17 17 17 42t-17 42l-42 42Zm-42 42L248-120H120v-128l504-504 128 128Zm-107-21-22-22 44 44-22-22Z"></path></svg>
                            </a>
                            <button type="submit" onclick="return confirm(mw.lang('Are you sure you want yo delete this?'))" class="text-danger border-0" style="background: none;" data-bs-toggle="tooltip" aria-label="Delete client" data-bs-original-title="Delete client">
                                <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
