
<ul class="navbar-nav-padding nav-item-profile-wrapper">
    <li class="nav-item nav-item-profile">
        <a href="{{admin_url('user/profile')}}" class="nav-link fs-3">
            <div class="mw-admin-sidebar-profile">
                <span style="font-size: 14px;">SB</span>
            </div>
            <div>
                <?php echo get_username_short(); ?>
            </div>
        </a>
    </li>
    {{--                <?php event_trigger('mw.admin.sidebar.li.last'); ?>--}}
    {{--                <div class="mt-5">--}}
    {{--                    <?php include(modules_path(). DS . 'admin/lang_swich_footer.php'); ?>--}}
    {{--                </div>--}}
</ul>
