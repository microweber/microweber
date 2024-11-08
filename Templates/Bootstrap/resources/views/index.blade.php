<?php

/*
  type: layout
  content_type: static
  name: Home
  position: 1
  description: Home
*/


?>
@extends('templates.bootstrap::layouts.master')

@section('content')


    <div class="edit main-content" data-layout-container rel="content" field="content">


        <x-container>

            <x-hero editable="true" align="center">

                <x-slot name="image">{{asset('templates/bootstrap/img/heros/illustration-2.png')}}</x-slot>

                <x-slot name="title">
                    <h1>Welcome to Microweber</h1>
                </x-slot>
                <x-slot name="content">
                    <p>
                        Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use, and it's a great tool for building websites, online shops, blogs, and more. It's based on the Laravel PHP framework and the Bootstrap front-end framework.
                    </p>
                </x-slot>
                <x-slot name="actions">
                    <a href="#" class="btn btn-primary">
                        Get Started
                    </a>
                    <a href="#" class="btn btn-secondary">
                        Learn More
                    </a>
                </x-slot>
            </x-hero>

        </x-container>




        <br />
        <br />
        <br />
        <br />

        <x-container>

            <x-simple-text align="right">
                <x-slot name="title">
                    <h1>Welcome to Microweber</h1>
                </x-slot>
                <x-slot name="content">
                    <p>
                        Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use, and it's a great tool for building websites, online shops, blogs, and more. It's based on the Laravel PHP framework and the Bootstrap front-end framework.
                    </p>
                </x-slot>
            </x-simple-text>

            <x-row>

                <x-col col="4" col-lg="12">
                    <x-card>

                        <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                        <x-slot name="title">
                            Microweber Card
                        </x-slot>

                        <x-slot name="content">
                            <p>
                                Some quick example text to build on the card title and make up the bulk of the card's content.
                            </p>
                        </x-slot>

                        <x-slot name="footer">
                            <a href="#" class="btn btn-primary">
                                Go somewhere
                            </a>
                        </x-slot>

                    </x-card>
                </x-col>

                <x-col size="4">
                    <x-card theme="success">

                        <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                        <x-slot name="title">
                            CloudVision Cart
                        </x-slot>

                        <x-slot name="content">
                            <p>
                                Some quick example text to build on the card title and make up the bulk of the card's content.
                            </p>
                        </x-slot>

                        <x-slot name="footer">
                            <a href="#" class="btn btn-primary">
                                Go somewhere
                            </a>
                        </x-slot>

                    </x-card>
                </x-col>

                <x-col size="4">
                    <x-card theme="danger">

                        <x-slot name="image">{{asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png')}}</x-slot>

                        <x-slot name="title">
                            CHAT GPT Card
                        </x-slot>

                        <x-slot name="content">
                            <p>
                                Some quick example text to build on the card title and make up the bulk of the card's content.
                            </p>
                        </x-slot>

                        <x-slot name="footer">
                            <a href="#" class="btn btn-primary">
                                Go somewhere
                            </a>
                        </x-slot>

                    </x-card>
                </x-col>

            </x-row>
        </x-container>


        <module type="layouts" template="jumbotron/skin-1"/>
        <module type="layouts" template="features/skin-4"/>
        <module type="layouts" template="content/skin-2"/>
        <module type="layouts" template="features/skin-3"/>
        <module type="layouts" template="blog/skin-1"/>
        <module type="layouts" template="content/skin-6"/>
        <module type="layouts" template="content/skin-4"/>
        <module type="layouts" template="text-block/skin-4"/>
    </div>
@endsection
