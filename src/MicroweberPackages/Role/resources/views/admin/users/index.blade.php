<script>
    mw.lib.require('bootstrap4');
</script>

<div class="container" style="margin-top: 30px">

    <!-- Vertical Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2>User</h2>
                    <a href="{{route('users.create')}}" class="btn btn-success">Add New User</a>
                    <a href="{{route('roles.create')}}" class="btn btn-success">Add New Role</a>
                    <a href="{{route('permissions.create')}}" class="btn btn-success">Add New Permission</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>
                                        @foreach($row->roles()->pluck('name') as $role)
                                            {{ $role }},
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{route('users.edit',$row->id)}}" class="btn btn-warning waves-effect">Edit</a>
                                        <form id="delete_form" method="POST" action="{{ route('users.destroy',$row->id) }}">
                                            {{ csrf_field() }}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger waves-effect" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Vertical Layout -->

</div>