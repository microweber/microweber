;(function (){

    var Uploader = function( options ) {
        //var upload = function( url, data, callback, type ) {
        options = options || {};
        options.accept = options.accept || options.filetypes || options.type;
        var defaults = {
            multiple: false,
            progress: null,
            element: null,
            url: options.url || (mw.settings.site_url + 'plupload'),
            urlParams: {},
            on: {},
            autostart: true,
            async: true,
            accept: '*',
            chunkSize: 1500000,
        };

        var normalizeAccept = function (type) {

            type = (type || '').trim().toLowerCase();
            if(!type) return '*';
            if (type === 'image' || type === 'images') return '.png,.gif,.jpg,.jpeg,.bmp,.svg,.ico,.avif,.webp';
            if (type === 'video' || type === 'videos') return '.mp4,.webm,.ogg,.wma,.mov,.wmv';
            if (type === 'document' || type === 'documents') return '.doc,.docx,.log,.pdf,.msg,.odt,.pages,' +
                '.rtf,.tex,.txt,.wpd,.wps,.pps,.ppt,.pptx,.xml,.htm,.html,.xlr,.xls,.xlsx';

            return '*';
        };

        var scope = this;
        this.settings = $.extend({}, defaults, options);

        this.settings.accept = normalizeAccept(this.settings.accept);


        var _e = {};

        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };

        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };


        this.getUrl = function () {
            var params = this.urlParams();
            var empty = mw.tools.isEmptyObject(params);
            return this.url() + (empty ? '' : ('?' + $.param(params)));
        };

        this.urlParam = function (param, value) {
            if(typeof value === 'undefined') {
                return this.settings.urlParams[param];
            }
            this.settings.urlParams[param] = value;
        };

        this.urlParams = function (params) {
            if(!params) {
                return this.settings.urlParams;
            }
            this.settings.urlParams = params;
        };

        this.url = function (url) {
            if(!url) {
                return this.settings.url;
            }
            this.settings.url = url;
        };

        this.create = function () {
            this.input = document.createElement('input');
            this.input.multiple = this.settings.multiple;
            this.input.accept = this.settings.accept;
            this.input.type = 'file';
            this.input.className = 'mw-uploader-input';
            this.input.oninput = function () {
                scope.addFiles(this.files);
            };
        };

        this.files = [];
        this._uploading = false;
        this.uploading = function (state) {
            if(typeof state === 'undefined') {
                return this._uploading;
            }
            this._uploading = state;
        };

        this._validateAccept = this.settings.accept
            .toLowerCase()
            .replace(/\*/g, '')
            .replace(/ /g, '')
            .split(',')
            .filter(function (item) {
                return !!item;
            });
        this.validate = function (file) {
            if(!file) return false;
            var ext = '.' + file.name.split('.').pop().toLowerCase();
            if (this._validateAccept.length === 0) {
                return true;
            }
            for (var i = 0; i < this._validateAccept.length; i++) {
                var item =  this._validateAccept[i];
                if(item === ext) {
                    return true;
                }
                else if(file.type.indexOf(item) === 0) {
                    return true;
                }
            }
            return false;

        };

        this.addFile = function (file) {
            if(this.validate(file)) {
                if(!this.files.length || this.settings.multiple){
                    this.files.push(file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                    $(scope).trigger('FileAdded', file);
                } else {
                    this.files = [file];
                    $(scope).trigger('FileAdded', file);
                    if(this.settings.on.fileAdded) {
                        this.settings.on.fileAdded(file);
                    }
                }
            }
        };

        this.addFiles = function (files) {

            if(!files || !files.length) return;

            if(!this.settings.multiple) {
                files = [files[0]];
            }
            if (files && files.length) {
                for (var i = 0; i < files.length; i++) {
                    scope.addFile(files[i]);
                }
                if(this.settings.on.filesAdded) {
                    if(this.settings.on.filesAdded(files) === false) {
                        return;
                    }
                }
                $(scope).trigger('FilesAdded', [files]);
                this.dispatch('filesAdded', [files])
                if(this.settings.autostart) {
                    this.uploadFiles();
                }
            }
        };

        this.remove = function () {
            if(this.input.parentNode) {
                this.input.parentNode.removeChild(this.input);
            }
        }

        this.build = function () {
            if(this.settings.element) {
                this.$element = $(this.settings.element);
                this.element = this.$element[0];

                if(this.element) {
                    this.$element/*.empty()*/.append(this.input);
                    var pos = getComputedStyle(this.element).position;
                    if(pos === 'static') {
                        this.element.style.position = 'relative';
                    }
                    this.element.style.overflow = 'hidden';
                }
            }
        };

        this.show = function () {
            this.$element.show();
        };

        this.hide = function () {
            this.$element.hide();
        };

        this.initDropZone = function () {
            if (!!this.settings.dropZone) {
                mw.$(this.settings.dropZone).each(function () {
                    $(this)
                    .on('dragleave', function (e) {
                        $(this).removeClass("mw-dropzone--drag-over");
                    })
                    .on('dragover', function (e) {
                        $(this).addClass("mw-dropzone--drag-over");
                        e.preventDefault();
                    }).on('drop', function (e) {
                        $(this).removeClass("mw-dropzone--drag-over");
                        var dt = e.dataTransfer || e.originalEvent.dataTransfer;
                        e.preventDefault();
                        if (dt && dt.items) {
                            var files = [];
                            for (var i = 0; i < dt.items.length; i++) {
                                if (dt.items[i].kind === 'file') {
                                    var file = dt.items[i].getAsFile();
                                    files.push(file);
                                }
                            }
                            scope.addFiles(files);
                        } else  if (dt && dt.files)  {
                            scope.addFiles(dt.files);
                        }
                    });
                });
            }
        };


        this.init = function() {
            this.create();
            this.build();
            this.initDropZone();
        };

        this.init();

        this.removeFile = function (file) {
            var i = this.files.indexOf(file);
            if (i > -1) {
                this.files.splice(i, 1);
            }
        };

        var beforeFileUpload = function () {
            if(scope.settings.on.beforeFileUpload) {
                return scope.settings.on.beforeFileUpload(this);
            } else {
                return new Promise(function (resolve){
                    resolve();
                });
            }
        };

        this.uploadFile = function (file, done, chunks, _all, _i) {
            return new Promise(function (resolve, reject) {
                beforeFileUpload().then(function (){
                        chunks = chunks || scope.sliceFile(file);
                        _all = _all || chunks.length;
                        _i = _i || 0;
                        var chunk = chunks.shift();
                        var data = {
                            name: file.name,
                            chunk: _i,
                            chunks: _all,
                            file: chunk,
                        };
                        _i++;
                        $(scope).trigger('uploadStart', [data]);

                        scope.upload(data, function (res) {
                            var dataProgress;
                            if(chunks.length) {
                                scope.uploadFile(file, done, chunks, _all, _i).then(function (){
                                    if (done) {
                                        done.call(file, res);
                                    }
                                    resolve(file);
                                }, function (xhr){
                                    if(scope.settings.on.fileUploadError) {
                                        scope.settings.on.fileUploadError(xhr);
                                    }
                                });
                                dataProgress = {
                                    percent: ((100 * _i) / _all).toFixed()
                                };
                                $(scope).trigger('progress', [dataProgress, res]);
                                if(scope.settings.on.progress) {
                                    scope.settings.on.progress(dataProgress, res);
                                }

                            } else {
                                dataProgress = {
                                    percent: '100'
                                };
                                $(scope).trigger('progress', [dataProgress, res]);
                                if(scope.settings.on.progress) {
                                    scope.settings.on.progress(dataProgress, res);
                                }
                                $(scope).trigger('FileUploaded', [res]);
scope.dispatch('fileUploaded', res);
                                if(scope.settings.on.fileUploaded) {
                                    scope.settings.on.fileUploaded(res);
                                }
                                if (done) {
                                    done.call(file, res);
                                }
                                resolve(file);
                            }
                        }, function (req) {

                            if(req && req.status === 400){
                                if(typeof mw.cookie !== 'undefined'){
                                    mw.cookie.delete('XSRF-TOKEN');
                                }
                            }

                            var msg = false;

                            if (req.responseJSON && req.responseJSON.error && req.responseJSON.error.message) {
                                msg = req.responseJSON.error.message;
                            } else if (req.responseJSON && req.responseJSON.error && req.responseJSON.message) {
                                msg = req.responseJSON.message;
                            }

                            if (msg) {
                                mw.notification.warning(msg, 10000);
                            }
                            scope.removeFile(file);
                            reject(req);
                        });
                    });
                });
        };

        this.sliceFile = function(file) {
            var byteIndex = 0;
            var chunks = [];
            var chunksAmount = file.size <= this.settings.chunkSize ? 1 : ((file.size / this.settings.chunkSize) >> 0) + 1;

            for (var i = 0; i < chunksAmount; i ++) {
                var byteEnd = Math.ceil((file.size / chunksAmount) * (i + 1));
                chunks.push(file.slice(byteIndex, byteEnd));
                byteIndex += (byteEnd - byteIndex);
            }

            return chunks;
        };

        this.uploadFiles = function () {
            if (this.settings.async) {
                 if (this.files.length) {
                    this.uploading(true);
                    var file = this.files[0]
                    scope.uploadFile(file)
                        .then(function (){
                        scope.files.shift();
                        scope.uploadFiles();
                    }, function (xhr){
                            scope.removeFile(file);
                            if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr)
                            }
                        });

                } else {
                    this.uploading(false);
                    scope.input.value = '';
                    if(scope.settings.on.filesUploaded) {
                        scope.settings.on.filesUploaded();
                    }
                    this.dispatch('filesUploaded')
                    $(scope).trigger('FilesUploaded');

                }
            } else {
                var count = 0;
                var all = this.files.length;
                this.uploading(true);
                this.files.forEach(function (file) {
                    scope.uploadFile(file)
                        .then(function (file){
                            count++;
                            scope.uploading(false);
                            if(all === count) {
                                scope.input.value = '';
                                if(scope.settings.on.filesUploaded) {
                                    scope.settings.on.filesUploaded();
                                }
                                $(scope).trigger('FilesUploaded');
                            }
                        }, function (xhr){
                            if(scope.settings.on.fileUploadError) {
                                scope.settings.on.fileUploadError(xhr)
                            }
                        });
                });
            }
        };


        this.upload = function (data, done, onFail) {
            if (!this.settings.url) {
                return;
            }
            var pdata = new FormData();
            $.each(data, function (key, val) {
                pdata.append(key, val);
            });
            if(scope.settings.on.uploadStart) {
                if (scope.settings.on.uploadStart(pdata) === false) {
                    return;
                }
            }


            var xhrOptions = {
                url: this.getUrl(),
                type: 'post',
                processData: false,
                contentType: false,
                data: pdata,
                success: function (data, statusText, xhrReq) {

                    if(xhrReq.status === 200) {
                        if (data && (data.form_data_required || data.form_data_module)) {
                            mw.extradataForm(xhrOptions, data, jQuery.ajax);
                        }
                        else {
                            scope.removeFile(data.file);
                            if(done) {
                                done.call(data, data);
                            }
                        }
                    }

                },
                error:  function(  xhrReq, edata, statusText ) {
                    scope.removeFile(data.file);
                    if (onFail) {
                        onFail.call(xhrReq, xhrReq);
                    }
                },
                dataType: 'json',
                xhr: function () {
                    var xhr = new XMLHttpRequest();

                    xhr.upload.addEventListener('progress', function (event) {
                        if (event.lengthComputable) {
                            var percent = (event.loaded / event.total) * 100;
                            if(scope.settings.on.progressNative) {
                                scope.settings.on.progressNative(percent, event);
                            }
                            $(scope).trigger('progressNative', [percent, event]);
                        }
                    });

                    return xhr;
                }
            };

            // var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");
            // if (typeof tokenFromCookie !== 'undefined') {
            //     $.ajaxSetup({
            //         headers: {
            //             'X-XSRF-TOKEN': tokenFromCookie
            //         }
            //     });
            // }
            //



            return jQuery.ajax(xhrOptions);
        };
    };

    if(!mw.uploadGlobalSettings) {
        mw.uploadGlobalSettings = {};
    }

    mw.upload = function (options) {
        if (!options) {
            options = {};
        }

        return new Uploader($.extend(true, {}, mw.uploadGlobalSettings, options));
    };

    mw.dropZone = (target, options = {}) => {
        if (target.dropZone) {
            options = target;
            target = target.dropZone;
        }
        options.dropZone = target
        jQuery(target).each((i, node) => node.classList.add('mw-dropzone'));
        const up = mw.upload(options);
        up.on('filesAdded', function (e) {
            mw.spinner({element: target, decorate: true}).show()
        });
        up.on('filesUploaded', function (e) {
            mw.spinner({element: target}).remove()
        });
        return up;
    }


})();
