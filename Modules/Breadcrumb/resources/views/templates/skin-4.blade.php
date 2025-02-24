<?php

/*

  type: layout

  name: skin-4

  description: skin-4

*/

?>
@include('modules.breadcrumb::partials.breadcrumb_params')

<style>
   .mw-big2-breadcrumb-skin-4 {
        text-align: center;
        padding: 20px;
        h2 {
            padding:0 0 30px;
            text-transform:uppercase;
            font-size:.9rem;
            font-weight:600;
            letter-spacing:.01rem;
            color: var(--mw-heading-color);
        }
        ul {
            list-style: none;
            display: inline-table;
            li {
                display: inline;
                a {
                    display: block;
                    float: left;
                    height: 81px;
                    background: #F3F5FA;

                    text-align: center;
                    padding: 30px 20px 0 60px;
                    position: relative;
                    margin: 0 10px 0 0;

                    font-size: 20px;
                    text-decoration: none;
                    color: var(--mw-heading-color);

                    &:after {
                        content: "";
                        border-top: 40px solid transparent;
                        border-bottom: 40px solid transparent;
                        border-left: 40px solid #F3F5FA;
                        position: absolute;
                        right: -40px;
                        top: 0;
                        z-index: 1;
                    }
                    &:before {
                        content: "";
                        border-top: 40px solid transparent;
                        border-bottom: 40px solid transparent;
                        border-left: 40px solid #fff;
                        position: absolute;
                        left: 0;
                        top: 0;
                    }

                    i {
                        font-size: 24px;
                    }
                }
            }
        }

       ul li:first-child a {
           border-top-left-radius: 10px; border-bottom-left-radius: 10px;
       }
       ul li:first-child a:before {
           display: none;
       }

       ul li:last-child a {
           padding-right: 40px;
           border-top-right-radius: 10px; border-bottom-right-radius: 10px;
       }
       ul li:last-child a:after {
           display: none;
       }

       ul li a:hover, ul li a.active {
           background: var(--mw-primary-color);
           color:#fff;
       }
       ul li a:hover:after, ul li a.active:after {
           border-left-color: var(--mw-primary-color);
           color:#fff;
       }


    }
</style>


<div class="mw-big2-breadcrumb-skin-4">
    <h2>Breadcrumbs</h2>

    <ul>
        <li>
            <a href="{{ $homepage['url'] }}">
                <i class="fa fa-home" aria-hidden="true"></i>
            </a>
        </li>

        @if(!empty($data))
            @foreach($data as $item)
                @if($loop->last)
                    <li>
                        <a class=" active">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @else
                   <li>
                       <a href="{{ $item['url'] }}">
                           {{ $item['title'] }}
                       </a>
                   </li>
                @endif
            @endforeach
        @endif
    </ul>
</div>



