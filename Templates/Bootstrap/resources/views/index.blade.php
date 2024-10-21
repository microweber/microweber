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


        <x-bootstrap-container>
            <x-bootstrap-row>

                <x-bootstrap-col size="3">
                    <x-bootstrap-card>

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

                    </x-bootstrap-card>
                </x-bootstrap-col>

                <x-bootstrap-col size="3">
                    <x-bootstrap-card>

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

                    </x-bootstrap-card>
                </x-bootstrap-col>

                <x-bootstrap-col size="3">
                    <x-bootstrap-card>

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

                    </x-bootstrap-card>
                </x-bootstrap-col>

            </x-bootstrap-row>
        </x-bootstrap-container>


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
