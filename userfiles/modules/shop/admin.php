<?php must_have_access(); ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<?php
if (isset($params["live_edit"]) and $params["live_edit"]) {

    $controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Shop\Http\Controllers\LiveEditAdminController::class);

    $request = new \Illuminate\Http\Request();
    $request->merge($params);
    $request->merge($_REQUEST);

    echo $controller->index($request);

} else {
    include __DIR__ . '/admin_backend.php';
}
?>
