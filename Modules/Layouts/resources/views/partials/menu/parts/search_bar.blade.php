<?php if ($search_bar): ?>
    <li class="nav-item dropdown btn-search">
        {{-- AI-295: see profile_link.blade.php — no href, tabindex="0",
             role="button" keeps it semantically a button. --}}
        <a class="nav-link" data-bs-toggle="dropdown" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="{{ _e('Search', true) }}"><i class="mdi mdi-magnify mdi-20px"></i></a>
        <div class="dropdown-menu">
            <div class="row">
                <form class="col w-300 mx-auto input-glass" action="<?php print site_url(); ?>search" method="get">
                    <input class="form-control border-0" type="text" id="keywords" name="keywords" placeholder="Search...">
                </form>
            </div>
        </div>
    </li>
<?php endif; ?>
