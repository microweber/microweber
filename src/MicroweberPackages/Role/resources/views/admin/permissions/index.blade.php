

<script>
    mw.lib.require('bootstrap4');
</script>
<div class="container" style="margin-top: 30px">

    <!-- Vertical Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2>Permission</h2>
                    <a href="{{route('permissions.create')}}" class="btn btn-success">Add New Permission</a>
                    <a href="{{route('users.index')}}" class="btn btn-success">Users</a>
                    <a href="{{route('roles.index')}}" class="btn btn-success">Roles</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($permissions as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                        <a href="{{route('permissions.edit',$row->id)}}" class="btn btn-warning waves-effect">Edit</a>
                                    </td>
                                    <td>
                                        <form id="delete_form" method="POST" action="{{ route('permissions.destroy',$row->id) }}">
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