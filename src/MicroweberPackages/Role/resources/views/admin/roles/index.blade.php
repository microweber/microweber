
<script>
    mw.lib.require('bootstrap4');
</script>
        <div class="container-fluid">
            <div class="block-header">
                <h2>Roles</h2>
            </div>

            <!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Roles</h2>
                            <a href="{{route('users.index')}}" class="btn btn-success">Users</a>
                            <a href="{{route('roles.create')}}" class="btn btn-success">Add New Role</a>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                        	<th>Id</th>
                                            <th>Name</th>
                                            <th>Permissions</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        	<th>Id</th>
                                            <th>Name</th>
                                            <th>Permissions</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    	@foreach($roles as $row)
                                        <tr>
                                        	<td>{{ $row->id }}</td>
                                        	<td>{{ $row->name }}</td>
                                            <td>
                                                @foreach($row->permissions()->pluck('name') as $permission)
                                                    {{ $permission }},
                                                @endforeach
                                            </td>
                                        	<td>
                                        		<a href="{{route('roles.edit',$row->id)}}" class="btn btn-warning waves-effect">Edit</a>
                                        	</td>
                                        	<td>
                                        		<form id="delete_form" method="POST" action="{{ route('roles.destroy',$row->id) }}">
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
