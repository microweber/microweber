/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/* AIRAliases.js - Revision: 0.15 */

// Copyright (c) 2007. Adobe Systems Incorporated.
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are met:
//
//   * Redistributions of source code must retain the above copyright notice,
//     this list of conditions and the following disclaimer.
//   * Redistributions in binary form must reproduce the above copyright notice,
//     this list of conditions and the following disclaimer in the documentation
//     and/or other materials provided with the distribution.
//   * Neither the name of Adobe Systems Incorporated nor the names of its
//     contributors may be used to endorse or promote products derived from this
//     software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
// AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
// LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
// CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
// SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
// INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
// CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.


var air;
if (window.runtime) 
{
    if (!air) air = {};
    // functions
    air.trace = window.runtime.trace;
    air.navigateToURL = window.runtime.flash.net.navigateToURL;
    air.sendToURL = window.runtime.flash.net.sendToURL;


    // file
    air.File = window.runtime.flash.filesystem.File;
    air.FileStream = window.runtime.flash.filesystem.FileStream;
    air.FileMode = window.runtime.flash.filesystem.FileMode;

    // events
    air.AsyncErrorEvent = window.runtime.flash.events.AsyncErrorEvent;
    air.DataEvent = window.runtime.flash.events.DataEvent;
    air.DRMAuthenticateEvent = window.runtime.flash.events.DRMAuthenticateEvent;
    air.DRMStatusEvent = window.runtime.flash.events.DRMStatusEvent;
    air.Event = window.runtime.flash.events.Event;
    air.EventDispatcher = window.runtime.flash.events.EventDispatcher;
    air.FileListEvent = window.runtime.flash.events.FileListEvent;
    air.HTTPStatusEvent = window.runtime.flash.events.HTTPStatusEvent;
    air.IOErrorEvent = window.runtime.flash.events.IOErrorEvent;
    air.InvokeEvent = window.runtime.flash.events.InvokeEvent;
    air.NetStatusEvent = window.runtime.flash.events.NetStatusEvent;
    air.OutputProgressEvent = window.runtime.flash.events.OutputProgressEvent;
    air.ProgressEvent = window.runtime.flash.events.ProgressEvent;
    air.SecurityErrorEvent = window.runtime.flash.events.SecurityErrorEvent;
    air.StatusEvent = window.runtime.flash.events.StatusEvent;
    air.TimerEvent = window.runtime.flash.events.TimerEvent;
    air.NativeDragEvent = window.runtime.flash.events.NativeDragEvent;
    air.ActivityEvent = window.runtime.flash.events.ActivityEvent;
    air.KeyboardEvent = window.runtime.flash.events.KeyboardEvent;    
    air.MouseEvent = window.runtime.flash.events.MouseEvent;    
    
    // native window
    air.NativeWindow = window.runtime.flash.display.NativeWindow;
    air.NativeWindowDisplayState = window.runtime.flash.display.NativeWindowDisplayState;
    air.NativeWindowInitOptions = window.runtime.flash.display.NativeWindowInitOptions;
    air.NativeWindowSystemChrome = window.runtime.flash.display.NativeWindowSystemChrome;
    air.NativeWindowResize = window.runtime.flash.display.NativeWindowResize;
    air.NativeWindowType = window.runtime.flash.display.NativeWindowType;

    air.NativeWindowBoundsEvent = window.runtime.flash.events.NativeWindowBoundsEvent;
    air.NativeWindowDisplayStateEvent = window.runtime.flash.events.NativeWindowDisplayStateEvent;

    // geom
    air.Point = window.runtime.flash.geom.Point;
    air.Rectangle = window.runtime.flash.geom.Rectangle;
    air.Matrix = window.runtime.flash.geom.Matrix;

    // net
    air.FileFilter = window.runtime.flash.net.FileFilter;
    
    air.LocalConnection = window.runtime.flash.net.LocalConnection;
    air.NetConnection = window.runtime.flash.net.NetConnection;

    air.URLLoader = window.runtime.flash.net.URLLoader;
    air.URLLoaderDataFormat = window.runtime.flash.net.URLLoaderDataFormat;
    air.URLRequest = window.runtime.flash.net.URLRequest;
    air.URLRequestDefaults = window.runtime.flash.net.URLRequestDefaults;
    air.URLRequestHeader = window.runtime.flash.net.URLRequestHeader;
    air.URLRequestMethod = window.runtime.flash.net.URLRequestMethod;
    air.URLStream = window.runtime.flash.net.URLStream;
    air.URLVariables = window.runtime.flash.net.URLVariables;
    air.Socket = window.runtime.flash.net.Socket;
    air.XMLSocket = window.runtime.flash.net.XMLSocket;

    air.Responder = window.runtime.flash.net.Responder;
    air.ObjectEncoding = window.runtime.flash.net.ObjectEncoding;

    air.NetStream = window.runtime.flash.net.NetStream;
    air.SharedObject = window.runtime.flash.net.SharedObject;
    air.SharedObjectFlushStatus = window.runtime.flash.net.SharedObjectFlushStatus;

    // system
    air.Capabilities = window.runtime.flash.system.Capabilities;
    air.System = window.runtime.flash.system.System;
    air.Security = window.runtime.flash.system.Security;
    air.Updater = window.runtime.flash.desktop.Updater;

    air.LoaderContext = window.runtime.flash.system.LoaderContext;
    air.ApplicationDomain = window.runtime.flash.system.ApplicationDomain;	


    // desktop
    air.Clipboard = window.runtime.flash.desktop.Clipboard;
    air.ClipboardFormats = window.runtime.flash.desktop.ClipboardFormats;
    air.ClipboardTransferMode = window.runtime.flash.desktop.ClipboardTransferMode;

    air.NativeDragManager = window.runtime.flash.desktop.NativeDragManager;
    air.NativeDragOptions = window.runtime.flash.desktop.NativeDragOptions;
    air.NativeDragActions = window.runtime.flash.desktop.NativeDragActions;

    air.Icon = window.runtime.flash.desktop.Icon;
    air.DockIcon = window.runtime.flash.desktop.DockIcon;
    air.InteractiveIcon = window.runtime.flash.desktop.InteractiveIcon;
    air.NotificationType = window.runtime.flash.desktop.NotificationType;
    air.SystemTrayIcon = window.runtime.flash.desktop.SystemTrayIcon;

    air.NativeApplication = window.runtime.flash.desktop.NativeApplication;

    // display
    air.NativeMenu = window.runtime.flash.display.NativeMenu;
    air.NativeMenuItem = window.runtime.flash.display.NativeMenuItem;
    air.Screen = window.runtime.flash.display.Screen;
    
    air.Loader  = window.runtime.flash.display.Loader;
    air.Bitmap = window.runtime.flash.display.Bitmap;
    air.BitmapData = window.runtime.flash.display.BitmapData;

    // ui
    air.Keyboard = window.runtime.flash.ui.Keyboard;
    air.KeyEquivalent = window.runtime.flash.ui.KeyEquivalent;
    air.Mouse = window.runtime.flash.ui.Mouse;


    // utils
    air.ByteArray = window.runtime.flash.utils.ByteArray;
    air.CompressionAlgorithm = window.runtime.flash.utils.CompressionAlgorithm;
    air.Endian = window.runtime.flash.utils.Endian;
    air.Timer = window.runtime.flash.utils.Timer;
    air.XMLSignatureValidator = window.runtime.flash.security.XMLSignatureValidator;

    air.HTMLLoader = window.runtime.flash.html.HTMLLoader;    

    // media
    air.ID3Info = window.runtime.flash.media.ID3Info;
    air.Sound = window.runtime.flash.media.Sound;
    air.SoundChannel = window.runtime.flash.media.SoundChannel;
    air.SoundLoaderContext = window.runtime.flash.media.SoundLoaderContext;
    air.SoundMixer = window.runtime.flash.media.SoundMixer;
    air.SoundTransform = window.runtime.flash.media.SoundTransform;
    air.Microphone = window.runtime.flash.media.Microphone;
    air.Video = window.runtime.flash.media.Video;
    air.Camera = window.runtime.flash.media.Camera;

    // data
    air.EncryptedLocalStore = window.runtime.flash.data.EncryptedLocalStore;
    air.SQLCollationType = window.runtime.flash.data.SQLCollationType;
    air.SQLColumnNameStyle = window.runtime.flash.data.SQLColumnNameStyle;
    air.SQLColumnSchema = window.runtime.flash.data.SQLColumnSchema;
    air.SQLConnection = window.runtime.flash.data.SQLConnection;
    air.SQLError = window.runtime.flash.errors.SQLError;
    air.SQLErrorEvent = window.runtime.flash.events.SQLErrorEvent;
    air.SQLErrorOperation = window.runtime.flash.errors.SQLErrorOperation;
    air.SQLEvent = window.runtime.flash.events.SQLEvent;
    air.SQLIndexSchema = window.runtime.flash.data.SQLIndexSchema;
    air.SQLMode = window.runtime.flash.data.SQLMode;
    air.SQLResult = window.runtime.flash.data.SQLResult;
    air.SQLSchema = window.runtime.flash.data.SQLSchema;
    air.SQLSchemaResult = window.runtime.flash.data.SQLSchemaResult;
    air.SQLStatement = window.runtime.flash.data.SQLStatement;
    air.SQLTableSchema = window.runtime.flash.data.SQLTableSchema;
    air.SQLTransactionLockType = window.runtime.flash.data.SQLTransactionLockType;
    air.SQLTriggerSchema = window.runtime.flash.data.SQLTriggerSchema;
    air.SQLUpdateEvent = window.runtime.flash.events.SQLUpdateEvent;
    air.SQLViewSchema = window.runtime.flash.data.SQLViewSchema;

    // service monitoring framework
    air.__defineGetter__("ServiceMonitor", function() { return window.runtime.air.net.ServiceMonitor; })
    air.__defineGetter__("SocketMonitor", function() { return window.runtime.air.net.SocketMonitor; })
    air.__defineGetter__("URLMonitor", function() { return window.runtime.air.net.URLMonitor; })
}