<?php must_have_access(); ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<script>
    setTimeout(function() {
        if(mw.top() && mw.top().dialog && mw.top().dialog.get('.mw_modal_live_edit_settings')) {

            mw.top().dialog.get('.mw_modal_live_edit_settings').resize(900);
        }
    },300);
</script>

<?php


if (isset($params["live_edit"]) and $params["live_edit"]) {

    $controller = \Illuminate\Support\Facades\App::make(\MicroweberPackages\Modules\Shop\Http\Controllers\LiveEditAdminController::class);

    $request = new \Illuminate\Http\Request();
    $request->merge($params);
    $request->merge($_REQUEST);

    echo $controller->index($request);

} else {
    include __DIR__ . '/admin_backend.php';
}
?>
