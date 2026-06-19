{{--
type: layout
name: Team 19
position: 19
categories: Team
--}}

<style>
    .team-19 .team {
        margin-top: 100px;
        padding-top: 140px;
    }

    .team-19 .team-member {
        position: relative;
    }

    @media (max-width: 1400px) {
        .team-19 .team-member {
            margin-bottom: 140px;
        }
    }

    .team-19 .team-member:hover img {
        top: -120px;
    }

    .team-19 .team-member img {
        max-width: 220px;
        border-radius: 50%;
        position: absolute;
        top: -110px;
        left: 50%;
        transform: translateX(-110px);
        transition: all .3s;
    }

    .team-19 .team-member .main-content {
        border-radius: 25px;
        padding: 140px 30px 40px 30px;
        background-color: #f1f0fe;
        text-align: center;
    }

    .team-19 .team-member .main-content span.category {
        color: #7a6ad8;
        font-size: 15px;
    }

    .team-19 .team-member .main-content h4 {
        font-size: 22px;
        font-weight: 600;
        margin-top: 8px;
        margin-bottom: 15px;
    }

    .team-19 .team-member .main-content ul li {
        display: inline-block;
        margin: 0px 2px;
    }

    .team-19 .team-member .main-content ul li a {
        background-color: #fff;
        display: inline-block;
        text-align: center;
        line-height: 40px;
        font-size: 18px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        transition: all .3s;
    }

    .team-19 .team-member .main-content ul li a i:before {
        font-size: 18px;
    }

    .team-19 .team-member .main-content ul li a:hover {
        background-color: #7a6ad8;
        color: #fff;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section team-19"
    field-name="layout-team-skin-19"
    container-class="mw-layout-container no-element container-fluid edit"
>
    <div class="container">
                <module type="teamcard" template="skin-17"/>
            </div>
</x-layout-section>
