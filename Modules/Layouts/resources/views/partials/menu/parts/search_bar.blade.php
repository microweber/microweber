<?php if ($search_bar == 1): ?>
    <li class="nav-item dropdown btn-search">
        <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-magnify mdi-20px"></i></a>
        <div class="dropdown-menu">
            <div class="row">
                <form class="col w-300 mx-auto input-glass" action="<?php print site_url(); ?>search" method="get">
                    <input class="form-control border-0" type="text" id="keywords" name="keywords" placeholder="Search...">
                </form>
            </div>
        </div>
    </li>
<?php endif; ?>
