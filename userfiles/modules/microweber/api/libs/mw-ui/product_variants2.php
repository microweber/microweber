<?php include('partials/header.php'); ?>


<div class="tree">
    DURVO
</div>

<script>
    $(document).ready(function () {
//        $('body > .main').addClass('show-sidebar-tree');
    });
</script>

<main>
    <div class="row">
        <div class="col-md-12">
            <div class="card style-1 mb-3">
                <div class="card-header">
                    <h5><strong>Size</strong> - edit option</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Close</button>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                    <label class="custom-control-label" for="customCheck1">Allow multiple choices</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                    <label class="custom-control-label" for="customCheck1">Is it required field? </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Option 1 title</label>
                                <input type="text" class="form-control" value="Size">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Placeholder</label>
                                <input type="text" class="form-control" placeholder="Enter placeholder here">
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                    <label class="custom-control-label" for="customCheck1">Show label</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Field type</label>
                            <div>
                                <select class="selectpicker" data-width="100%">
                                    <option>Dropdown</option>
                                    <option>Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Organize in columns</label>
                                <div>
                                    <select class="selectpicker" data-width="100%">
                                        <option>col-12</option>
                                        <option>col-6</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row copyright mt-3">
        <div class="col-12">
            <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
        </div>
    </div>
</main>


<?php include('partials/footer.php'); ?>
