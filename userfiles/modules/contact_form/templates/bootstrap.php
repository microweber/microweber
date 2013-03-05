<?php

/*

type: layout

name: Bootstrap

description: Bootstrap

*/

 ?>
 <style>
 /*!
 * Bootstrap v2.2.2
 *
 * Copyright 2012 Twitter, .black Inc
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Designed and built with all the love in the world @twitter by @mdo and @fat.
 */
  .black {;
	background-color:black;
	font-family: Verdana, Geneva, sans-serif;
	text-decoration: none;  
  }
  
.black .clearfix {
  *zoom: 1;}
.black .clearfix:before, .black .clearfix:after {
  display: table;
  content: "";
  line-height: 0;}
.black .clearfix:after {
  clear: both;}
.black .hide-text {
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;}
.black .input-block-level {
  display: block;
  width: 100%;
  min-height: 30px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;}
.black form {
  margin: 0 0 20px;}
.black fieldset {
  padding: 0;
  margin: 0;
  border: 0;}
.black legend {
  display: block;
  width: 100%;
  padding: 0;
  margin-bottom: 20px;
  font-size: 21px;
  line-height: 40px;
  color: #333333;
  border: 0;
  border-bottom: 1px solid #e5e5e5;}
.black legend small {
  font-size: 15px;
  color: #999999;}
.black label, .black input, .black button, .black select, .black textarea {
  font-size: 14px;
  font-weight: normal;
  line-height: 20px;}
.black input, .black button, .black select, .black textarea {
  font-family: "Helvetica Neue", .black Helvetica, .black Arial, .black sans-serif;}
.black label {
  display: block;
  margin-bottom: 5px;}
.black select, .black textarea, .black input[type="text"], .black input[type="password"], .black input[type="datetime"], .black input[type="datetime-local"], .black input[type="date"], .black input[type="month"], .black input[type="time"], .black input[type="week"], .black input[type="number"], .black input[type="email"], .black input[type="url"], .black input[type="search"], .black input[type="tel"], .black input[type="color"], .black .uneditable-input {
  display: inline-block;
  height: 20px;
  padding: 4px 6px;
  margin-bottom: 10px;
  font-size: 14px;
  line-height: 20px;
  color: #555555;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  vertical-align: middle;}
.black input, .black textarea, .black .uneditable-input {
  width: 206px;}
.black textarea {
  height: auto;}
.black textarea, .black input[type="text"], .black input[type="password"], .black input[type="datetime"], .black input[type="datetime-local"], .black input[type="date"], .black input[type="month"], .black input[type="time"], .black input[type="week"], .black input[type="number"], .black input[type="email"], .black input[type="url"], .black input[type="search"], .black input[type="tel"], .black input[type="color"], .black .uneditable-input {
  background-color: #ffffff;
  border: 1px solid #cccccc;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  -webkit-transition: border linear .2s, .black box-shadow linear .2s;
  -moz-transition: border linear .2s, .black box-shadow linear .2s;
  -o-transition: border linear .2s, .black box-shadow linear .2s;
  transition: border linear .2s, .black box-shadow linear .2s;}
.black textarea:focus, .black input[type="text"]:focus, .black input[type="password"]:focus, .black input[type="datetime"]:focus, .black input[type="datetime-local"]:focus, .black input[type="date"]:focus, .black input[type="month"]:focus, .black input[type="time"]:focus, .black input[type="week"]:focus, .black input[type="number"]:focus, .black input[type="email"]:focus, .black input[type="url"]:focus, .black input[type="search"]:focus, .black input[type="tel"]:focus, .black input[type="color"]:focus, .black .uneditable-input:focus {
  border-color: rgba(82, .black 168, .black 236, .black 0.8);
  outline: 0;
  outline: thin dotted \9;
  /* IE6-9 */

  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black .075), .black 0 0 8px rgba(82, .black 168, .black 236, .black .6);
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black .075), .black 0 0 8px rgba(82, .black 168, .black 236, .black .6);
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black .075), .black 0 0 8px rgba(82, .black 168, .black 236, .black .6);}
.black input[type="radio"], .black input[type="checkbox"] {
  margin: 4px 0 0;
  *margin-top: 0;
  /* IE7 */

  margin-top: 1px \9;
  /* IE8-9 */

  line-height: normal;}
.black input[type="file"], .black input[type="image"], .black input[type="submit"], .black input[type="reset"], .black input[type="button"], .black input[type="radio"], .black input[type="checkbox"] {
  width: auto;}
.black select, .black input[type="file"] {
  height: 30px;
  /* In IE7, .black the height of the select element cannot be changed by height, .black only font-size */

  *margin-top: 4px;
  /* For IE7, .black add top margin to align select with labels */

  line-height: 30px;}
.black select {
  width: 220px;
  border: 1px solid #cccccc;
  background-color: #ffffff;}
.black select[multiple], .black select[size] {
  height: auto;}
.black select:focus, .black input[type="file"]:focus, .black input[type="radio"]:focus, .black input[type="checkbox"]:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;}
.black .uneditable-input, .black .uneditable-textarea {
  color: #999999;
  background-color: #fcfcfc;
  border-color: #cccccc;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, .black 0, .black 0, .black 0.025);
  -moz-box-shadow: inset 0 1px 2px rgba(0, .black 0, .black 0, .black 0.025);
  box-shadow: inset 0 1px 2px rgba(0, .black 0, .black 0, .black 0.025);
  cursor: not-allowed;}
.black .uneditable-input {
  overflow: hidden;
  white-space: nowrap;}
.black .uneditable-textarea {
  width: auto;
  height: auto;}
.black input:-moz-placeholder, .black textarea:-moz-placeholder {
  color: #999999;}
.black input:-ms-input-placeholder, .black textarea:-ms-input-placeholder {
  color: #999999;}
.black input::-webkit-input-placeholder, .black textarea::-webkit-input-placeholder {
  color: #999999;}
.black .radio, .black .checkbox {
  min-height: 20px;
  padding-left: 20px;}
.black .radio input[type="radio"], .black .checkbox input[type="checkbox"] {
  float: left;
  margin-left: -20px;}
.black .controls > .radio:first-child, .black .controls > .checkbox:first-child {
  padding-top: 5px;}
.black .radio.inline, .black .checkbox.inline {
  display: inline-block;
  padding-top: 5px;
  margin-bottom: 0;
  vertical-align: middle;}
.black .radio.inline + .radio.inline, .black .checkbox.inline + .checkbox.inline {
  margin-left: 10px;}
.black .input-mini {
  width: 60px;}
.black .input-small {
  width: 90px;}
.black .input-medium {
  width: 150px;}
.black .input-large {
  width: 210px;}
.black .input-xlarge {
  width: 270px;}
.black .input-xxlarge {
  width: 530px;}
.black input[class*="span"], .black select[class*="span"], .black textarea[class*="span"], .black .uneditable-input[class*="span"], .black .row-fluid input[class*="span"], .black .row-fluid select[class*="span"], .black .row-fluid textarea[class*="span"], .black .row-fluid .uneditable-input[class*="span"] {
  float: none;
  margin-left: 0;}
.black .input-append input[class*="span"], .black .input-append .uneditable-input[class*="span"], .black .input-prepend input[class*="span"], .black .input-prepend .uneditable-input[class*="span"], .black .row-fluid input[class*="span"], .black .row-fluid select[class*="span"], .black .row-fluid textarea[class*="span"], .black .row-fluid .uneditable-input[class*="span"], .black .row-fluid .input-prepend [class*="span"], .black .row-fluid .input-append [class*="span"] {
  display: inline-block;}
.black input, .black textarea, .black .uneditable-input {
  margin-left: 0;}
.black .controls-row [class*="span"] + [class*="span"] {
  margin-left: 20px;}
.black input.span12, .black textarea.span12, .black .uneditable-input.span12 {
  width: 926px;}

.black input.span11, .black textarea.span11, .black .uneditable-input.span11 {
  width: 846px;}
.black input.span10, .black textarea.span10, .black .uneditable-input.span10 {
  width: 766px;}
.black input.span9, .black textarea.span9, .black .uneditable-input.span9 {
  width: 686px;}
.black input.span8, .black textarea.span8, .black .uneditable-input.span8 {
  width: 606px;}
.black input.span7, .black textarea.span7, .black .uneditable-input.span7 {
  width: 526px;}
.black input.span6, .black textarea.span6, .black .uneditable-input.span6 {
  width: 446px;}
.black input.span5, .black textarea.span5, .black .uneditable-input.span5 {
  width: 366px;}
.black input.span4, .black textarea.span4, .black .uneditable-input.span4 {
  width: 286px;}
.black input.span3, .black textarea.span3, .black .uneditable-input.span3 {
  width: 206px;}
.black input.span2, .black textarea.span2, .black .uneditable-input.span2 {
  width: 126px;}
.black input.span1, .black textarea.span1, .black .uneditable-input.span1 {
  width: 46px;}
.black .controls-row {
  *zoom: 1;}
.black .controls-row:before, .black .controls-row:after {
  display: table;
  content: "";
  line-height: 0;}
.black .controls-row:after {
  clear: both;}
.black .controls-row [class*="span"], .black .row-fluid .controls-row [class*="span"] {
  float: left;}
.black .controls-row .checkbox[class*="span"], .black .controls-row .radio[class*="span"] {
  padding-top: 5px;}
.black input[disabled], .black select[disabled], .black textarea[disabled], .black input[readonly], .black select[readonly], .black textarea[readonly] {
  cursor: not-allowed;
  background-color: #eeeeee;}
.black input[type="radio"][disabled], .black input[type="checkbox"][disabled], .black input[type="radio"][readonly], .black input[type="checkbox"][readonly] {
  background-color: transparent;}
.black .control-group.warning .control-label, .black .control-group.warning .help-block, .black .control-group.warning .help-inline {
  color: #c09853;}
.black .control-group.warning .checkbox, .black .control-group.warning .radio, .black .control-group.warning input, .black .control-group.warning select, .black .control-group.warning textarea {
  color: #c09853;}
.black .control-group.warning input, .black .control-group.warning select, .black .control-group.warning textarea {
  border-color: #c09853;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);}
.black .control-group.warning input:focus, .black .control-group.warning select:focus, .black .control-group.warning textarea:focus {
  border-color: #a47e3c;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #dbc59e;
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #dbc59e;
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #dbc59e;}
.black .control-group.warning .input-prepend .add-on, .black .control-group.warning .input-append .add-on {
  color: #c09853;
  background-color: #fcf8e3;
  border-color: #c09853;}
.black .control-group.error .control-label, .black .control-group.error .help-block, .black .control-group.error .help-inline {
  color: #b94a48;}
.black .control-group.error .checkbox, .black .control-group.error .radio, .black .control-group.error input, .black .control-group.error select, .black .control-group.error textarea {
  color: #b94a48;}
.black .control-group.error input, .black .control-group.error select, .black .control-group.error textarea {
  border-color: #b94a48;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);}
.black .control-group.error input:focus, .black .control-group.error select:focus, .black .control-group.error textarea:focus {
  border-color: #953b39;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #d59392;
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #d59392;
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #d59392;}
.black .control-group.error .input-prepend .add-on, .black .control-group.error .input-append .add-on {
  color: #b94a48;
  background-color: #f2dede;
  border-color: #b94a48;}
.black .control-group.success .control-label, .black .control-group.success .help-block, .black .control-group.success .help-inline {
  color: #468847;}
.black .control-group.success .checkbox, .black .control-group.success .radio, .black .control-group.success input, .black .control-group.success select, .black .control-group.success textarea {
  color: #468847;}
.black .control-group.success input, .black .control-group.success select, .black .control-group.success textarea {
  border-color: #468847;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);}
.black .control-group.success input:focus, .black .control-group.success select:focus, .black .control-group.success textarea:focus {
  border-color: #356635;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #7aba7b;
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #7aba7b;
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #7aba7b;}
.black .control-group.success .input-prepend .add-on, .black .control-group.success .input-append .add-on {
  color: #468847;
  background-color: #dff0d8;
  border-color: #468847;}
.black .control-group.info .control-label, .black .control-group.info .help-block, .black .control-group.info .help-inline {
  color: #3a87ad;}
.black .control-group.info .checkbox, .black .control-group.info .radio, .black .control-group.info input, .black .control-group.info select, .black .control-group.info textarea {
  color: #3a87ad;}
.black .control-group.info input, .black .control-group.info select, .black .control-group.info textarea {
  border-color: #3a87ad;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075);}
.black .control-group.info input:focus, .black .control-group.info select:focus, .black .control-group.info textarea:focus {
  border-color: #2d6987;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #7ab5d3;
  -moz-box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #7ab5d3;
  box-shadow: inset 0 1px 1px rgba(0, .black 0, .black 0, .black 0.075), .black 0 0 6px #7ab5d3;}
.black .control-group.info .input-prepend .add-on, .black .control-group.info .input-append .add-on {
  color: #3a87ad;
  background-color: #d9edf7;
  border-color: #3a87ad;}
.black input:focus:invalid, .black textarea:focus:invalid, .black select:focus:invalid {
  color: #b94a48;
  border-color: #ee5f5b;}
.black input:focus:invalid:focus, .black textarea:focus:invalid:focus, .black select:focus:invalid:focus {
  border-color: #e9322d;
  -webkit-box-shadow: 0 0 6px #f8b9b7;
  -moz-box-shadow: 0 0 6px #f8b9b7;
  box-shadow: 0 0 6px #f8b9b7;}
.black .form-actions {
  padding: 19px 20px 20px;
  margin-top: 20px;
  margin-bottom: 20px;
  background-color: #f5f5f5;
  border-top: 1px solid #e5e5e5;
  *zoom: 1;}
.black .form-actions:before, .black .form-actions:after {
  display: table;
  content: "";
  line-height: 0;}
.black .form-actions:after {
  clear: both;}
.black .help-block, .black .help-inline {
  color: #ffffff;}
.black .help-block {
  display: block;
  margin-bottom: 10px;}
.black .help-inline {
  display: inline-block;
  *display: inline;
  /* IE7 inline-block hack */

  *zoom: 1;
  vertical-align: middle;
  padding-left: 5px;}
.black .input-append, .black .input-prepend {
  margin-bottom: 5px;
  font-size: 0;
  white-space: nowrap;}
.black .input-append input, .black .input-prepend input, .black .input-append select, .black .input-prepend select, .black .input-append .uneditable-input, .black .input-prepend .uneditable-input, .black .input-append .dropdown-menu, .black .input-prepend .dropdown-menu {
  font-size: 14px;}
.black .input-append input, .black .input-prepend input, .black .input-append select, .black .input-prepend select, .black .input-append .uneditable-input, .black .input-prepend .uneditable-input {
  position: relative;
  margin-bottom: 0;
  *margin-left: 0;
  vertical-align: top;
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;}
.black .input-append input:focus, .black .input-prepend input:focus, .black .input-append select:focus, .black .input-prepend select:focus, .black .input-append .uneditable-input:focus, .black .input-prepend .uneditable-input:focus {
  z-index: 2;}
.black .input-append .add-on, .black .input-prepend .add-on {
  display: inline-block;
  width: auto;
  height: 20px;
  min-width: 16px;
  padding: 4px 5px;
  font-size: 14px;
  font-weight: normal;
  line-height: 20px;
  text-align: center;
  text-shadow: 0 1px 0 #ffffff;
  background-color: #eeeeee;
  border: 1px solid #ccc;}
.black .input-append .add-on, .black .input-prepend .add-on, .black .input-append .btn, .black .input-prepend .btn, .black .input-append .btn-group > .dropdown-toggle, .black .input-prepend .btn-group > .dropdown-toggle {
  vertical-align: top;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0;}
.black .input-append .active, .black .input-prepend .active {
  background-color: #a9dba9;
  border-color: #46a546;}
.black .input-prepend .add-on, .black .input-prepend .btn {
  margin-right: -1px;}
.black .input-prepend .add-on:first-child, .black .input-prepend .btn:first-child {
  -webkit-border-radius: 4px 0 0 4px;
  -moz-border-radius: 4px 0 0 4px;
  border-radius: 4px 0 0 4px;}
.black .input-append input, .black .input-append select, .black .input-append .uneditable-input {
  -webkit-border-radius: 4px 0 0 4px;
  -moz-border-radius: 4px 0 0 4px;
  border-radius: 4px 0 0 4px;}
.black .input-append input + .btn-group .btn:last-child, .black .input-append select + .btn-group .btn:last-child, .black .input-append .uneditable-input + .btn-group .btn:last-child {
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;}
.black .input-append .add-on, .black .input-append .btn, .black .input-append .btn-group {
  margin-left: -1px;}
.black .input-append .add-on:last-child, .black .input-append .btn:last-child, .black .input-append .btn-group:last-child > .dropdown-toggle {
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;}
.black .input-prepend.input-append input, .black .input-prepend.input-append select, .black .input-prepend.input-append .uneditable-input {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0;}
.black .input-prepend.input-append input + .btn-group .btn, .black .input-prepend.input-append select + .btn-group .btn, .black .input-prepend.input-append .uneditable-input + .btn-group .btn {
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;}
.black .input-prepend.input-append .add-on:first-child, .black .input-prepend.input-append .btn:first-child {
  margin-right: -1px;
  -webkit-border-radius: 4px 0 0 4px;
  -moz-border-radius: 4px 0 0 4px;
  border-radius: 4px 0 0 4px;}
.black .input-prepend.input-append .add-on:last-child, .black .input-prepend.input-append .btn:last-child {
  margin-left: -1px;
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;}
.black .input-prepend.input-append .btn-group:first-child {
  margin-left: 0;}
.black input.search-query {
  padding-right: 14px;
  padding-right: 4px \9;
  padding-left: 14px;
  padding-left: 4px \9;
  /* IE7-8 doesn't have border-radius, .black so don't indent the padding */

  margin-bottom: 0;
  -webkit-border-radius: 15px;
  -moz-border-radius: 15px;
  border-radius: 15px;}
.black /* Allow for input prepend/append in search forms */
.form-search .input-append .search-query, .black .form-search .input-prepend .search-query {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0;}
.black .form-search .input-append .search-query {
  -webkit-border-radius: 14px 0 0 14px;
  -moz-border-radius: 14px 0 0 14px;
  border-radius: 14px 0 0 14px;}
.black .form-search .input-append .btn {
  -webkit-border-radius: 0 14px 14px 0;
  -moz-border-radius: 0 14px 14px 0;
  border-radius: 0 14px 14px 0;}
.black .form-search .input-prepend .search-query {
  -webkit-border-radius: 0 14px 14px 0;
  -moz-border-radius: 0 14px 14px 0;
  border-radius: 0 14px 14px 0;}
.black .form-search .input-prepend .btn {
  -webkit-border-radius: 14px 0 0 14px;
  -moz-border-radius: 14px 0 0 14px;
  border-radius: 14px 0 0 14px;}
.black .form-search input, .black .form-inline input, .black .form-horizontal input, .black .form-search textarea, .black .form-inline textarea, .black .form-horizontal textarea, .black .form-search select, .black .form-inline select, .black .form-horizontal select, .black .form-search .help-inline, .black .form-inline .help-inline, .black .form-horizontal .help-inline, .black .form-search .uneditable-input, .black .form-inline .uneditable-input, .black .form-horizontal .uneditable-input, .black .form-search .input-prepend, .black .form-inline .input-prepend, .black .form-horizontal .input-prepend, .black .form-search .input-append, .black .form-inline .input-append, .black .form-horizontal .input-append {
  display: inline-block;
  *display: inline;
  /* IE7 inline-block hack */

  *zoom: 1;
  margin-bottom: 0;
  vertical-align: middle;}
.black .form-search .hide, .black .form-inline .hide, .black .form-horizontal .hide {
  display: none;}
.black .form-search label, .black .form-inline label, .black .form-search .btn-group, .black .form-inline .btn-group {
  display: inline-block;}
.black .form-search .input-append, .black .form-inline .input-append, .black .form-search .input-prepend, .black .form-inline .input-prepend {
  margin-bottom: 0;}
.black .form-search .radio, .black .form-search .checkbox, .black .form-inline .radio, .black .form-inline .checkbox {
  padding-left: 0;
  margin-bottom: 0;
  vertical-align: middle;}
.black .form-search .radio input[type="radio"], .black .form-search .checkbox input[type="checkbox"], .black .form-inline .radio input[type="radio"], .black .form-inline .checkbox input[type="checkbox"] {
  float: left;
  margin-right: 3px;
  margin-left: 0;}
.black .control-group {
  margin-bottom: 10px;}
.black legend + .control-group {
  margin-top: 20px;
  -webkit-margin-top-collapse: separate;}
.black .form-horizontal .control-group {
  margin-bottom: 20px;
  *zoom: 1;}
.black .form-horizontal .control-group:before, .black .form-horizontal .control-group:after {
  display: table;
  content: "";
  line-height: 0;}
.black .form-horizontal .control-group:after {
  clear: both;}
.black .form-horizontal .control-label {
  float: left;
  width: 160px;
  padding-top: 5px;
  text-align: right;}
.black .form-horizontal .controls {
  *display: inline-block;
  *padding-left: 20px;
  margin-left: 180px;
  *margin-left: 0;}
.black .form-horizontal .controls:first-child {
  *padding-left: 180px;}
.black .form-horizontal .help-block {
  margin-bottom: 0;}
.black .form-horizontal input + .help-block, .black .form-horizontal select + .help-block, .black .form-horizontal textarea + .help-block, .black .form-horizontal .uneditable-input + .help-block, .black .form-horizontal .input-prepend + .help-block, .black .form-horizontal .input-append + .help-block {
  margin-top: 10px;}
.black .form-horizontal .form-actions {
  padding-left: 180px;}
.black 
 </style>
 <? 

$template_file = module_templates( $config['module'], 'default');
 

  ?><div class="black"><?
if(isset($template_file) and is_file($template_file) != false){
  include($template_file);
}
 
 
 
 ?></div>

