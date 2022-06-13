<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- MW UI changes CSS -->
    <?php if (is_file('grunt/plugins/ui/css/main_compiled.css')): ?>
        <link rel="stylesheet" id="main-css-style" href="grunt/plugins/ui/css/main.php">
    <?php elseif (is_file('assets/ui/plugins/css/main.css')): ?>
        <link rel="stylesheet" href="assets/ui/plugins/css/main_with_mw.css">
    <?php else: ?>
        <link rel="stylesheet" href="grunt/plugins/ui/css/main_with_mw.css">
    <?php endif; ?>

    <!-- MW Admin CSS -->
    <?php if (!is_file('grunt/plugins/ui/css/main_compiled.css')): ?>
        <?php if (is_file('assets/ui/plugins/css/mw.css')): ?>
<!--            <link rel="stylesheet" href="assets/ui/plugins/css/mw.css">-->
        <?php else: ?>
<!--            <link rel="stylesheet" href="grunt/plugins/ui/css/mw.css">-->
        <?php endif; ?>
    <?php endif; ?>


    <!-- MW UI plugins CSS -->
    <link rel="stylesheet" href="assets/ui/plugins/css/plugins.min.css"/>


    <title>Hello, world!</title>
</head>
<body>

<div class="bg-primary py-1 position-sticky sticky-top">
    <ul class="nav p-3" id="myTab" role="tablist">
        <li class="mx-1 mx-1 d-flex align-items-center"><h5 class="text-white mr-3 my-0">Microweber UI</h5></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#navigations" role="tab" aria-controls="navigations" aria-selected="true">Navigations</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#buttons" role="tab" aria-controls="buttons" aria-selected="false">Buttons</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#badges" role="tab" aria-controls="badges" aria-selected="true">Badges</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#progress" role="tab" aria-controls="progress" aria-selected="false">Progress bars</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#typography" role="tab" aria-controls="typography" aria-selected="false">Typography</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#tables" role="tab" aria-controls="tables" aria-selected="false">Tables</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#forms" role="tab" aria-controls="tables" aria-selected="false">Forms</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#cards" role="tab" aria-controls="cards" aria-selected="false">Cards</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#tabs" role="tab" aria-controls="tabs" aria-selected="false">Tabs</a></li>
        <li class="mx-1"><a class="btn btn-info" data-toggle="tab" href="#others-tab" role="tab" aria-controls="other" aria-selected="false">Other</a></li>
    </ul>
</div>

<div class="container pb-5">
    <div class="page-header mb-5 mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1>Microweber UI</h1>
                <p class="lead">Cool colors for cool people!</p>
                <hr/>
            </div>
        </div>
    </div>
</div>

<div class="pb-5">
    <div>
        <div class="tab-content" id="ui-nav">
            <div class="tab-pane fade" id="navigations" role="tabpanel" aria-labelledby="navigations-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="bs-component my-3">
                                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>

                                    <div class="collapse navbar-collapse" id="navbarColor01">
                                        <ul class="navbar-nav mr-auto">
                                            <li class="nav-item active">
                                                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">About</a>
                                            </li>
                                        </ul>
                                        <form class="form-inline my-2 my-lg-0">
                                            <input class="form-control mr-sm-2" type="text" placeholder="Search">
                                            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                    </div>
                                </nav>
                            </div>

                            <div class="bs-component my-3">
                                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>

                                    <div class="collapse navbar-collapse" id="navbarColor02">
                                        <ul class="navbar-nav mr-auto">
                                            <li class="nav-item active">
                                                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">About</a>
                                            </li>
                                        </ul>
                                        <form class="form-inline my-2 my-lg-0">
                                            <input class="form-control mr-sm-2" type="text" placeholder="Search">
                                            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                    </div>
                                </nav>
                            </div>

                            <div class="bs-component my-3">
                                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>

                                    <div class="collapse navbar-collapse" id="navbarColor03">
                                        <ul class="navbar-nav mr-auto">
                                            <li class="nav-item active">
                                                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">About</a>
                                            </li>
                                        </ul>
                                        <form class="form-inline my-2 my-lg-0">
                                            <input class="form-control mr-sm-2" type="text" placeholder="Search">
                                            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                                        </form>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="buttons" role="tabpanel" aria-labelledby="buttons-tab">
                <div class="container-fluid">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-4">
                            <div class="buttons-standarts">
                                <h5>Standart</h5>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary">Primary</button>
                                    <button type="button" class="btn btn-secondary">Secondary</button>
                                    <button type="button" class="btn btn-success">Success</button>
                                    <button type="button" class="btn btn-info">Info</button>
                                    <button type="button" class="btn btn-warning">Warning</button>
                                    <button type="button" class="btn btn-danger">Danger</button>
                                    <button type="button" class="btn btn-link">Link</button>
                                </p>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary disabled">Primary</button>
                                    <button type="button" class="btn btn-secondary disabled">Secondary</button>
                                    <button type="button" class="btn btn-success disabled">Success</button>
                                    <button type="button" class="btn btn-info disabled">Info</button>
                                    <button type="button" class="btn btn-warning disabled">Warning</button>
                                    <button type="button" class="btn btn-danger disabled">Danger</button>
                                    <button type="button" class="btn btn-link disabled">Link</button>
                                </p>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-outline-primary">Primary</button>
                                    <button type="button" class="btn btn-outline-secondary">Secondary</button>
                                    <button type="button" class="btn btn-outline-success">Success</button>
                                    <button type="button" class="btn btn-outline-info">Info</button>
                                    <button type="button" class="btn btn-outline-warning">Warning</button>
                                    <button type="button" class="btn btn-outline-danger">Danger</button>
                                    <button type="button" class="btn btn-link">Link</button>
                                </p>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary btn-rounded">Primary</button>
                                    <button type="button" class="btn btn-secondary btn-rounded">Secondary</button>
                                    <button type="button" class="btn btn-success btn-rounded">Success</button>
                                    <button type="button" class="btn btn-info btn-rounded">Info</button>
                                    <button type="button" class="btn btn-warning btn-rounded">Warning</button>
                                    <button type="button" class="btn btn-danger btn-rounded">Danger</button>
                                    <button type="button" class="btn btn-link btn-rounded">Link</button>
                                </p>

                                <div class="bs-component my-3">
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <button type="button" class="btn btn-primary">Primary</button>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-group d-none" role="group" aria-label="Button group with nested dropdown">
                                        <button type="button" class="btn btn-secondary">Secondary</button>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop5" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop5">
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <button type="button" class="btn btn-success">Success</button>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop2" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <button type="button" class="btn btn-info">Info</button>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop3" type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop3">
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <button type="button" class="btn btn-warning">Warning</button>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop6" type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop6">
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <button type="button" class="btn btn-danger">Danger</button>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop4" type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop4">
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                                <a class="dropdown-item" href="#">Dropdown link</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bs-component my-3">
                                    <button type="button" class="btn btn-primary btn-lg">Large button</button>
                                    <button type="button" class="btn btn-primary">Default button</button>
                                    <button type="button" class="btn btn-primary btn-sm">Small button</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 text-center">
                            <h5>Only Icons</h5>

                            <p class="bs-component my-3">
                                <button type="button" class="btn btn-primary btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-secondary btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-success btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-info btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-warning btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-danger btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-link btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                            </p>

                            <p class="bs-component my-3">
                                <button type="button" class="btn btn-primary disabled btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-secondary disabled btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-success disabled btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-info disabled btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-warning disabled btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-danger disabled btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-link disabled btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                            </p>

                            <p class="bs-component my-3">
                                <button type="button" class="btn btn-outline-primary btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-success btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-info btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-warning btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-danger btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-link btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                            </p>

                            <p class="bs-component my-3">
                                <button type="button" class="btn btn-primary btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-secondary btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-success btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-info btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-warning btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-danger btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-link btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                            </p>

                            <p class="bs-component my-3">
                                <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-success btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-info btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-warning btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-outline-danger btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                <button type="button" class="btn btn-link btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                            </p>
                        </div>

                        <div class="col-xl-4">
                            <div class="buttons-with-icons">
                                <h5>With Icons</h5>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary icon-left"><i class="mdi mdi-account-edit"></i> Primary</button>
                                    <button type="button" class="btn btn-success icon-left"><i class="mdi mdi-plus"></i> Success</button>
                                    <button type="button" class="btn btn-info icon-left"><i class="mdi mdi-information-variant"></i> Info</button>
                                    <button type="button" class="btn btn-warning icon-right">Warning <i class="mdi mdi-alert"></i></button>
                                    <button type="button" class="btn btn-danger icon-right">Danger <i class="mdi mdi-close-octagon"></i></button>
                                    <button type="button" class="btn btn-link icon-left"><i class="mdi mdi-link"></i> Link</button>
                                </p>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary disabled icon-left"><i class="mdi mdi-account-edit"></i> Primary</button>
                                    <button type="button" class="btn btn-success disabled icon-left"><i class="mdi mdi-plus"></i> Success</button>
                                    <button type="button" class="btn btn-info disabled icon-left"><i class="mdi mdi-information-variant"></i> Info</button>
                                    <button type="button" class="btn btn-warning disabled icon-right">Warning <i class="mdi mdi-alert"></i></button>
                                    <button type="button" class="btn btn-danger disabled icon-right">Danger <i class="mdi mdi-close-octagon"></i></button>
                                    <button type="button" class="btn btn-link disabled icon-left"><i class="mdi mdi-link"></i> Link</button>
                                </p>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-outline-primary icon-left"><i class="mdi mdi-account-edit"></i> Primary</button>
                                    <button type="button" class="btn btn-outline-success icon-left"><i class="mdi mdi-plus"></i> Success</button>
                                    <button type="button" class="btn btn-outline-info icon-left"><i class="mdi mdi-information-variant"></i> Info</button>
                                    <button type="button" class="btn btn-outline-warning icon-right">Warning <i class="mdi mdi-alert"></i></button>
                                    <button type="button" class="btn btn-outline-danger icon-right">Danger <i class="mdi mdi-close-octagon"></i></button>
                                    <button type="button" class="btn btn-link icon-left"><i class="mdi mdi-link"></i> Link</button>
                                </p>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary btn-rounded icon-left"><i class="mdi mdi-account-edit"></i> Primary</button>
                                    <button type="button" class="btn btn-success btn-rounded icon-left"><i class="mdi mdi-plus"></i> Success</button>
                                    <button type="button" class="btn btn-info btn-rounded icon-left"><i class="mdi mdi-information-variant"></i> Info</button>
                                    <button type="button" class="btn btn-warning btn-rounded icon-right">Warning <i class="mdi mdi-alert"></i></button>
                                    <button type="button" class="btn btn-danger btn-rounded icon-right">Danger <i class="mdi mdi-close-octagon"></i></button>
                                    <button type="button" class="btn btn-link btn-rounded icon-left"><i class="mdi mdi-link"></i> Link</button>
                                </p>

                                <div class="bs-component my-3">
                                    <button type="button" class="btn btn-primary btn-lg icon-left"><i class="mdi mdi-arrow-right"></i> Large button</button>
                                    <button type="button" class="btn btn-primary icon-left"><i class="mdi mdi-arrow-right"></i> Default button</button>
                                    <button type="button" class="btn btn-primary btn-sm icon-left"><i class="mdi mdi-arrow-right"></i> Small button</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr/>

                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-6">
                            <div class="buttons-all-types">
                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary btn-sm">Primary</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm">Primary</button>
                                    <button type="button" class="btn btn-primary btn-rounded btn-sm">Primary</button>
                                    <button type="button" class="btn btn-primary icon-left btn-sm"><i class="mdi mdi-account-edit"></i> Primary</button>
                                    <button type="button" class="btn btn-primary btn-icon btn-sm"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-outline-primary btn-icon btn-sm"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-primary btn-rounded btn-icon btn-sm"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-outline-primary btn-rounded btn-icon btn-sm"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-link btn-rounded btn-icon btn-sm"><i class="mdi mdi-cards-heart"></i></button>
                                </p>

                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary">Primary</button>
                                    <button type="button" class="btn btn-outline-primary">Primary</button>
                                    <button type="button" class="btn btn-primary btn-rounded">Primary</button>
                                    <button type="button" class="btn btn-primary icon-left"><i class="mdi mdi-account-edit"></i> Primary</button>
                                    <button type="button" class="btn btn-primary btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-outline-primary btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-primary btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-link btn-rounded btn-icon"><i class="mdi mdi-cards-heart"></i></button>
                                </p>


                                <p class="bs-component my-3">
                                    <button type="button" class="btn btn-primary btn-lg">Primary</button>
                                    <button type="button" class="btn btn-outline-primary btn-lg">Primary</button>
                                    <button type="button" class="btn btn-primary btn-rounded btn-lg">Primary</button>
                                    <button type="button" class="btn btn-primary icon-left btn-lg"><i class="mdi mdi-account-edit"></i> Primary</button>
                                    <button type="button" class="btn btn-primary btn-icon btn-lg"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-outline-primary btn-icon btn-lg"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-primary btn-rounded btn-icon btn-lg"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-outline-primary btn-rounded btn-icon btn-lg"><i class="mdi mdi-cards-heart"></i></button>
                                    <button type="button" class="btn btn-link btn-rounded btn-icon btn-lg"><i class="mdi mdi-cards-heart"></i></button>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-5">
                            <p class="bs-componen my-3t">
                                <button type="button" class="btn btn-primary btn-block">Block level button</button>
                            </p>

                            <div class="bs-component my-3">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="checkbox" checked autocomplete="off"> Active
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="checkbox" autocomplete="off"> Check
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="checkbox" autocomplete="off"> Check
                                    </label>
                                </div>

                                &nbsp; &nbsp;

                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-secondary active">
                                        <input type="checkbox" checked autocomplete="off"> Active
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="checkbox" autocomplete="off"> Check
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="checkbox" autocomplete="off"> Check
                                    </label>
                                </div>

                                &nbsp; &nbsp;

                                <div class="btn-group btn-group-toggle btn-hover-style-1" data-toggle="buttons">
                                    <label class="btn btn-outline-secondary active">
                                        <input type="checkbox" checked autocomplete="off"> Active
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="checkbox" autocomplete="off"> Check
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="checkbox" autocomplete="off"> Check
                                    </label>
                                </div>
                            </div>

                            <div class="bs-component my-3">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="options" id="option1" autocomplete="off" checked> Active
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" id="option2" autocomplete="off"> Radio
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="options" id="option3" autocomplete="off"> Radio
                                    </label>
                                </div>

                                &nbsp; &nbsp;

                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-secondary active">
                                        <input type="radio" name="options" id="xoption1" autocomplete="off" checked> Active
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="radio" name="options" id="xoption2" autocomplete="off"> Radio
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="radio" name="options" id="xoption3" autocomplete="off"> Radio
                                    </label>
                                </div>

                                &nbsp; &nbsp;

                                <div class="btn-group btn-group-toggle btn-hover-style-1" data-toggle="buttons">
                                    <label class="btn btn-outline-secondary active">
                                        <input type="radio" name="options" id="yoption1" autocomplete="off" checked> Active
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="radio" name="options" id="yoption2" autocomplete="off"> Radio
                                    </label>
                                    <label class="btn btn-outline-secondary">
                                        <input type="radio" name="options" id="yoption3" autocomplete="off"> Radio
                                    </label>
                                </div>
                            </div>

                            <div class="bs-component my-3">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-secondary">Left</button>
                                    <button type="button" class="btn btn-secondary">Middle</button>
                                    <button type="button" class="btn btn-secondary">Right</button>
                                </div>

                                &nbsp; &nbsp;

                                <div class="btn-toolbar d-inline-block" role="toolbar" aria-label="Toolbar with button groups">
                                    <div class="btn-group mr-2" role="group" aria-label="First group">
                                        <button type="button" class="btn btn-secondary">1</button>
                                        <button type="button" class="btn btn-secondary">2</button>
                                        <button type="button" class="btn btn-secondary">3</button>
                                        <button type="button" class="btn btn-secondary">4</button>
                                    </div>
                                    <div class="btn-group mr-2" role="group" aria-label="Second group">
                                        <button type="button" class="btn btn-secondary">5</button>
                                        <button type="button" class="btn btn-secondary">6</button>
                                        <button type="button" class="btn btn-secondary">7</button>
                                    </div>
                                    <div class="btn-group" role="group" aria-label="Third group">
                                        <button type="button" class="btn btn-secondary">8</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-5">
                            <div class="my-3">
                                <button type="button" class="btn btn-outline-secondary icon-left btn-sm"><i class="mdi mdi-cart"></i> Notifications <span class="badge badge-success badge-pill ml-3">4</span></button>
                                <button type="button" class="btn btn-outline-secondary icon-left"><i class="mdi mdi-cart"></i> Notifications <span class="badge badge-success badge-pill ml-3">4</span></button>
                                <button type="button" class="btn btn-outline-secondary icon-left btn-lg"><i class="mdi mdi-cart"></i> Notifications <span class="badge badge-success badge-pill ml-3">4</span></button>
                            </div>

                            <div class="my-3">
                                <button class="btn btn-primary btn-sm" type="button" disabled><i class="spinner-border spinner-border-sm mr-2" aria-hidden="true"></i> Loading...</button>
                                &nbsp; &nbsp;
                                <button class="btn btn-primary" type="button" disabled><i class="spinner-border spinner-border-sm mr-2" aria-hidden="true"></i> Loading...</button>
                            </div>

                            <div class="my-3">
                                <div class="btn-group tag tag-xs">
                                    <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> Tag</span>
                                    <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                                </div>
                                &nbsp; &nbsp;
                                <div class="btn-group tag">
                                    <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> Tag</span>
                                    <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                                </div>
                                &nbsp; &nbsp;
                                <div class="btn-group tag">
                                    <span class="btn btn-primary icon-left no-hover"><i class="mdi mdi-tag"></i> Tag</span>
                                    <button type="button" class="btn btn-primary btn-icon"><i class="mdi mdi-close"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="my-3">
                                <div class="bs-component my-3">
                                    <div class="btn-group btn-group-toggle">
                                        <button type="button" class="btn btn-outline-secondary icon-left btn-sm"><i class="mdi mdi-thumb-up text-silver"></i> <strong>10</strong></button>
                                        <button type="button" class="btn btn-outline-secondary icon-left btn-sm"><i class="mdi mdi-thumb-down text-silver"></i> <strong>6</strong></button>
                                    </div>
                                    &nbsp; &nbsp;
                                    <div class="btn-group btn-group-toggle">
                                        <button type="button" class="btn btn-outline-secondary text-s icon-left"><i class="mdi mdi-thumb-up text-silver"></i> <strong>10</strong></button>
                                        <button type="button" class="btn btn-outline-secondary icon-left"><i class="mdi mdi-thumb-down text-silver"></i> <strong>6</strong></button>
                                    </div>
                                </div>
                            </div>

                            <div class="my-3">
                                <div class="bs-component my-3">
                                    <div class="d-inline-block">
                                        <button type="button" class="btn text-silver btn-link btn-icon btn-sm"><i class="mdi mdi-thumb-up"></i></button>
                                        <span class="text-dark"><strong>0</strong></span>
                                        <button type="button" class="btn text-silver btn-link btn-icon btn-sm"><i class="mdi mdi-thumb-down"></i></button>
                                    </div>
                                    &nbsp;&nbsp;
                                    <div class="d-inline-block">
                                        <button type="button" class="btn text-silver btn-link btn-icon btn-sm"><i class="mdi mdi-thumb-up"></i></button>
                                        <span class="text-success"><strong>+10</strong></span>
                                        <button type="button" class="btn text-silver btn-link btn-icon btn-sm"><i class="mdi mdi-thumb-down"></i></button>
                                    </div>
                                    &nbsp;&nbsp;
                                    <div class="d-inline-block">
                                        <button type="button" class="btn text-silver btn-link btn-icon btn-sm"><i class="mdi mdi-thumb-up"></i></button>
                                        <span class="text-danger"><strong>-5</strong></span>
                                        <button type="button" class="btn text-silver btn-link btn-icon btn-sm"><i class="mdi mdi-thumb-down"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="my-3">
                                <div class="btn-group btn-group-quantity">
                                    <button type="button" class="btn btn-primary btn-sm btn-icon btn-minus"><i class="mdi mdi-minus"></i></button>
                                    <span class="btn btn-primary btn-sm icon-left no-hover qty-holder">
                                                <input type="text" class="text-white" value="10"/>
                                            </span>
                                    <button type="button" class="btn btn-primary btn-sm btn-icon btn-plus"><i class="mdi mdi-plus"></i></button>
                                </div>
                                &nbsp; &nbsp;
                                <div class="btn-group btn-group-rounded btn-group-quantity">
                                    <button type="button" class="btn btn-primary btn-sm btn-icon btn-minus"><i class="mdi mdi-minus"></i></button>
                                    <span class="btn btn-primary btn-sm icon-left no-hover qty-holder">
                                                <input type="text" class="text-white" value="10"/>
                                            </span>
                                    <button type="button" class="btn btn-primary btn-sm btn-icon btn-plus"><i class="mdi mdi-plus"></i></button>
                                </div>
                                &nbsp; &nbsp;
                                <div class="btn-group btn-group-quantity">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-icon btn-minus"><i class="mdi mdi-minus"></i></button>
                                    <span class="btn btn-outline-secondary btn-sm icon-left no-hover qty-holder">
                                                <input type="text" value="10"/>
                                            </span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-icon btn-plus"><i class="mdi mdi-plus"></i></button>
                                </div>
                                &nbsp; &nbsp;
                                <div class="btn-group btn-group-rounded btn-group-quantity">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-icon btn-minus"><i class="mdi mdi-minus"></i></button>
                                    <span class="btn btn-outline-secondary btn-sm icon-left no-hover qty-holder">
                                                <input type="text" value="10"/>
                                            </span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-icon btn-plus"><i class="mdi mdi-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-lg-12">
                            <div class="bs-component" style="margin-bottom: 40px;">
                                <span class="badge badge-primary">Primary</span>
                                <span class="badge badge-secondary">Secondary</span>
                                <span class="badge badge-success">Success</span>
                                <span class="badge badge-danger">Danger</span>
                                <span class="badge badge-warning">Warning</span>
                                <span class="badge badge-info">Info</span>
                                <span class="badge badge-light">Light</span>
                                <span class="badge badge-dark">Dark</span>
                            </div>

                            <div class="bs-component">
                                <span class="badge badge-pill badge-primary">Primary</span>
                                <span class="badge badge-pill badge-secondary">Secondary</span>
                                <span class="badge badge-pill badge-success">Success</span>
                                <span class="badge badge-pill badge-danger">Danger</span>
                                <span class="badge badge-pill badge-warning">Warning</span>
                                <span class="badge badge-pill badge-info">Info</span>
                                <span class="badge badge-pill badge-light">Light</span>
                                <span class="badge badge-pill badge-dark">Dark</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 id="progress-basic">Basic</h3>
                            <div class="bs-component my-3">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="bs-component my-3">
                                <div class="progress progress-silver">
                                    <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <h3 id="progress-alternatives">Contextual alternatives</h3>
                            <div class="bs-component my-3">
                                <div class="progress my-1">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress my-1">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress my-1">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress my-1">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <h3 id="progress-multiple">Multiple bars</h3>
                            <div class="bs-component my-3">
                                <div class="progress">
                                    <div class="progress-bar my-1" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div class="progress-bar bg-success my-1" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div class="progress-bar bg-info my-1" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <h3 id="progress-striped">Striped</h3>
                            <div class="bs-component my-3">
                                <div class="progress my-1">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress my-1">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress my-1">
                                    <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress my-1">
                                    <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress my-1">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <h3 id="progress-animated">Animated</h3>
                            <div class="bs-component my-3">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="typography" role="tabpanel" aria-labelledby="typography-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <h1>Heading 1</h1>
                                <h1 class="font-weight-bold">Heading 1</h1>
                                <h1 class="text-uppercase">Heading 1</h1>
                                <h2>Heading 2</h2>
                                <h2 class="font-weight-bold">Heading 2</h2>
                                <h2 class="text-uppercase">Heading 2</h2>
                                <h3>Heading 3</h3>
                                <h3 class="font-weight-bold">Heading 3</h3>
                                <h3 class="text-uppercase">Heading 3</h3>
                                <h4>Heading 4</h4>
                                <h4 class="font-weight-bold">Heading 4</h4>
                                <h4 class="text-uppercase">Heading 4</h4>
                                <h5>Heading 5</h5>
                                <h5 class="font-weight-bold">Heading 5</h5>
                                <h5 class="text-uppercase">Heading 5</h5>
                                <h6>Heading 6</h6>
                                <h6 class="font-weight-bold">Heading 6</h6>
                                <h6 class="text-uppercase">Heading 6</h6>
                                <br/>
                                <br/>
                                <h3>Heading
                                    <small class="text-muted">with muted text</small>
                                </h3>
                                <p class="lead">This is Lead text. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <h4>Example body text</h4>
                                <p>Nullam quis risus eget <a href="#">urna mollis ornare</a> vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.</p>
                                <p>
                                    <small>This line of text is meant to be treated as fine print.</small>
                                </p>
                                <p>The following is <strong>rendered as bold text</strong>.</p>
                                <p>The following is <em>rendered as italicized text</em>.</p>
                                <p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <h4>Emphasis classes</h4>
                                <p class="text-muted">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
                                <p class="text-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                                <p class="text-secondary">Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
                                <p class="text-warning">Etiam porta sem malesuada magna mollis euismod.</p>
                                <p class="text-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
                                <p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                                <p class="text-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
                            </div>

                        </div>
                    </div>

                    <!-- Blockquotes -->

                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <h4>Blockquotes</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <blockquote class="blockquote">
                                    <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                    <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                                </blockquote>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <blockquote class="blockquote text-center">
                                    <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                    <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                                </blockquote>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <blockquote class="blockquote text-end text-right">
                                    <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                    <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tables" role="tabpanel" aria-labelledby="tables-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="bs-component">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">Type</th>
                                        <th scope="col">Column heading</th>
                                        <th scope="col">Column heading</th>
                                        <th scope="col">Column heading</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="table-active">
                                        <th scope="row">Active</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Default</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <th scope="row">Primary</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <th scope="row">Secondary</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-success">
                                        <th scope="row">Success</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <th scope="row">Danger</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <th scope="row">Warning</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-info">
                                        <th scope="row">Info</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-light">
                                        <th scope="row">Light</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    <tr class="table-dark">
                                        <th scope="row">Dark</th>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                        <td>Column content</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!-- /example -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show active" id="forms" role="tabpanel" aria-labelledby="forms-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="bs-component">
                                <form>
                                    <fieldset>
                                        <legend>Legend</legend>
                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="email@example.com">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelect1">Example select</label>
                                            <select class="form-control" id="exampleSelect1">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleSelect2">Example multiple select</label>
                                            <select multiple class="form-control" id="exampleSelect2">
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleTextarea">Example textarea</label>
                                            <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputFile">File input</label>
                                            <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                                            <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
                                        </div>
                                        <fieldset class="form-group">
                                            <legend>Radio buttons</legend>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                                    Option one is this and that&mdash;be sure to include why it's great
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2">
                                                    Option two can be something else and selecting it will deselect option one
                                                </label>
                                            </div>
                                            <div class="form-check disabled">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                                                    Option three is disabled
                                                </label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <legend>Checkboxes</legend>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" value="" checked>
                                                    Option one is this and that&mdash;be sure to include why it's great
                                                </label>
                                            </div>
                                            <div class="form-check disabled">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" value="" disabled>
                                                    Option two is disabled
                                                </label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group">
                                            <legend>Sliders</legend>
                                            <label>Example range</label>
                                            <div class="js-range mb-5">
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <input type="text" value="" class="form-control js-from">
                                                    </div>
                                                    <div class="col col-1 col-px-5">
                                                        <hr/>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" value="" class="form-control js-to">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 position-relative">
                                                        <input type="text" class="js-slider" name="my_range" value="" data-min="10" data-max="100" data-type="single" data-step="1" data-grid="true"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="js-range">
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <input type="text" value="" class="form-control js-from">
                                                    </div>
                                                    <div class="col col-1 col-px-5">
                                                        <hr/>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" value="" class="form-control js-to">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 position-relative">
                                                        <input type="text" class="js-slider" name="my_range" value="" data-min="10" data-max="100" data-type="double" data-step="1" data-grid="false"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-4 offset-lg-1">

                            <form class="bs-component">
                                <div class="form-group">
                                    <fieldset disabled>
                                        <label class="control-label" for="disabledInput">Disabled input</label>
                                        <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled="">
                                    </fieldset>
                                </div>

                                <div class="form-group">
                                    <fieldset>
                                        <label class="control-label" for="readOnlyInput">Readonly input</label>
                                        <input class="form-control" id="readOnlyInput" type="text" placeholder="Readonly input here…" readonly>
                                    </fieldset>
                                </div>

                                <div class="form-group has-success">
                                    <label class="form-control-label" for="inputSuccess1">Valid input</label>
                                    <input type="text" value="correct value" class="form-control is-valid" id="inputValid">
                                    <div class="valid-feedback">Success! You've done it.</div>
                                </div>

                                <div class="form-group has-danger">
                                    <label class="form-control-label" for="inputDanger1">Invalid input</label>
                                    <input type="text" value="wrong value" class="form-control is-invalid" id="inputInvalid">
                                    <div class="invalid-feedback">Sorry, that username's taken. Try another?</div>
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label col-form-label-lg" for="inputLarge">Large input</label>
                                    <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" id="inputLarge">
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label" for="inputDefault">Default input</label>
                                    <input type="text" class="form-control" placeholder="Default input" id="inputDefault">
                                </div>

                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group mb-3 prepend-transparent append-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label col-form-label-sm" for="inputSmall">Small input</label>
                                    <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm" id="inputSmall">
                                </div>

                                <div class="form-group">
                                    <div class="input-group mb-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" aria-label="Search" placeholder="Search">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputGroupFile02">
                                            <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Upload</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-inline">
                                    <div class="form-group mr-1 mb-2">
                                        <label for="inputPassword2" class="sr-only">Password</label>
                                        <input type="text" class="form-control" id="inputPassword2" placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-icon mb-2"><i class="mdi mdi-magnify"></i></button>
                                </div>


                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Enter your email">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary btn-icon mb-2"><i class="mdi mdi-send"></i></button>
                                    </div>
                                </div>
                            </form>

                            <div class="bs-component">
                                <fieldset>
                                    <legend>Custom forms</legend>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" checked>
                                            <label class="custom-control-label" for="customRadio1">Toggle this custom radio</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio2">Or toggle this other custom radio</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input" disabled>
                                            <label class="custom-control-label" for="customRadio3">Disabled custom radio</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
                                            <label class="custom-control-label" for="customCheck1">Check this custom checkbox</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck2" disabled>
                                            <label class="custom-control-label" for="customCheck2">Disabled custom checkbox</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch pl-0">
                                            <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                            <input type="checkbox" class="custom-control-input" id="customSwitch1" checked="">
                                            <label class="custom-control-label" for="customSwitch1">Yes</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
                                            <label class="custom-control-label" for="customSwitch1">Toggle this switch element</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" disabled id="customSwitch2">
                                            <label class="custom-control-label" for="customSwitch2">Disabled switch element</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                        </div>
                    </div>

                    <div class="mt-5">
                        <h3>Selectpicker</h3>

                        <div class="row">
                            <div class="col-lg-3 my-1">
                                <label>Normal</label>
                                <select class="selectpicker">
                                    <option>Mustard</option>
                                    <option>Ketchup</option>
                                    <option>Barbecue</option>
                                    <option>Tent</option>
                                    <option>Flashlight</option>
                                    <option>Toilet Paper</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Groups</label>
                                <select class="selectpicker">
                                    <optgroup label="Picnic">
                                        <option>Mustard</option>
                                        <option>Ketchup</option>
                                        <option>Relish</option>
                                    </optgroup>

                                    <optgroup label="Camping">
                                        <option>Tent</option>
                                        <option>Flashlight</option>
                                        <option>Toilet Paper</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Multiple choise</label>
                                <select class="selectpicker" multiple>
                                    <option>Mustard</option>
                                    <option>Ketchup</option>
                                    <option>Barbecue</option>
                                    <option>Tent</option>
                                    <option>Flashlight</option>
                                    <option>Toilet Paper</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Live search</label>
                                <select class="selectpicker" data-live-search="true">
                                    <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
                                    <option data-tokens="mustard">Burger, Shake and a Smile</option>
                                    <option data-tokens="frosting">Sugar, Spice and all things nice</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Titles</label>
                                <select class="selectpicker">
                                    <option title="Combo 1">Hot Dog, Fries and a Soda</option>
                                    <option title="Combo 2">Burger, Shake and a Smile</option>
                                    <option title="Combo 3">Sugar, Spice and all things nice</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Count selects</label>
                                <select class="selectpicker" multiple data-selected-text-format="count">
                                    <option>Mustard</option>
                                    <option>Ketchup</option>
                                    <option>Barbecue</option>
                                    <option>Tent</option>
                                    <option>Flashlight</option>
                                    <option>Toilet Paper</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Icons</label>
                                <select class="selectpicker">
                                    <option data-icon="mdi mdi-allergy">Mustard</option>
                                    <option data-icon="mdi mdi-airplane">Ketchup</option>
                                    <option data-icon="mdi mdi-air-horn">Barbecue</option>
                                    <option data-icon="mdi mdi-baby-carriage">Tent</option>
                                    <option data-icon="fa-nell">Flashlight</option>
                                    <option data-icon="fa-bullhorn">Toilet Paper</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Badges</label>
                                <select class="selectpicker">
                                    <option data-content="<span class='badge badge-success'>Relish</span>">Relish</option>
                                    <option>Mustard</option>
                                    <option>Ketchup</option>
                                    <option>Barbecue</option>
                                    <option>Tent</option>
                                    <option>Flashlight</option>
                                    <option>Toilet Paper</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Actions</label>
                                <select class="selectpicker" multiple data-actions-box="true">
                                    <option>Mustard</option>
                                    <option>Ketchup</option>
                                    <option>Relish</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Custom</label>
                                <select class="selectpicker" data-live-search="true">
                                    <option data-content="<div class='select-products'><div class='image'><img src='https://cdn.tobi.com/product_images/lg/1/plum-maddie-asymmetrical-bodycon-dress.jpg' height='50' /></div><div class='info'><span class='title'>Pink dress</span><span class='price'>$35.99</span></div></div>"></option>
                                    <option data-content="<div class='select-products'><div class='image'><img src='https://cdn.tobi.com/product_images/lg/1/plum-maddie-asymmetrical-bodycon-dress.jpg' height='50' /></div><div class='info'><span class='title'>Pink dress dress dress dress dress</span><span class='price'>$35.99</span></div></div>"></option>
                                    <option data-content="<div class='select-products'><div class='image'><img src='https://cdn.tobi.com/product_images/lg/1/plum-maddie-asymmetrical-bodycon-dress.jpg' height='50' /></div><div class='info'><span class='title'>Pink dress</span><span class='price'>$35.99</span></div></div>"></option>
                                    <option data-content="<div class='select-products'><div class='image'><img src='https://cdn.tobi.com/product_images/lg/1/plum-maddie-asymmetrical-bodycon-dress.jpg' height='50' /></div><div class='info'><span class='title'>Pink dress</span><span class='price'>$35.99</span></div></div>"></option>
                                    <option data-content="<div class='select-products'><div class='image'><img src='https://cdn.tobi.com/product_images/lg/1/plum-maddie-asymmetrical-bodycon-dress.jpg' height='50' /></div><div class='info'><span class='title'>Pink dress</span><span class='price'>$35.99</span></div></div>"></option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Custom</label>
                                <select class="selectpicker js-change-color" data-style="btn-success">
                                    <option data-change-color="btn-success">Publish</option>
                                    <option data-change-color="btn-warning">Unpublish</option>
                                    <option data-change-color="btn-secondary">Mark as Spam</option>
                                    <option data-change-color="btn-danger">Delete</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>Small</label>
                                <select class="selectpicker" data-style="btn-sm">
                                    <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
                                    <option data-tokens="mustard">Burger, Shake and a Smile</option>
                                    <option data-tokens="frosting">Sugar, Spice and all things nice</option>
                                </select>
                            </div>

                            <div class="col-lg-3 my-1">
                                <label>As link</label>
                                <select class="selectpicker as-link" data-style="btn-sm">
                                    <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
                                    <option data-tokens="mustard">Burger, Shake and a Smile</option>
                                    <option data-tokens="frosting">Sugar, Spice and all things nice</option>
                                </select>
                            </div>
                        </div>
                        <br/>

                    </div>

                    <div class="mb-5">
                        <h3>New components</h3>

                        <div class="row">
                            <div class="col-lg-3 my-1">
                                <label>Dropdown as Link</label>
                                <div>
                                    <select class="selectpicker btn-as-link" data-style="btn-sm" data-width="auto">
                                        <option>Mustard</option>
                                        <option>Ketchup</option>
                                        <option>Barbecue</option>
                                        <option>Tent</option>
                                        <option>Flashlight</option>
                                        <option>Toilet Paper</option>
                                    </select>
                                </div>
                                <div>
                                    <select class="selectpicker btn-as-link" data-width="auto">
                                        <option>Mustard</option>
                                        <option>Ketchup</option>
                                        <option>Barbecue</option>
                                        <option>Tent</option>
                                        <option>Flashlight</option>
                                        <option>Toilet Paper</option>
                                    </select>
                                </div>
                                <div>
                                    <select class="selectpicker btn-as-link" data-style="btn-lg" data-width="auto">
                                        <option>Mustard</option>
                                        <option>Ketchup</option>
                                        <option>Barbecue</option>
                                        <option>Tent</option>
                                        <option>Flashlight</option>
                                        <option>Toilet Paper</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 my-1">
                                <label>Quantity</label>
                                <div class="form-group">
                                    <div class="input-group mb-3 prepend-transparent append-transparent input-group-quantity">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-muted">BGN</span>
                                        </div>
                                        <input type="text" class="form-control" value="0.00"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text plus-minus-holder">
                                                <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group mb-3 append-transparent">
                                        <input type="text" class="form-control" value="3.0">
                                        <div class="input-group-append">
                                                    <span style="width:70px;">
                                                        <select class="selectpicker" data-width="100%">
                                                            <option>kg</option>
                                                            <option>lb</option>
                                                            <option>oz</option>
                                                            <option>g</option>
                                                        </select>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 my-1">
                                <label>Pricing</label>

                                <div class="form-group">
                                    <div class="input-group mb-3 prepend-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-muted">BGN</span>
                                        </div>
                                        <input type="text" class="form-control" value="0.00"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group mb-3 prepend-transparent append-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-muted">BGN</span>
                                        </div>
                                        <input type="text" class="form-control" value="0.00"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="tooltip" title="To put a product on sale, makeCompare at price the original price and enter the lower amount into Price."><i
                                                        class="mdi mdi-help-circle"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 my-1">
                                <input type="text" data-role="tagsinput" value="L,M,XL" placeholder="Separate options with a comma"/>

                                <select data-role="tagsinput" multiple>
                                    <option value="Amsterdam">Amsterdam</option>
                                    <option value="Washington">Washington</option>
                                    <option value="Sydney">Sydney</option>
                                    <option value="Beijing">Beijing</option>
                                    <option value="Cairo">Cairo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="cards" role="tabpanel" aria-labelledby="cards-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <div class="card text-white bg-primary mb-3" style="max-width: 20rem;">
                                    <div class="card-header text-primary">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card text-dark bg-secondary mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Secondary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card text-white bg-success mb-3" style="max-width: 20rem;">
                                    <div class="card-header text-success">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Success card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card text-white bg-danger mb-3" style="max-width: 20rem;">
                                    <div class="card-header text-danger">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Danger card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card text-white bg-warning mb-3" style="max-width: 20rem;">
                                    <div class="card-header text-warning">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Warning card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card text-white bg-info mb-3" style="max-width: 20rem;">
                                    <div class="card-header text-info">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Info card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card bg-light mb-3" style="max-width: 20rem;">
                                    <div class="card-header text-dark">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Light card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card text-white bg-dark mb-3" style="max-width: 20rem;">
                                    <div class="card-header text-dark">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Dark card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bs-component">
                                <div class="card border-primary mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card border-secondary mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Secondary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card border-success mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Success card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card border-danger mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Danger card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card border-warning mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Warning card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card border-info mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Info card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card border-light mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Light card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card border-dark mb-3" style="max-width: 20rem;">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <h4 class="card-title">Dark card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="bs-component">
                                <div class="card mb-3" style="max-width: 20rem;">
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>

                                <div class="card card-success mb-3" style="max-width: 20rem;">
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>

                                <div class="card card-danger mb-3" style="max-width: 20rem;">
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>

                                <div class="card style-1 mb-3" style="max-width: 20rem;">
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>

                                <div class="card style-1 card-success mb-3" style="max-width: 20rem;">
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>

                                <div class="card style-1 card-danger mb-3" style="max-width: 20rem;">
                                    <div class="card-body">
                                        <h4 class="card-title">Primary card title</h4>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tabs" role="tabpanel" aria-labelledby="tabs-tab">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="bs-component">
                                <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1">
                                    <a class="btn btn-outline-secondary btn-sm justify-content-center active" data-toggle="tab" href="#home">White</a>
                                    <a class="btn btn-outline-secondary btn-sm justify-content-center" data-toggle="tab" href="#profile">Yellow</a>
                                    <a class="btn btn-outline-secondary btn-sm justify-content-center" data-toggle="tab" href="#other">Red</a>
                                    <a class="btn btn-outline-secondary btn-sm justify-content-center" data-toggle="tab" href="#other">Blue</a>
                                    <a class="btn btn-outline-secondary btn-sm justify-content-center" data-toggle="tab" href="#other">Black</a>
                                </nav>
                                <br/>
                                <br/>
                                <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1">
                                    <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#home">White</a>
                                    <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#profile">Yellow</a>
                                    <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#other">Red</a>
                                    <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#other">Blue</a>
                                    <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#other">Black</a>
                                </nav>

                                <div id="myTabContent" class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="home">
                                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.
                                            Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosbysweater
                                            eu banh mi,qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                                    </div>

                                    <div class="tab-pane fade" id="profile">
                                        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko
                                            farm-to-table craft beer twee. Qui photo booth letterpress,commodo
                                            enim craftbeer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
                                    </div>

                                    <div class="tab-pane fade" id="other">
                                        <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer,
                                            iphone skateboard locavore carles etsy salvia banksyhoodie
                                            helvetica.DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="others-tab" role="tabpanel" aria-labelledby="others-tab">
                <div class="container">
                    <div class="bs-docs-section">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h3>Square dropable zones</h3>

                                <div class="dropable-zone small-zone square-zone">
                                    <div class="holder">
                                        <button type="button" class="btn btn-link">Add file</button>
                                        <p>or drop file to upload</p>
                                    </div>
                                </div>

                                <br/><br/>

                                <div class="dropable-zone square-zone">
                                    <div class="holder">
                                        <div class="dropable-zone-img"></div>

                                        <div class="progress progress-silver">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <button type="button" class="btn btn-primary btn-rounded">Add file</button>
                                        <p>or drop files to upload</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h3>Auto height dropable zones</h3>

                                <div class="dropable-zone small-zone">
                                    <div class="holder">
                                        <button type="button" class="btn btn-link">Add file</button>
                                        <p>or drop file to upload</p>
                                    </div>
                                </div>

                                <br/><br/>

                                <div class="dropable-zone">
                                    <div class="holder">
                                        <div class="dropable-zone-img"></div>

                                        <div class="progress progress-silver">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <button type="button" class="btn btn-primary btn-rounded">Add file</button>
                                        <p>or drop files to upload</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bs-docs-section">
                        <div class="row mb-4">
                            <div class="col-4 d-flex flex-column align-items-center jusityf-content-center">
                                <div class="img-circle-holder img-absolute">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder img-absolute w-60">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder w-60">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                            </div>

                            <div class="col-4 d-flex flex-column align-items-center jusityf-content-center">
                                <div class="img-circle-holder border-radius-10">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder w-60 border-radius-10">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder img-absolute border-radius-10">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder img-absolute w-60 border-radius-10">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                            </div>

                            <div class="col-4 d-flex flex-column align-items-center jusityf-content-center">
                                <div class="img-circle-holder border-radius-0">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder w-60 border-radius-0">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder img-absolute border-radius-0">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                                <br/>
                                <div class="img-circle-holder img-absolute w-60 border-radius-0">
                                    <img src="assets/img/no-image.jpg"/>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-6">
                                <h2 id="nav-breadcrumbs">Breadcrumbs</h2>
                                <div class="bs-component">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Library</li>
                                    </ol>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                                        <li class="breadcrumb-item active">Data</li>
                                    </ol>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <h2 id="pagination">Pagination</h2>
                                <div class="bs-component">
                                    <div>
                                        <ul class="pagination">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#">&laquo;</a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">4</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">5</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">&raquo;</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div>
                                        <ul class="pagination pagination-lg">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#">&laquo;</a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">4</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">5</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">&raquo;</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div>
                                        <ul class="pagination pagination-sm">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#">&laquo;</a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">4</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">5</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">&raquo;</a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Indicators
                    ================================================== -->
                    <div class="bs-docs-section">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="page-header">
                                    <h1 id="indicators">Indicators</h1>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Alerts</h2>
                                <div class="bs-component">
                                    <div class="alert alert-dismissible alert-warning">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <h4 class="alert-heading">Warning!</h4>
                                        <p class="mb-0">Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, <a href="#" class="alert-link">vel scelerisque nisl
                                                consectetur et</a>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="alert alert-dismissible alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="alert alert-dismissible alert-success">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="alert alert-dismissible alert-info">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="alert alert-dismissible alert-primary">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="alert alert-dismissible alert-secondary">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="alert alert-dismissible alert-light">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- Containers
                    ================================================== -->
                    <div class="bs-docs-section">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="page-header">
                                    <h1 id="containers">Containers</h1>
                                </div>
                                <div class="bs-component">
                                    <div class="jumbotron">
                                        <h1 class="display-3">Hello, world!</h1>
                                        <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                                        <hr class="my-4">
                                        <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
                                        <p class="lead">
                                            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <h2>List groups</h2>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Cras justo odio
                                            <span class="badge badge-primary badge-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Dapibus ac facilisis in
                                            <span class="badge badge-primary badge-pill">2</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Morbi leo risus
                                            <span class="badge badge-primary badge-pill">1</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action active">
                                            Cras justo odio
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action disabled">Morbi leo risus
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="bs-component">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">List group item heading</h5>
                                                <small>3 days ago</small>
                                            </div>
                                            <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                                            <small>Donec id elit non mi porta.</small>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">List group item heading</h5>
                                                <small class="text-muted">3 days ago</small>
                                            </div>
                                            <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                                            <small class="text-muted">Donec id elit non mi porta.</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Dialogs
                    ================================================== -->
                    <div class="bs-docs-section">


                        <div class="row">
                            <div class="col-lg-6">
                                <h2>Modals</h2>
                                <div class="bs-component">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                        Launch demo modal
                                    </button>

                                    <div class="modal fade" id="exampleModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modal title</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Modal body text goes here.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h2>Popovers</h2>
                                <div class="bs-component" style="margin-bottom: 3em;">
                                    <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="left"
                                            data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Left
                                    </button>

                                    <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="top"
                                            data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Top
                                    </button>

                                    <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="bottom"
                                            data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Bottom
                                    </button>

                                    <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="right"
                                            data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Right
                                    </button>
                                </div>
                                <h2>Tooltips</h2>
                                <div class="bs-component" style="margin-bottom: 3em;">
                                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Left</button>

                                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Top</button>

                                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">Bottom</button>

                                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Right</button>
                                </div>
                                <h2>Toasts</h2>
                                <div class="bs-component">
                                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="toast-header">
                                            <strong class="mr-auto">Bootstrap</strong>
                                            <small>11 mins ago</small>
                                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="toast-body">
                                            Hello, world! This is a toast message.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="source-modal" class="modal fade" tabindex='-1'>
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Source Code</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <pre contenteditable></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="assets/ui/plugins/js/jquery-3.4.1.min.js"></script>
<script src="assets/ui/plugins/js/plugins.js"></script>
<script>
    $(document).ready(function () {

    });
</script>
<?php include __DIR__ . '/partials/template_colors.php'; ?>
</body>
</html>
