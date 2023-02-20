<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>



    <link rel="stylesheet" href="http://localhost/mw3/userfiles/modules/microweber/css/ui.css">


    <style>
        .mw-le-btn.disabled,
        .mw-le-btn[disabled]{
            pointer-events: none;
            opacity: .7;
        }
        .mw-le-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50px;
            line-height: 40px;
            min-height: 40px;
            text-align: center;
            white-space: nowrap;
            padding: 0 20px;
            color: white;
            text-decoration: none;
            outline: none;
            cursor: pointer;
            font-size: 16px;
            background-color: #464646;
            border: none;
            box-shadow: none;

        }
        .mw-le-btn-sm{
            font-size:12px;
            line-height: 25px;
            min-height: 25px;
        }
        .mw-le-btn-primary{ background-color: #39b54a; }
        .mw-le-btn-primary2{ background-color: #0078ff; }

        .mw-le-btn-icon svg *{
            fill: white;
        }
        .mw-le-btn-icon svg{
            fill: white;
            width: 20px;
        }
        .mw-le-btn-icon{
            width: 40px;
            padding: 0;
        }

        .mw-le-nav-box{
            display: block;

            background-color: white;
            position: relative;
            box-shadow: 0 0 32px rgba(0,0,0,.17);
            border-radius: 10px;
        }
        .mw-le-nav-box-content{
            padding: 25px;
        }
        .mw-le-nav-box .mw-le-nav-box-content + .mw-le-nav-box-content{
            padding-top: 0;
        }
        .mw-le-nav-box nav a:hover{
            background-color: #eee;
        }
        .mw-le-nav-box nav a{
            display: block;
            font-size: 14px;
            text-decoration: none;
            color: #2b2b2b;
            padding: 15px 0 15px 65px;
            position: relative;
            transition: background-color .2s;
        }

        .mw-le-nav-box nav a > svg:first-child,
        .mw-le-nav-box nav a > img:first-child{
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            max-width: 25px;
            max-height: 25px;
        }


        .mw-le-hamburger span{
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;

            transition: .4s cubic-bezier(0.4, 0.0, 0.2, 1);;
            background-color: white;
            user-select: none;
        }
        .mw-le-hamburger span + span{
            top: 7px;
        }
        .mw-le-hamburger span + span + span{
            top: 14px;
        }
        .mw-le-hamburger{
            display: inline-block;
            vertical-align: middle;
            position: relative;
            width: 24px;
            height: 17px;
            cursor: pointer;
            transition: .4s cubic-bezier(0.4, 0.0, 0.2, 1);;
        }
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap');
        html{

            --toolbar-static-height: 90px;
            --toolbar-height: 90px;
            --toolbar-height-animation-speed: .4s;
        }

        *,*:before,*:after{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body{
            overflow: hidden;
            height: 100vh;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #2b2b2b;
        }
        a{
            text-decoration: none;
            color: #2b2b2b;
        }
        #live-edit-fram-holder{
            position: fixed;
            top: var(--toolbar-height);
            left: 0;
            width: 100%;
            height: calc(100% - var(--toolbar-height));
            bottom: 0;
            transition: var(--toolbar-height-animation-speed);
        }
        #live-editor-frame{
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            left: 50%;
            transform: translateX(-50%);
            transition: width var(--toolbar-height-animation-speed);
        }
        #toolbar{
            height: var(--toolbar-height);
            width: 100%;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            background-color: #000;
            display: flex;
            padding: 0 30px;
            position: relative;
            z-index: 2;
            transition:   var(--toolbar-height-animation-speed);
        }

        html.preview #toolbar{

            overflow: hidden;

        }
        @media (max-width: 1200px) {
            #preview-nav{
                display: none;
            }
        }

        #preview-nav span{
            display: inline-flex;
            width: 40px;
            height: 40px;
            justify-content: center;
            align-items: center;
            border-radius: 55px;
            background-color: #464646;
        }
        #preview-nav span.active,
        .toolbar-nav span.active{
            background-color: #0078ff;
            transition: .3s;
        }
        .toolbar-nav{
            background-color: #2b2b2b;
            border-radius: 100px;
            display: inline-flex;
            margin: 15px 0;
            padding: 10px 10px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            gap: 5px;
            vertical-align: top;
        }
        .toolbar-nav:empty{
            display: none;
        }
        .toolbar-nav-hover{
            background-color: transparent;
        }
        .toolbar-nav-hover:hover{
            background-color: #2b2b2b;
        }
        #preview-nav svg{
            width: 25px;
            fill: transparent;
            stroke: white;
            stroke-width: 2px;
        }
        #live-edit-fram-holder.has-mw-spinner:after{
            backdrop-filter: blur(10px);
        }

        #user-menu-wrapper #user-menu{
            position: absolute;
        }

        #user-menu-wrapper{
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: var(--toolbar-height);
        }

        #user-menu nav{
            padding: 15px 0;
        }
        #user-menu{
            position: absolute;
            top: 100%;
            right: 0;
            opacity: 0;
            transform: scale(.8) translateY(-20px);
            pointer-events: none;
            transition: .4s;
            width: 300px;
            transform-origin: right top;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        #user-menu-header{
            border-bottom: 1px solid #d7d7d7;
        }

        #user-menu-header h3{
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 24px;
            padding: 0 0 10px;
        }

        #user-menu-wrapper.active #user-menu{
            opacity: 1;
            transform: scale(1) translateY(0px);
            pointer-events: auto;
        }

        html[dir="rtl"] #user-menu{
            left: 0;
            right: auto;
        }


        .active > .mw-le-hamburger > span:nth-child(2){
            width: 40px;
            height: 40px;
            border-radius: 50px;
            background-color: rgb(255 255 255 / 19%);
            left: -8px;
            top: -11px;
        }
        .mw-le-hamburger > span:first-child{
            transform-origin: right center;
        }
        .mw-le-hamburger > span:last-child{
            transform-origin: right bottom;
        }
        .active > .mw-le-hamburger{
            transform: rotate(-90deg);
        }
        .active > .mw-le-hamburger > span:first-child{
            transform: rotate(-45deg) translate(-2px, -3px);
        }
        .active > .mw-le-hamburger > span:last-child{
            transform: rotate(45deg) translate(-2px, 4px);
        }






        /* Editor */


        .mw-editor-le2 .mw-editor-controller-button.mw-editor-group-button .mw-editor-group-button-caret:hover{
            background-color: rgba(0, 0, 0, 0.29);
        }
        .mw-editor-le2 .mw-editor-controller-button.mw-editor-group-button .mw-editor-group-button-caret{
            display: flex;
            height: 100%;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .mw-editor-le2 .mw-editor-controller-button.mw-editor-group-button{
            margin: 0;
            padding: 0;
        }
        .mw-editor-le2 .mw-bar-control-item-group{
            padding: 0;
        }
        .mw-editor-le2 mw-editor-button, .mw-editor-le2 .mw-bar-control-item-group{
            border-radius: 3px;
        }
        .mw-editor-le2 .mw-bar-control-item-group:hover,
        .mw-editor-le2 mw-editor-button:hover{
            background-color: rgba(0, 0, 0, 0.29);
        }
        .mw-editor-le2 .mw-editor-group-button mw-editor-button.mw-editor-controller-component{
            border-right: 1px solid transparent;
        }
        .mw-editor-le2 .mw-editor-group-button mw-editor-button.mw-editor-controller-component:hover{
            border-right-color: #eeeeee36;
        }
        .mw-editor-le2 .mw-editor-controller-component, .mw-editor-le2 .mw-bar-control-item {
            display: inline-flex;
            position: relative;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            color: white;
        }


        .mw-editor-le2 .mw-editor-option-dropdown-h1{ font-size: 30px }
        .mw-editor-le2 .mw-editor-option-dropdown-h2{ font-size: 27px }
        .mw-editor-le2 .mw-editor-option-dropdown-h3{ font-size: 25px }
        .mw-editor-le2 .mw-editor-option-dropdown-h4{ font-size: 22px }
        .mw-editor-le2 .mw-editor-option-dropdown-h5{ font-size: 20px }
        .mw-editor-le2 .mw-editor-option-dropdown-h6{ font-size: 17px }

        .mw-editor-le2 .mw-editor-controller-component-select .mw-editor-controller-component-select-values-holder{
            background-color: #2b2b2b;
            border-radius: 0 0 10px 10px;
            border-color: rgba(255, 255, 255, 0.12);
        }
        .mw-editor-le2 .mw-bar-control-item-group-contents{
            display: none;
            position: absolute;
            top: 100%;
            left: -4px;
            padding: 5px;
            white-space: nowrap;
            background-color: #2b2b2b;
            border-radius: 0 0 10px 10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        }

        .mw-bar-control-item.active .mw-bar-control-item-group-contents{
            display: block;

        }
        .mw-editor-le2 mw-editor-button{
            cursor: pointer;
            color: white;
        }
        .mw-editor-le2 {
            color: white;
        }
        .mw-editor-le2 iframe{
            border: none;
        }
        .mw-editor-le2 .mw-editor-controller-button.mw-editor-group-button .mw-editor-group-button-icon{
            color: white;
        }
        .mw-editor-le2 .mw-editor-dropdown-option{
            padding: 10px 10px;
            white-space: nowrap;
            font-size: 14px;
        }
        .mw-editor-le2 .mw-editor-controller-component-select-values-holder{
            max-height: calc(100vh - 120px) !important;
        }
        .mw-editor-le2 .mw-bar-control-item{
            position: relative;
        }
        .mw-editor-le2 .mw-editor-dropdown-option:hover{
            background-color: rgba(255,255,255, .1);
        }

        .mw-editor-le2 .mw-editor-controller-component[data-tooltip]:after{
            position: absolute;
            content: attr(data-tooltip);
            background: rgba(80,83,86,1);
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            border-radius: 3px;
            padding: 5px;
            bottom: 86%;
            left: 50%;
            transform: translateX(-50%) scale(1);
            opacity: 0;
            pointer-events: none;
            transition: .2s cubic-bezier(0.4, 0.0, 0.2, 1);
            line-height: normal;
            white-space: nowrap;
        }
        .mw-editor-le2 .mw-editor-controller-component[data-tooltip]:hover:after,
        .mw-editor-le2 .mw-editor-controller-component[data-tooltip]:focus:after{
            opacity: 1;
            margin-top:0;
            bottom: 100%;
            transform: translateX(-50%) scale(1);
        }

        .mw-editor-le2 .mw-editor-dropdown-option.mw-editor-dropdown-option-active,
        .mw-editor-le2 .mw-editor-dropdown-option.mw-editor-dropdown-option-active:hover{
            background-color: #4592ff;
        }

        /* /editor*/

        .toolbar-col{
            display: flex;
            flex-wrap: nowrap;
            gap: 20px;
        }

        #preview-button{
            position: fixed;
            top: calc(var(--toolbar-static-height) / 2);
            right: 80px;
            transform: translateY(-50%);
            z-index: 10;
        }

         #preview-button:after{
             position:absolute;
             top: 0;
             left: 50%;
             content: '';
             width: 4px;
             height: 100%;
             background-color: white;
             transform: rotate(45deg) scale(0);
             border-left: 2px solid #464646;
             transition: .4s;
             opacity: 0;
             margin-left: -1px;

         }
        html.preview #preview-button:after{
            transform: rotate(45deg) scaleY(1);
            opacity: 1;
        }
        html.preview #preview-button{
            box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
        }

        #bubble-nav{
            position: fixed;
            top: calc(var(--toolbar-height) + 100px);
            left: 30px;
            z-index: 4;
        }

        #bubble-nav span svg{
            max-width: 25px;
            max-height: 25px;
            display: block;
        }
        #bubble-nav span{
            cursor: pointer;
            display: flex;
            margin: 0 auto 5px;
            width: 50px;
            height: 50px;
            align-items: center;
            justify-content: center;
            border-radius: 50px;
            background-color: #2b2b2b;
            transform: translateX(-100px);
            box-shadow: rgb(0 0 0 / 16%) 0px 3px 6px, rgb(0 0 0 / 23%) 0px 3px 6px;
            color: white;
        }
        #bubble-nav span:nth-child(1){ transition: .4s cubic-bezier(.41,.2,.21,1.37); }
        #bubble-nav span:nth-child(2){ transition: .6s cubic-bezier(.41,.2,.21,1.37); }
        #bubble-nav span:nth-child(3){ transition: .8s cubic-bezier(.41,.2,.21,1.37); }
        #bubble-nav span:nth-child(4){ transition: 1s cubic-bezier(.41,.2,.21,1.37); }
        #bubble-nav span:nth-child(5){ transition: 1.2s cubic-bezier(.41,.2,.21,1.37); }

        body.loaded #bubble-nav span{
            transform: translateX(0px);
        }







        .modules-list-block-item-is-locked-true:before{
            content: '';
            position: absolute;
            inset: 0;
            background-color: rgba(255,255,255,.7);
            background-image: url("data:image/svg+xml,%3Csvg fill='%23c82c2c' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Ctitle%3Elock-outline%3C/title%3E%3Cpath d='M12,17C10.89,17 10,16.1 10,15C10,13.89 10.89,13 12,13A2,2 0 0,1 14,15A2,2 0 0,1 12,17M18,20V10H6V20H18M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10C4,8.89 4.89,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 24px auto;

            z-index: 2;
        }

        /* layouts dialog */
        .mw-le-layouts-dialog {

            --layouts-dialog-height: calc(100vh - 50px);
            --layouts-dialog-toolbar-height: 60px;
        }


        .mw-le-layouts-dialog .modules-list-categories{


        }

        .mw-le-dialog-block.active{
            opacity: 1;
            visibility: visible;
        }
        .mw-le-dialog-block{
            position: fixed;
            z-index: 5;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
            transition: .4s;
            opacity: 0;
            visibility: hidden;
        }

        .mw-le-layouts-dialog{

            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: calc(100vw - 60px) ;
            height: calc(100vh - 60px) ;
            min-height: 100px;
            width: 1650px;
            background-color: #ececec;

        }

        .mw-le-layouts-dialog-row {
            position: relative;
            height: var(--layouts-dialog-height);
        }
        .mw-le-layouts-dialog-col:first-child{
            width: 255px;
            left:0;
            top: 0;
            position: absolute;
            height: 100%;
            background-color: #2b2b2b;
        }
        .mw-le-layouts-dialog-col:last-child{
            height: 100%;
            position: absolute;
            left: 255px;
            right:0;
            top: 0
        }
        .mw-le-layouts-dialog .modules-list-block{
            overflow: auto;
        }
        .mw-le-layouts-dialog-col{
            flex: 1;
            display: flex;
            overflow: hidden;
            flex-direction: column;

        }
        .mw-le-layouts-dialog .modules-list-block-item:hover{
            transform: scale(1.02) ;

            box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px;
        }
        .mw-le-layouts-dialog .modules-list-block-item-description{
            display: none;
        }


        .mw-le-layouts-dialog .modules-list-block-item-is-locked-true:before{

            border: 3px solid #c82c2c;

        }

        .mw-le-layouts-dialog .modules-list-block-item:hover .modules-list-block-item-title{
            transform: scale(1.4);
        }
        .mw-le-layouts-dialog .modules-list-block-item-title{
            position: absolute;
            bottom: 0;
            right: 0;
            padding: 3px 10px;
            background-color: white;
            font-size: 10px;
            transition: .3s;
            transform-origin: right bottom;
        }
        .mw-le-layouts-dialog .modules-list-search-block .modules-list-search-field{
            height: var(--layouts-dialog-toolbar-height);
            background-color: transparent;
            border: none;
            outline: none   ;
            padding: 0 20px 0 55px;
            font-size: 14px;
            color:white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23ffffff60' viewBox='0 0 24 24'%3E%3Ctitle%3Emagnify%3C/title%3E%3Cpath d='M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 25px center;
            background-size: 19px auto;
        }
        .mw-le-layouts-dialog .modules-list-block-item{
            transition: .22s;
            display: block;
            position: relative;
            width: 305px;
            height: 160px;
            cursor: pointer;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
        }



        .mw-le-layouts-dialog .modules-list-block-item-picture{
            width: 305px;
            height: 160px;
            background-color: white;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;


        }
        .mw-le-layouts-dialog .modules-list-block:after{
            content: "";
            flex: auto;

        }
        .mw-le-overlay {
            position: fixed;
            inset: 0;
            z-index: 4;
            background-color: rgba(0,0,0,.2);
        }
        .mw-le-layouts-dialog .modules-list-block{
            padding: 30px;
            background-color: #ececec;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: space-between;
            max-height: 100%;
            overflow: auto;
        }
        .mw-le-layouts-dialog .modules-list-categories li:hover{
            color: #5f85ff
        }
        .mw-le-layouts-dialog .modules-list-categories li.active{
            color: #5f85ff
        }
        .mw-le-layouts-dialog .modules-list-categories li{
            display: block;
            cursor: pointer;
            text-transform: capitalize;
            padding: 10px 15px;



        }
        .mw-le-layouts-dialog .modules-list-categories{
            font-size: 14px;
            color: white;
            list-style: none;
            flex: 1;
            margin: 0 10px;
            padding-inline-start: 20px;
        }
        .mw-le-layouts-dialog .modules-list-search-block{
            height: var(--layouts-dialog-toolbar-height);
            background-color: #515151;
        }

        .modules-list-block-item-locked-badge{
            position: absolute;
            top: 10px;
            right: 10px;
            border-radius: 3px;
            color:white;
            background-color: #c82c2c;
            min-width: 35px;
            text-align: center;
            font-size: 10px;
            padding: 3px 6px;
            z-index: 3;

        }


        .modules-list-block::-webkit-scrollbar,
        .modules-list-categories::-webkit-scrollbar {
            width: 9px;
        }

        .modules-list-block::-webkit-scrollbar-thumb ,
        .modules-list-categories::-webkit-scrollbar-thumb {
            background-color: transparent;
            outline: none;
        }


        .modules-list-block::-webkit-scrollbar-thumb ,
        .modules-list-categories::-webkit-scrollbar-thumb {
            background-image: linear-gradient(180deg, #8a8a8a 0%, #9f9f9f 99%);
            border-radius: 100px;
            width: 9px;
        }
        .modules-list-categories{
            list-style: none;

            overflow: auto;

            color: white;
        }
        .mw-le-layouts-dialog-categories-title:after{
            height: 2px;
            background-color: white;
            flex: 1;
            content: '';
            width: 100%;
        }
        .mw-le-layouts-dialog-categories-title{
            font-size: 12px;
            text-transform: uppercase;
            color: white;
            line-height: 50px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 20px 29px;
        }
        /* /layouts dialog */



        /* modules dialog */

        .mw-le-modules-dialog {

            --layouts-dialog-height: calc(100vh - 50px);
            --layouts-dialog-toolbar-height: 60px;
        }

        .mw-le-modules-dialog{
            position: fixed;
            top: 50%;

            transform: translateY(-50%) scale(0.5);
            max-width: calc(100vw - 60px) ;
            height: calc(100vh - 60px) ;
            left: -440px;
            width: 430px;
            background-color: #2b2b2b;
            z-index: 5;
            box-shadow: rgba(0, 0, 0, 0.1) 0 10px 50px;
        }

        .mw-le-modules-dialog.active{
            left: 20px;
            transform: translateY(-50%) scale(1);
        }

        .mw-le-modules-dialog .modules-list-defaultModules{
            display: flex;
            flex-direction: column;
            max-height: 100% ;
        }
        .mw-le-modules-dialog .modules-list-block-item {
            position: relative;
            width: 120px;
            height: 90px;
            border-radius: 5px;
            background-color: #f5f5f5;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            transition: .3s;
        }

        .mw-le-modules-dialog .modules-list-block-item:hover{
            background-color: #fff;
            transform: scale(1.05);
        }

        .mw-le-modules-dialog .modules-list-block-item-title{
            white-space: nowrap;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            max-width: 100%;
            padding: 0 10px;
        }
        .mw-le-modules-dialog .modules-list-block-item-description{
            display: none;
        }
        .mw-le-modules-dialog .modules-list-block-category-section .modules-list-block-category-section-title{
            width: 100%;
            min-width: 100%;
            text-align: center;
            padding: 50px 0 10px;
        }
        .mw-le-modules-dialog .modules-list-block-category-section .modules-list-block-category-section-title h5{
            display: inline-block;
            color: white;
            border-radius: 50px;
            background-color: #464646;
            font-weight: 300;
            white-space: nowrap;
            padding: 12px 30px;
            text-transform: uppercase;
        }
        .mw-le-modules-dialog .modules-list-block{
            max-height:100%;
            overflow: auto;
            padding: 8px;
            margin: 8px;
        }
        .mw-le-modules-dialog .modules-list-block-category-section{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;

        }
        .mw-le-modules-dialog .modules-list-block-item-picture{
            width: 30px;
            height: 30px;
            background-color: transparent;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;


        }

        .mw-le-modules-dialog .modules-list-search-block .modules-list-search-field{
            height: var(--layouts-dialog-toolbar-height);
            background-color: transparent;
            border: none;
            outline: none   ;
            padding: 0 20px 0 55px;
            font-size: 14px;
            color:white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23ffffff60' viewBox='0 0 24 24'%3E%3Ctitle%3Emagnify%3C/title%3E%3Cpath d='M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 25px center;
            background-size: 19px auto;
        }

        .mw-le-dialog-close.active{
            opacity: 1;
            transform: scale(1);
        }
        .mw-le-dialog-close{
            z-index: 6;
            opacity: 0;
            transform: scale(0);
            transition: .2s;
            position: fixed;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            cursor: pointer;
            background-color: rgba(255,255,255, 1);
            background-repeat: no-repeat;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Ctitle%3Eclose%3C/title%3E%3Cpath d='M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z' /%3E%3C/svg%3E");
            background-size: contain;
            border-radius: 40px;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        }

        /* /modules dialog */


    </style>
    <script>
        mw.require('editor.js');
        mw.require('css_parser.js');
        mw.require('le2/modules-list.js');
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--<script src="https://unit.microweber.com/le2/editor.js"></script>--}}
    <script src="https://unit.microweber.com/le2/live-edit2.js"></script>
    <script>






        $.fn.reload_module  = function () {

        }


        var frame, frameHolder;

        var _CommandDialogs = [];
        var CommandDialog = function (className){
            _CommandDialogs.push(this);
            this.dialog = mw.element({
                props: {
                    className: 'mw-le-dialog-block ' +  className
                }
            });
            this.overlay = mw.element({
                props: {
                    className: 'mw-le-overlay'
                }
            });
            this.open = function () {
                this.dialog.addClass('active');
                this.overlay.addClass('active');
                this.closeButton.addClass('active');
            }
            this.close = function () {
                this.dialog.removeClass('active');
                this.overlay.removeClass('active');
                this.closeButton.removeClass('active');
            }
            this.remove = function () {
                this.close();
                setTimeout(() => {
                    this.dialog.remove();
                    this.overlay.remove();
                    this.closeButton.remove();
                }, 400);
            }
            this.closeButton = mw.element({
                props: {
                    className: 'mw-le-dialog-close'
                }
            });
            this.closeButton.on('click', e => {
                this.remove()
            })
            mw.element(document.body).append(this.overlay);
            mw.element(document.body).append(this.dialog);
            mw.element(document.body).append(this.closeButton);
            setTimeout(() => {
                this.open();

            }, 100);

        }

        document.addEventListener('keydown', function(e) {
            if ((e.key === 'Escape' || e.keyCode == 27) && _CommandDialogs.length > 0) {
                _CommandDialogs[0].remove();
                _CommandDialogs.splice(0 ,1);
            }
        }) ;


        var _modulesCache, _layotsCache;

        var _modulesDataLoader = function (modulesDialog) {
            var modulesList = new ModulesList({
                data: _modulesCache
            });
            modulesList.createCategorized().then(function (){
                modulesDialog.append(modulesList.root)

            })
        }

        var _layoutsDataLoader = function (cmmodulesDialog) {
            var modulesList = new ModulesList({
                data: _layotsCache
            });



            modulesList.create().then(function (){
                var grid = mw.element({
                    props: {
                        className: 'mw-le-layouts-dialog-row'
                    }
                });
                var colSidebar = mw.element({
                    props: {
                        className: 'mw-le-layouts-dialog-col'
                    }
                });
                var colContent = mw.element({
                    props: {
                        className: 'mw-le-layouts-dialog-col'
                    }
                });
                grid.append(colSidebar);
                grid.append(colContent);
                mw.element(modulesList.root).append(grid);
                colSidebar.append(modulesList.searchBlock);

                var categoriesTitle = mw.element({
                    props: {
                        innerHTML: 'Categories',
                        className: 'mw-le-layouts-dialog-categories-title'
                    }
                });
                colSidebar.append(categoriesTitle);
                colSidebar.append(modulesList.categoriesNavigation);
                colContent.append(modulesList.modulesList);

                cmmodulesDialog.append(modulesList.root);



            })
        }

        var command = {
            insertModule: function () {
                var cmmodulesDialog = new CommandDialog('mw-le-modules-dialog')
                var modulesDialog = cmmodulesDialog.dialog;


                if(_modulesCache) {
                    _modulesDataLoader(modulesDialog)
                } else {
                    mw.spinner({
                        element: modulesDialog.get(0),
                        decorate: true
                    });
                    /* demo */
                    fetch('<?php print api_url();  ?>live-edit/modules-list?layout_type=layout')
                        .then(function (data){
                            return data.json();
                        }).then(function (data){
                        _modulesCache = data;
                        _modulesDataLoader(modulesDialog)
                        mw.spinner({
                            element: modulesDialog.get(0),
                            decorate: true
                        }).remove()
                    })
                }



            },
            insertLayout: function () {



                var cmmodulesDialog = new CommandDialog('mw-le-layouts-dialog')
                var layOutsDialog = cmmodulesDialog.dialog;




                if(_layotsCache){
                    _layoutsDataLoader(layOutsDialog);
                    return;
                }

                mw.spinner({
                    element: layOutsDialog.get(0),
                    decorate: true
                })

                /* demo */
                fetch('<?php print api_url();  ?>live-edit/modules-list?layout_type=layout')
                    .then(function (data){
                        return data.json();
                    }).then(function (data){
                    _layotsCache = data;
                    _layoutsDataLoader(layOutsDialog)
                    mw.spinner({
                        element: layOutsDialog.get(0),
                        decorate: true
                    }).remove()
                })
            }
        }




        addEventListener('load', () => {

            mw.element('#bubble-nav [data-command]').on('click', function (){
                if(command[this.dataset.command]) {
                    command[this.dataset.command]()
                }
            })

            var userMenuWrapper = document.getElementById('user-menu-wrapper');
            document.getElementById('toolbar-user-menu-button').addEventListener('click', function () {
                userMenuWrapper.classList.toggle('active')
            });






            var initEditor = function () {
                var holder = document.querySelector('#mw-live-edit-editor');

                var _fontFamilyProvider = function () {
                    var _e = {};
                    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
                    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

                    this.provide = function (fontsArray) {
                        this.dispatch('change', fontsArray.map(function (font){
                            return {
                                label: font,
                                value: font,
                            }
                        }))
                    }

                };

                var fontFamilyProvider = new _fontFamilyProvider();
                window.fontFamilyProvider = fontFamilyProvider;
                frame.contentWindow.fontFamilyProvider = fontFamilyProvider;

                window.liveEditor = mw.Editor({
                    document: frame.contentWindow.document,
                    executionDocument: frame.contentWindow.document,
                    actionWindow: frame.contentWindow,
                    element: holder,
                    mode: 'document',
                    regions: '.edit',
                    skin: 'le2',
                    editMode: 'liveedit',
                    scopeColor: 'white',
                    controls:  [
                        [

                            {
                                group: {
                                    icon: 'mdi mdi-format-title',
                                    controls: ['format', 'lineHeight']
                                }
                            },

                            {
                                group: {
                                    controller: 'bold',
                                    controls: [ 'italic', 'underline', 'strikeThrough', 'removeFormat']
                                }
                            },
                            'fontSelector',

                            'fontSize',


                            {
                                group: {
                                    controller: 'alignLeft',
                                    controls: [ 'alignLeft', 'alignCenter', 'alignRight', 'alignJustify' ]
                                }
                            },

                            {
                                group: {
                                    controller: 'ul',
                                    controls: [ 'ol' ]
                                }
                            },


                            'image',
                            {
                                group: {
                                    controller: 'link',
                                    controls: [ 'unlink' ]
                                }
                            },
                            {
                                group: {
                                    controller: 'textColor',
                                    controls: [ 'textBackgroundColor' ]
                                }
                            },



                        ]
                    ],
                    smallEditorPositionX: 'center',
                    smallEditorSkin: 'lite',

                    interactionControls: [

                    ],

                    id: 'live-edit-wysiwyg-editor',

                    minHeight: 250,
                    maxHeight: '70vh',
                    state: frame.contentWindow.mw.liveEditState,

                    fontFamilyProvider: fontFamilyProvider
                });


                var btnUndo = document.getElementById('toolbar-undo')
                var btnRedo = document.getElementById('toolbar-redo')

                liveEditor.state.on('record', function (){
                    console.log(liveEditor.state)
                    btnRedo.disabled = !liveEditor.state.hasPrev;
                    btnUndo.disabled = !liveEditor.state.hasNext;
                })
                liveEditor.state.on('change', function (){
                    console.log(1)
                    btnRedo.disabled = !liveEditor.state.hasPrev;
                    btnUndo.disabled = !liveEditor.state.hasNext;
                })

                btnUndo.addEventListener('click', function (){
                    liveEditor.state.undo()
                });
                btnRedo.addEventListener('click', function (){
                    liveEditor.state.redo()
                });

/*                liveEditor.on('action', function (){
                    mw.wysiwyg.change(liveEditor.api.elementNode(liveEditor.api.getSelection().focusNode))
                })
                liveEditor.on('smallEditorReady', function (){
                    fontFamilyProvider.provide(mw.top().wysiwyg.fontFamiliesExtended);
                })
                $(liveEditor).on('selectionchange', function (){
                    var sel = liveEditor.getSelection();
                    if(sel.rangeCount) {
                        liveEditor.lastRange =  sel.getRangeAt(0) ;
                    } else {
                        liveEditor.lastRange = undefined;
                    }

                })*/

                holder.innerHTML = '';
                holder.appendChild(liveEditor.wrapper);


                var memPin = liveEditor.storage.get(liveEditor.settings.id + '-small-editor-pinned');
                if(typeof memPin === 'undefined') {
                    liveEditor.smallEditorApi.pin()
                }

            }


            initEditor()









            var _reTypes = {
                tablet: 800,
                phone: 400,
                desktop: '100%',
            }
            const responsiveEmulatorSet = function (key) {
                var width = _reTypes[key];
                if(typeof width === 'number'){
                    width = width + 'px'
                }
                frame.style.width = width;

                mw.element('[data-preview]').removeClass('active')
                mw.element('[data-preview="'+key+'"]').addClass('active')
            };





            Array.from(document.querySelectorAll('#preview-nav [data-preview]')).forEach(node => {
                node.addEventListener('click', function (){
                    responsiveEmulatorSet(this.dataset.preview)
                })
            });

            document.getElementById('save-button').addEventListener('click', function () {
                frame.contentWindow.mw.drag.save()
            })





        });



        var _hascss, isPreview = true;


        var previewMode = function () {
            document.documentElement.classList.add('preview');
            document.documentElement.style.setProperty('--toolbar-height', '0px');
            frame.contentWindow.document.documentElement.classList.add('mw-le--page-preview');
            frame.contentWindow.document.body.classList.remove('mw-live-edit');
        }

        var liveEditMode = function () {
            document.documentElement.classList.remove('preview');
            document.documentElement.style.setProperty('--toolbar-height', document.documentElement.style.getPropertyValue( '--toolbar-static-height'));
            frame.contentWindow.document.documentElement.classList.remove('mw-le--page-preview');
            frame.contentWindow.document.body.classList.add('mw-live-edit');
        }

        var pagePreviewToggle = function () {
            isPreview = !isPreview;
            if(!isPreview) {
                previewMode();
            } else {
                liveEditMode()
            }


            if(!_hascss) {
                _hascss = true;
                var css = `
                html.mw-le--page-preview body{
                    padding-top: 0 !important
                }
                html.mw-le--page-preview .mw_image_resizer,
                html.mw-le--page-preview #live_edit_toolbar_holder,
                html.mw-le--page-preview .mw-handle-item,
                html.mw-le--page-preview .mw-selector,
                html.mw-le--page-preview .mw_dropable,
                html.mw-le--page-preview .mw-padding-ctrl,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview .mw-cloneable-control,
                html.mw-le--page-preview #live_edit_toolbar_holder
                {
                    display: none !important
                }
            `

                var node = frame.contentWindow.document.createElement('style');
                node.textContent = css;
                frame.contentWindow.document.body.appendChild(node)
            }


        }
    </script>


</head>
<body>


<div id="bubble-nav">
    <span title="Insert layout" data-command="insertLayout">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 15.6C13.3 15.8 13.7 15.8 14 15.6L19 12.7V13C19.7 13 20.4 13.1 21 13.4V11.6L22 11C22.5 10.7 22.6 10.1 22.4 9.6L20.9 7.1C20.8 6.9 20.7 6.7 20.5 6.6L12.6 2.2C12.4 2.1 12.2 2 12 2S11.6 2.1 11.4 2.2L3.6 6.6C3.4 6.7 3.2 6.8 3.1 7L1.6 9.6C1.3 10.1 1.5 10.7 2 11C2.3 11.2 2.7 11.2 3 11V16.5C3 16.9 3.2 17.2 3.5 17.4L11.4 21.8C11.6 21.9 11.8 22 12 22S12.4 21.9 12.6 21.8L13.5 21.3C13.2 20.7 13.1 20 13 19.3M11 19.3L5 15.9V9.2L11 12.6V19.3M20.1 9.7L13.8 13.3L13.2 12.3L19.5 8.7L20.1 9.7M12 10.8V4.2L18 7.5L12 10.8M20 15V18H23V20H20V23H18V20H15V18H18V15H20Z" /></svg>
    </span>
    <span title="Insert layout" data-command="insertModule"></span>
    <span></span>
    <span></span>
</div>


<?php
$back_url = site_url() . 'admin/view:content';
if (defined('CONTENT_ID')) {
    if ((!defined('POST_ID') or POST_ID == false) and !defined('PAGE_ID') or PAGE_ID != false and PAGE_ID == CONTENT_ID) {
        $back_url .= '#action=showposts:' . PAGE_ID;
    } else {
        $back_url .= '#action=editpage:' . CONTENT_ID;
    }
} else if (isset($_COOKIE['back_to_admin'])) {
    $back_url = $_COOKIE['back_to_admin'];
}

$user = get_user();

?>
    <div id="toolbar">
        <div class="toolbar-nav toolbar-nav-hover">
            <a href="<?php print $back_url; ?>" class="mw-le-btn mw-le-btn-icon mw-le-btn-primary2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 32px;"><path d="M21,11H6.83L10.41,7.41L9,6L3,12L9,18L10.41,16.58L6.83,13H21V11Z" /></svg>
            </a>
        </div>
        <nav id="preview-nav" class="toolbar-nav toolbar-nav-hover">

            <span data-preview="desktop" class="active">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 48 36.17" style="enable-background:new 0 0 48 36.17;" xml:space="preserve">


                    <path d="M25.59,34.11h-3.58v-6.59h3.58V34.11z M14.16,34.88L14.16,34.88c0-0.71,0.58-1.29,1.29-1.29h17.1
                        c0.71,0,1.29,0.58,1.29,1.29v0c0,0.71-0.58,1.29-1.29,1.29h-17.1C14.74,36.17,14.16,35.59,14.16,34.88z"/>


                    <path class="st0" d="M3.32,27.6h41.35c1.53,0,2.76-1.24,2.76-2.76V3.24c0-1.53-1.24-2.76-2.76-2.76H3.32
                        c-1.53,0-2.76,1.24-2.76,2.76v21.6C0.56,26.37,1.8,27.6,3.32,27.6z"/>

                </svg>
            </span>

            <span data-preview="tablet">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">

                <path class="st0" d="M24.45,39.5c0.56,0,1.03-0.18,1.39-0.55c0.37-0.37,0.55-0.83,0.55-1.39s-0.18-1.03-0.55-1.39
                    c-0.37-0.37-0.83-0.55-1.39-0.55s-1.03,0.18-1.39,0.55c-0.37,0.37-0.55,0.83-0.55,1.39s0.18,1.03,0.55,1.39
                    C23.42,39.32,23.88,39.5,24.45,39.5z M7,46c-0.8,0-1.5-0.3-2.1-0.9C4.3,44.5,4,43.8,4,43V5c0-0.8,0.3-1.5,0.9-2.1C5.5,2.3,6.2,2,7,2
                    h34c0.8,0,1.5,0.3,2.1,0.9C43.7,3.5,44,4.2,44,5v38c0,0.8-0.3,1.5-0.9,2.1C42.5,45.7,41.8,46,41,46H7z"/>
                </svg>

            </span>

            <span data-preview="phone">
                <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">

                <path class="st0" d="M18,11.5c0.43,0,0.79-0.14,1.08-0.43c0.28-0.28,0.42-0.64,0.42-1.07s-0.14-0.79-0.42-1.07
                    C18.79,8.64,18.43,8.5,18,8.5s-0.79,0.14-1.08,0.43C16.64,9.21,16.5,9.57,16.5,10s0.14,0.79,0.42,1.07
                    C17.21,11.36,17.57,11.5,18,11.5z M13,46c-0.8,0-1.5-0.3-2.1-0.9C10.3,44.5,10,43.8,10,43V5c0-0.8,0.3-1.5,0.9-2.1
                    C11.5,2.3,12.2,2,13,2h22c0.8,0,1.5,0.3,2.1,0.9C37.7,3.5,38,4.2,38,5v38c0,0.8-0.3,1.5-0.9,2.1C36.5,45.7,35.8,46,35,46H13z"/>
                </svg>
            </span>
        </nav>
        <div class="toolbar-nav" id="mw-live-edit-editor"></div>
        <div class="toolbar-col">
            <div class="toolbar-nav toolbar-nav-hover">
                <button class="mw-le-btn mw-le-btn-icon" id="toolbar-undo" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z" /></svg>
                </button>
                <button class="mw-le-btn mw-le-btn-icon" id="toolbar-redo" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>redo</title><path d="M18.4,10.6C16.55,9 14.15,8 11.5,8C6.85,8 2.92,11.03 1.54,15.22L3.9,16C4.95,12.81 7.95,10.5 11.5,10.5C13.45,10.5 15.23,11.22 16.62,12.38L13,16H22V7L18.4,10.6Z" /></svg>
                </button>
                <span class="mw-le-btn mw-le-btn-primary" id="save-button">
                    Save
                </span>
            </div>
                <span style="width: 50px"></span>
                <span class="mw-le-btn mw-le-btn-icon" id="preview-button" onclick="pagePreviewToggle()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>eye</title><path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" /></svg>
                </span>
            <div id="user-menu-wrapper">
                <span class="mw-le-hamburger" id="toolbar-user-menu-button">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <div id="user-menu" class="mw-le-nav-box">
                    <div class="mw-le-nav-box-content" id="user-menu-header">
                        <small>Project</small>
                        <h3>Boris Website</h3>
                        <span class="mw-le-btn mw-le-btn-sm ">
                            In Test Period
                        </span>
                        <span class="mw-le-btn mw-le-btn-sm mw-le-btn-primary2">
                            Upgrade
                        </span>
                    </div>
                    <nav>
                        <a href="<?php print $back_url?>">
                            <svg viewBox="0 0 40 40"><path d="M20 27.3l2.1-2.1-3.7-3.7h9.1v-3h-9.1l3.7-3.7-2.1-2.1-7.3 7.3 7.3 7.3zM20 40c-2.73 0-5.32-.52-7.75-1.58-2.43-1.05-4.56-2.48-6.38-4.3s-3.25-3.94-4.3-6.38S0 22.73 0 20c0-2.77.53-5.37 1.57-7.8s2.48-4.55 4.3-6.35 3.94-3.22 6.38-4.28S17.27 0 20 0c2.77 0 5.37.53 7.8 1.57s4.55 2.48 6.35 4.28c1.8 1.8 3.23 3.92 4.28 6.35C39.48 14.63 40 17.23 40 20c0 2.73-.52 5.32-1.58 7.75-1.05 2.43-2.48 4.56-4.28 6.38-1.8 1.82-3.92 3.25-6.35 4.3C25.37 39.48 22.77 40 20 40zm0-3c4.73 0 8.75-1.66 12.05-4.97C35.35 28.71 37 24.7 37 20c0-4.73-1.65-8.75-4.95-12.05C28.75 4.65 24.73 3 20 3c-4.7 0-8.71 1.65-12.02 4.95S3 15.27 3 20c0 4.7 1.66 8.71 4.98 12.03C11.29 35.34 15.3 37 20 37z"/></svg>
                            Back to Admin
                        </a>
                        <a href="">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 40 40" xml:space="preserve" enable-background="new 0 0 40 40"><path d="M14.7 23c-2 0-3.6-.7-5-2-1.3-1.4-2-3-2-4.9 0-1.9.7-3.5 2-4.9 1.4-1.3 3-2 5-2 1.8 0 3.5.7 4.8 2 1.4 1.4 2 3 2 4.9 0 1.9-.6 3.5-2 4.9-1.3 1.3-3 2-4.8 2zm0-3a3.8 3.8 0 0 0 3.9-3.9c0-1.1-.4-2-1.2-2.8a3.8 3.8 0 0 0-2.7-1c-1.1 0-2 .3-2.8 1-.8.8-1.1 1.7-1.1 2.8 0 1 .3 2 1.1 2.8.8.7 1.7 1.1 2.8 1.1zm15 5.3c-1.5 0-2.7-.5-3.8-1.6-1-1-1.5-2.2-1.5-3.7s.5-2.7 1.6-3.8 2.2-1.5 3.7-1.5 2.7.5 3.8 1.6S35 18.4 35 20s-.5 2.7-1.6 3.8-2.2 1.5-3.7 1.5zM17.1 36.8c1.6-3 3.6-5 6.1-6S28 29 29.7 29a12.6 12.6 0 0 1 4.2.6A18.3 18.3 0 0 0 37 20c0-4.7-1.6-8.8-5-12-3.3-3.3-7.3-5-12-5S11.2 4.7 8 8a16.8 16.8 0 0 0-2.2 21.2 19.2 19.2 0 0 1 13.8-1.4 13.6 13.6 0 0 0-3.2 2.2H14.8a16.2 16.2 0 0 0-7.1 1.6c1.2 1.4 2.7 2.5 4.3 3.4s3.4 1.5 5.2 1.8zM20 40A20.3 20.3 0 0 1 1.6 27.7 19.4 19.4 0 0 1 5.9 5.8a20.2 20.2 0 0 1 21.9-4.2A20.3 20.3 0 0 1 40 20a20.3 20.3 0 0 1-12.2 18.4c-2.4 1-5 1.6-7.8 1.6z"/></svg>
                            Users
                        </a>
                        <a href="">
                            <svg viewBox="0 0 40 40"><path d="M15.4 40l-1-6.3c-.63-.23-1.3-.55-2-.95-.7-.4-1.32-.82-1.85-1.25l-5.9 2.7L0 26l5.4-3.95a5.1 5.1 0 01-.12-1.02c-.02-.39-.03-.73-.03-1.03s.01-.64.02-1.02c.02-.38.06-.73.12-1.02L0 14l4.65-8.2 5.9 2.7c.53-.43 1.15-.85 1.85-1.25.7-.4 1.37-.7 2-.9l1-6.35h9.2l1 6.3c.63.23 1.31.54 2.02.93.72.38 1.33.81 1.83 1.27l5.9-2.7L40 14l-5.4 3.85c.07.33.11.69.12 1.08a19.5 19.5 0 010 2.13c-.02.37-.06.72-.12 1.05L40 26l-4.65 8.2-5.9-2.7c-.53.43-1.14.86-1.83 1.28-.68.42-1.36.72-2.02.92l-1 6.3h-9.2zM20 26.5c1.8 0 3.33-.63 4.6-1.9s1.9-2.8 1.9-4.6-.63-3.33-1.9-4.6-2.8-1.9-4.6-1.9-3.33.63-4.6 1.9-1.9 2.8-1.9 4.6.63 3.33 1.9 4.6 2.8 1.9 4.6 1.9zm0-3c-.97 0-1.79-.34-2.48-1.02-.68-.68-1.02-1.51-1.02-2.48s.34-1.79 1.02-2.48c.68-.68 1.51-1.02 2.48-1.02s1.79.34 2.48 1.02c.68.68 1.02 1.51 1.02 2.48s-.34 1.79-1.02 2.48c-.69.68-1.51 1.02-2.48 1.02zM17.8 37h4.4l.7-5.6c1.1-.27 2.14-.68 3.12-1.25s1.88-1.25 2.68-2.05l5.3 2.3 2-3.6-4.7-3.45c.13-.57.24-1.12.33-1.67s.12-1.11.12-1.67-.03-1.12-.1-1.67-.18-1.11-.35-1.67L36 13.2l-2-3.6-5.3 2.3c-.77-.87-1.63-1.59-2.6-2.17s-2.03-.96-3.2-1.12L22.2 3h-4.4l-.7 5.6c-1.13.23-2.19.63-3.17 1.2s-1.86 1.27-2.62 2.1L6 9.6l-2 3.6 4.7 3.45c-.13.57-.24 1.12-.32 1.67s-.13 1.11-.13 1.68.04 1.12.12 1.67c.08.55.19 1.11.32 1.67L4 26.8l2 3.6 5.3-2.3c.8.8 1.69 1.48 2.68 2.05s2.02.98 3.12 1.25l.7 5.6z"/></svg>
                            Website Settings
                        </a>
                        <a href="">
                            <svg viewBox="0 0 40 32.29"><path d="M40 3v26c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V3C0 2.2.3 1.5.9.9 1.5.3 2.2 0 3 0h34c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1zM3 8.45h34V3H3v5.45zm0 6.45V29h34V14.9H3zM3 29V3v26z"/></svg>
                            Plans and Payments
                        </a>
                        <a href="<?php print site_url('logout'); ?>">
                            <svg viewBox="0 0 36 36.1"><path d="M3 36.1c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V22.6h3v10.5h30V3H3v10.6H0V3C0 2.2.3 1.5.9.9S2.2 0 3 0h30c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1v30.1c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3zm11.65-8.35L12.4 25.5l5.9-5.9H0v-3h18.3l-5.9-5.9 2.25-2.25 9.65 9.65-9.65 9.65z"/></svg>
                            Log out
                        </a>
                    </nav>
                </div>
            </div>
        </div>


    </div>
    <div id="live-edit-fram-holder">
        <iframe id="live-editor-frame"
                title="Inline Frame Example"
                width="100%"
                height="2000"
                referrerpolicy="no-referrer"
                frameborder="0"
                src="<?php print site_url(); ?>?editmode=n"
                data-src="about:blank">
        </iframe>
    </div>

<script>

    frame = document.getElementById('live-editor-frame');
    frameHolder = document.getElementById('live-edit-fram-holder');
    mw.spinner({
        element: frameHolder, size: 52, decorate: true
    })

    frame.addEventListener('load', function () {
        var doc = frame.contentWindow.document;
        /*var link = doc.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://unit.microweber.com/le2/live-edit2.css.css';
        doc.body.appendChild(link);

        liveEdit = new LiveEdit({
            root: frame.contentWindow.document.body,
            strict: false,
            mode: 'auto',
            document: frame.contentWindow.document
        })*/

        mw.spinner({
            element: frameHolder
        }).remove()

    })

</script>


</body>
</html>
