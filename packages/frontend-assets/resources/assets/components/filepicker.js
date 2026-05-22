var fileUploadProgress = function (fileName, progress, target) {
    if (!target) {
        target = document.body;
    }

    var node = target.querySelector('[data-file="' + fileName + '"]');
    if (!node) {
        node = document.createElement("div");
        node.dataset.file = fileName;
        target.appendChild(node);
    }

    mw.progress({ element: node, action: fileName }).set(progress);
};

const toDataURL = async (url, callback) => {
    if (typeof url !== "string") {
        url = URL.createObjectURL(url);
    }

    return new Promise((resolve) => {
        const img = new Image();

        img.crossOrigin = "anonymous";

        const size = 600;

        let maxWidth = size,
            maxHeight = size;

        img.onload = function () {
            const originalWidth = img.width;
            const originalHeight = img.height;

            const aspectRatio = originalWidth / originalHeight;

            let newWidth, newHeight;

            if (originalWidth > maxWidth || originalHeight > maxHeight) {
                if (originalWidth / maxWidth > originalHeight / maxHeight) {
                    newWidth = maxWidth;
                    newHeight = maxWidth / aspectRatio;
                } else {
                    newHeight = maxHeight;
                    newWidth = maxHeight * aspectRatio;
                }
            } else {
                newWidth = originalWidth;
                newHeight = originalHeight;
            }

            const canvas = document.createElement("canvas");
            canvas.width = newWidth;
            canvas.height = newHeight;

            const ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, newWidth, newHeight);

            const base64String = canvas.toDataURL();

            if (typeof callback === "function") {
                callback(base64String);
            }

            resolve(base64String);
        };

        img.src = url;
    });
};

mw.filePickerDialog = (
    conf = { pickerOptions: {}, dialogOptions: {} },
    callback
) => {
    if (typeof conf === "function") {
        callback = conf;
        conf = { pickerOptions: {}, dialogOptions: {} };
    }
    if (!conf.pickerOptions) {
        conf.pickerOptions = {};
    }

    if (!conf.dialogOptions) {
        conf.dialogOptions = {};
    }

    const { pickerOptions, dialogOptions } = conf;
    let dialog, picker;

    let filePickerDefaults = {
        type: "images",
        label: false,
        autoSelect: false,
        footer: true,
        _frameMaxHeight: true,
    };

    let filepickerSettings = Object.assign(
        {},
        filePickerDefaults,
        pickerOptions
    );
    let url;

    picker = new mw.filePicker({
        ...filepickerSettings,
        onResult: function (res) {
            url = res.src ? res.src : res;
            if (!url) {
                dialog.remove();
                return;
            }
            if (typeof url === "string" || url instanceof String) {
                url = url.toString();
            }

            if (Array.isArray(url)) {
                if (url.length === 1) {
                    url = url[0];
                }
            }

            result = url;
            dialog.remove();
        },
    });

    var dialogDefaults = {
        content: picker.root,
        title: mw.lang("Select image"),
        footer: false,
        width: 860,
    };

    var dialogSettings = Object.assign({}, dialogDefaults, dialogOptions);

    let resolver,
        result = null;
    const promise = new Promise((resolve) => {
        resolver = resolve;
    });

    dialog = mw.top().dialog(dialogSettings);
    picker.$cancel.on("click", function () {
        dialog.remove();
    });
    mw.top()
        .$(dialog)
        .on("Remove", function () {
            resolver(url);
            if (typeof callback === "function") {
                callback.call(undefined, url);
            }
        });

    return {
        picker,
        dialog,
        promise: () => promise,
    };
};

mw.filePicker = function (options) {
    options = options || {};
    var scope = this;
    var defaults = {
        // audit-test 2026-05-07 Image Picker baseline (TICKET-XX): Media library
        // moved to first position. Most authors pick from existing assets rather
        // than upload new ones, so the most-used path now opens the picker
        // immediately. desktop/server follow as upload paths; ai/url last as
        // generation/external paths.
        //
        // task-2026-05-17-2a7bcf / AI-739 — removed the legacy
        // "Uploaded" (server) tab. Per designer dispatch: redundant
        // with "Media library" — both surfaced uploaded files; the
        // former was the legacy FileManager UI, the latter is the
        // canonical iframe. 5 tabs → 4 tabs. Skeleton-loader +
        // "No files yet" empty state for the Media library tab
        // tracked as AI-739a (require larger Vue component work).
        components: [
            { type: "library", label: mw.lang("Media library") },
            { type: "desktop", label: mw.lang("My computer") },
            { type: "ai", label: mw.lang("Enter prompt") },
            { type: "url", label: mw.lang("URL") },
        ],
        nav: "tabs", // 'tabs | 'dropdown',
        hideHeader: false,
        dropDownTargetMode: "self", // 'self', 'dialog'
        element: null,
        //   type: null, // filetypes to accept, ex. images, videos, audios, documents
        footer: true,
        /* task-2026-05-17-46d1ac AI-741 — verb-led footer label */
        okLabel: mw.lang("Insert image"),
        cancelLabel: mw.lang("Cancel"),
        uploaderType: "big", // 'big' | 'small'
        confirm: function (data) {},
        cancel: function () {},
        label: mw.lang("Media"),
        autoSelect: true, // depending on the component
        boxed: false,
        multiple: false,
        // AI-117 / TICKET-CK (cycle-108 2026-05-09): first-use UX.
        // When the Media Library is empty for a fresh install, the
        // default "library" tab shows an empty state — useless to a
        // first-time user who has nothing to pick from. Callers can
        // pass `firstActiveComponent: "desktop"` to flip the picker
        // open on the upload tab instead.
        //
        // Recommended wire-up: server-side reads MediaLibrary::count()
        // == 0 and pipes the result to the picker via:
        //   data-mw-media-empty="1" on the trigger element  OR
        //   window.MwMediaLibraryEmpty global               OR
        //   passed explicitly: { firstActiveComponent: "desktop" }
        //
        // Switch back to "library" after first upload via the
        // existing $firstUpload event hook.
        firstActiveComponent: null,
    };

    this.settings = $.extend(true, {}, defaults, options);
    this.settings.type = this.settings.type || this.settings.accept;

    // AI-117 / TICKET-CK (cycle-108): if the caller didn't specify
    // `firstActiveComponent` explicitly, derive it from the global
    // `window.MwMediaLibraryEmpty` flag (server-side rendered).
    // Empty library → desktop (upload) tab; non-empty → library
    // (the historical default — first item in the components list).
    if (
        this.settings.firstActiveComponent === null &&
        typeof window !== "undefined" &&
        window.MwMediaLibraryEmpty === true
    ) {
        this.settings.firstActiveComponent = "desktop";
    }

    this.$root = $(
        '<div class="' +
            (this.settings.boxed ? "card mb-3" : "") +
            ' mw-filepicker-root form-control-live-edit-label-wrapper"></div>'
    );
    this.root = this.$root[0];

    $.each(this.settings.components, function (i) {
        this["index"] = i;
    });

    const _okLabel = (val) => {
        val =
            typeof scope.settings.okLabel === "function"
                ? scope.settings.okLabel(val, scope)
                : scope.settings.okLabel;
        if (this.$ok) {
            this.$ok.html(val);
        }
        return val;
    };

    this.components = {
        _$inputWrapper: function (label) {
            var html =
                '<div class="form-control-live-edit-label-wrapper">' +
                /*'<label>' + label + '</label>' +*/
                "</div>";
            return mw.$(html);
        },
        url: function () {
            var $input = $(
                '<input class="form-control-live-edit-input" placeholder="https://example.com/image.jpg">'
            );
            scope.$urlInput = $input;
            var $wrap = this._$inputWrapper(
                scope._getComponentObject("url").label
            );
            $wrap.append($input);
            // task-2026-05-17-0c4aa5 / AI-772 — label renamed
            // "Insert file url" → "Image URL" so the all-caps
            // CSS treatment from `.live-edit-label` reads
            // "IMAGE URL" (3-word label) instead of "INSERT FILE
            // URL" (4 words, far heavier than the input itself).
            // Helper paragraph below input added per dispatch:
            // closes the "what file types are accepted" gap.
            //
            // AI-771-followup (deferred): the AI tab (Enter prompt)
            // has a dead right half on desktop — designer's
            // Option A (single-column collapse) or Option B (2-col
            // with preview placeholder) need a deeper recon of
            // the AI flex-grid + preview wiring than this label
            // polish slice covers. Tracked separately.
            $input.before(
                '<label class="live-edit-label">' +
                    mw.lang("Image URL") +
                    "</label>"
            );
            $input.after(
                '<span class="form-control-live-edit-bottom-effect"></span>'
            );
            $input.after(
                '<small class="form-control-live-edit-url-helper" style="display:block;color:#6b7280;font-size:0.85em;margin-top:6px;">' +
                    mw.lang("Paste a direct link to a JPG, PNG, GIF, or WebP file.") +
                    "</small>"
            );
            // AI-59 / TICKET-VV (cycle-66 2026-05-08): client-side
            // scheme validation. Inline error region is rendered next
            // to the input so feedback is visible without a toast.
            // The actual security guarantee lives server-side
            // (mw.tools.isAllowedFileUrl) — this is UX, not the gate.
            var $err = $(
                '<small class="form-control-live-edit-url-error" data-mw-filepicker-url-error style="display:none;color:#c81e1e;font-size:0.85em;margin-top:4px;"></small>'
            );
            $input.after($err);

            $input.on("input", function () {
                var val = this.value.trim();
                if (val === "") {
                    $err.hide().text("");
                    scope.setSectionValue(null, false);
                    return;
                }
                // AI-59 / TICKET-VV (cycle-66): reject non-http(s)
                // schemes BEFORE the value is committed. Closes the
                // local-XSS surface where someone pastes
                // `javascript:alert(1)` and the picker happily wires
                // it into an <img> / <a> attribute.
                var allowed = mw.tools && typeof mw.tools.isAllowedFileUrl === "function"
                    ? mw.tools.isAllowedFileUrl(val)
                    : /^https?:\/\//i.test(val);
                if (!allowed) {
                    $err.show().text(mw.lang(
                        "Only http:// and https:// URLs are allowed."
                    ));
                    scope.setSectionValue(null, false);
                    return;
                }
                $err.hide().text("");
                scope.setSectionValue(val, false);
            });

            return $wrap[0];
        },

        ai: function () {
            // AI-83 / TICKET-WW (cycle-94 2026-05-09): aspect-ratio
            // entries now carry a kebab-case `slug` (e.g. '16-9') so
            // the option's swatch can resolve via the
            // `.mw-aspect-ratio-${slug}` CSS class instead of an
            // inline `style="aspect-ratio:..."`. Slashes/colons
            // aren't legal class-name chars so the slug strips them.
            const aspectRatio = [
                { value: "16:9", slug: "16-9", label: "16:9" },
                { value: "9:16", slug: "9-16", label: "9:16" },
                { value: "4:3", slug: "4-3", label: "4:3" },
                { value: "3:4", slug: "3-4", label: "3:4" },
                { value: "1:1", slug: "1-1", label: "1:1" },
            ];
            const id = mw.id();
            const statusId = id + "-status";
            const generateId = id + "-generate";

            // AI-83 / TICKET-WW (cycle-94): every `style=""` in the
            // template string below has been lifted into class
            // selectors in
            // packages/frontend-assets/resources/assets/css/microweber/css/default.css
            // (search for "AI-83 / TICKET-WW"). Strict CSP
            // `style-src 'self'` blocks inline `style=""` attributes;
            // class-based styling satisfies it. Also added:
            //   - `aria-busy` toggle on Generate button
            //   - `aria-describedby` linking button → status region
            //   - `<output role="status" aria-live="polite">` so SR
            //     users hear "Generating image…" / "Image generated"
            //
            // task-2026-05-17-23e0ee / AI-771 — outer wrapper was
            // `max-w-sm mx-auto` (Tailwind ≤24rem centered) +
            // nested `.mw-filepicker-ai-tab-wrap` had max-width:
            // 400px (filepicker.css). Combined effect: AI tab
            // occupied a narrow centered column with dead space
            // either side at desktop, breaking width hierarchy
            // (Generate was "full-width" inside 400px ≈ 280px,
            // visually equal to secondary fields). Per designer
            // Option A greenlight: outer wrapper now `w-full`;
            // inner .mw-filepicker-ai-tab-wrap max-width dropped
            // (see filepicker.css next-to AI-771 marker). Single-
            // column flow fills modal width; Generate becomes
            // truly full-width primary; secondary fields keep
            // their 200px narrow constraint via .mw-filepicker-
            // ai-field-narrow.
            const html = `

                <div class="mw-image-picker-ai w-full">
                <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                <div class="form-control-live-edit-label-wrapper empty:hidden" id="${id}"></div>
                <div class="form-control-live-edit-label-wrapper mw-filepicker-ai-tab-wrap">
                <div class="form-control-live-edit-label-wrapper">
                    <label>${scope._getComponentObject("ai").label}</label>
                    <input class="form-control-live-edit-input" name="prompt" placeholder="${mw.lang(
                        "Describe your image"
                    )}">
                </div>
                <div class="form-control-live-edit-label-wrapper">
                    <label>${mw.lang("Aspect ratio")}</label>
                    <select name="aspect_ratio" class="mw-native-select">
                        <button>
                            <selectedcontent></selectedcontent>
                        </button>
                        ${aspectRatio
                            .map((o) => {
                                return `<option value="${o.value}" data-aspect-ratio="${o.value}">
                                <span class="mw-filepicker-aspect-ratio-icon mw-aspect-ratio-${o.slug}"></span>
                                <span class="mw-filepicker-aspect-ratio-label" >${o.label}</span>
                            </option>`;
                            })
                            .join("")}
                    </select>
                </div>
                 <div class="flex gap-4">
                    <div class="form-control-live-edit-label-wrapper mw-filepicker-ai-field-narrow">
                        <label>${mw.lang("Width")}</label>
                        <!-- audit-test 2026-05-07 Image Picker audit
                             finding 2: width and height had min=1 but
                             no max - a 99999x99999 request burns AI
                             provider quota and could wedge backend.
                             Cap at 2048 client-side as a UX guard;
                             server-side validation is the security
                             guarantee (TICKET-VV). -->
                        <input class="form-control-live-edit-input" name="width" type="number" min="1" max="2048">
                    </div>
                    <div class="form-control-live-edit-label-wrapper mw-filepicker-ai-field-narrow">
                        <label>${mw.lang("Height")}</label>
                        <input class="form-control-live-edit-input" name="height" type="number" min="1" max="2048">
                    </div>
                </div>
                <div class="form-control-live-edit-label-wrapper mw-filepicker-ai-field-narrow d-none">
                    <label>${mw.lang("Number of images")}</label>
                    <input class="form-control-live-edit-input" name="number_of_images" type="number" min="1" max="${
                        scope.settings.multiple ? 6 : 1
                    }" value="1">
                </div>

                <div class="form-control-live-edit-label-wrapper mw-filepicker-ai-field-narrow">
                    <!-- task-2026-05-05-54cda2 (Audit-#1) — fixed
                         "Refference image" typo per external audit.
                         User-facing label is now correctly spelled
                         "Reference image". CSS class names retain
                         the legacy spelling to avoid breaking any
                         existing CSS selectors / theme overrides. -->
                    <label>${mw.lang("Reference image")}</label>
                    <div class="refference-image-pick-container">
                        <span class="refference-image-pick-preview d-none">
                            <span class="refference-image-pick-preview-remove">
                             ${mw.top().app.iconService.icon("delete")}
                            </span>
                        </span>
                        <span class="btn  refference-image-pick">
                            ${mw.lang("Select image")}
                            <!-- audit-test 2026-05-07 Image Picker audit
                                 finding 3: file input had no accept
                                 attr, so a user could attach a PDF or
                                 exe or zip as a reference image. The
                                 accept="image/*" attr makes the OS
                                 file-picker filter to images only;
                                 server-side type validation is the
                                 backstop. -->
                            <input type="file" accept="image/*">
                        </span>
                    </div>
                </div>

                    <br>
                    <button type="button" id="${generateId}" disabled class="btn btn-primary btn-pill w-100 mw-filepicker-ai-generate-btn" data-action="generate" aria-describedby="${statusId}">${mw.lang("Generate")}</button>
                    <output id="${statusId}" role="status" aria-live="polite" class="mw-filepicker-ai-status"></output>
                </div>
                </div>
                </div>
            `;

            var $wrap = this._$inputWrapper();
            $wrap
                .html(html)
                .find('[name="prompt"]')
                .on("input", (e) => {
                    $wrap
                        .find('[data-action="generate"]')
                        .attr("disabled", !e.target.value.trim());
                });

            const refPickerNode = $wrap[0].querySelector(
                ".refference-image-pick"
            );
            const refPickerNodepreview = $wrap[0].querySelector(
                ".refference-image-pick-preview"
            );

            let refImage = null;

            $wrap[0]
                .querySelector('[type="file"]')
                .addEventListener("input", async function () {
                    mw.spinner({ element: $wrap[0], size: 30 }).show();
                    refImage = this.files[0]
                        ? await toDataURL(this.files[0])
                        : null;
                    mw.spinner({ element: $wrap[0], size: 30 }).remove();
                    // AI-83 / TICKET-WW (cycle-94): runtime
                    // background-image stays as a JS style mutation
                    // (CSP's `style-src 'self'` does NOT block
                    // .style.X = Y; only blocks inline `style=""`
                    // attrs in HTML markup), but the show/hide
                    // toggle moved off `.style.display` and onto
                    // the `d-none` class so the initial hidden state
                    // is class-driven (markup) and JS just toggles
                    // the class.
                    refPickerNodepreview.style.backgroundImage = refImage
                        ? `url(${refImage})`
                        : "none";
                    refPickerNodepreview.classList.toggle("d-none", !refImage);
                });
            $wrap[0]
                .querySelector(".refference-image-pick-preview-remove")
                .addEventListener("click", (e) => {
                    refImage = null;
                    refPickerNodepreview.style.backgroundImage = `none`;
                    refPickerNodepreview.classList.add("d-none");
                });

            const submit = async () => {
                const body = {};
                Array.from($wrap[0].querySelectorAll("[name]"))
                    .filter((node) => !!("" + node.value.trim()))
                    .forEach((node) => (body[node.name] = node.value));

                if (refImage) {
                    body.image = refImage;
                }

                var images = [];

                if (window.MwAi) {
                    let message = body["prompt"];
                    let messages = [{ role: "user", content: message }];
                    var mwAi = MwAi();

                    // AI-83 / TICKET-WW (cycle-94): aria-busy + aria-
                    // live status announce so screen-reader users
                    // know the model is working ("Generating image…")
                    // and when it's done.
                    const generateBtn = $wrap[0].querySelector("#" + generateId);
                    const statusOutput = $wrap[0].querySelector("#" + statusId);
                    if (generateBtn) generateBtn.setAttribute("aria-busy", "true");
                    if (statusOutput) statusOutput.textContent = mw.lang("Generating image…");

                    mw.spinner({ element: $wrap[0], size: 30 }).show();
                    let res = await mwAi.generateImage(messages, body);
                    if (res.data && res.data.urls) {
                        images = res.data.urls;
                    }

                    $("#" + id).html(
                        `${images.map((im) => `<img src="${im}">`).join("")}`
                    );
                    scope.setSectionValue(images);
                    mw.spinner({ element: $wrap[0] }).remove();

                    if (generateBtn) generateBtn.removeAttribute("aria-busy");
                    if (statusOutput) statusOutput.textContent = mw.lang("Image generated");
                }
            };

            $wrap[0]
                .querySelector('[type="number"]')
                .addEventListener("change", function () {
                    const max = parseFloat(this.max);
                    if (!isNaN(max) && this.value > max) {
                        this.value = max;
                    }
                });
            $wrap[0]
                .querySelector('[data-action="generate"]')
                .addEventListener("click", (e) => {
                    submit();
                });

            return $wrap[0];
        },

        desktop: function () {
            var _setdesktopType = function () {
                var $zone;
                if (scope.settings.uploaderType === "big") {
                    $zone = $(
                        '<div class="mw-file-drop-zone">' +
                            '<div class="mw-file-drop-zone-holder">' +
                            '<div class="mw-file-drop-zone-img"></div>' +
                            '<div class="mw-ui-progress-small"><div class="mw-ui-progress-bar" style="width: 0%"></div></div>' +
                            '<span class="btn btn-pill  btn-primary">' +
                            mw.lang("Choose file") /* task-2026-05-17-46d1ac AI-741 — verb-led label */ +
                            "</span> " +
                            "<p>" +
                            mw.lang("or drag and drop an image here · JPG, PNG, GIF, WebP up to 10MB") /* task-2026-05-17-46d1ac AI-741 — inline format + size hint */ +
                            "</p>" +
                            "</div>" +
                            "</div>"
                    );
                } else if (scope.settings.uploaderType === "small") {
                    $zone = $(
                        '<div class="mw-file-drop-zone mw-file-drop-zone-small mw-file-drop-square-zone"> <div class="mw-file-drop-zone-holder"> <span class="mw-ui-link">' +
                            mw.lang("Choose file") /* task-2026-05-17-46d1ac AI-741 — verb-led label */ +
                            "</span> " +
                            "<p>" +
                            mw.lang("or drag and drop an image here · JPG, PNG, GIF, WebP up to 10MB") /* task-2026-05-17-46d1ac AI-741 — inline format + size hint */ +
                            "</p>" +
                            "</div>" +
                            "</div>"
                    );
                }
                var $el = $(scope.settings.element).eq(0);
                $el.removeClass(
                    "mw-filepicker-desktop-type-big mw-filepicker-desktop-type-small"
                );
                $el.addClass(
                    "mw-filepicker-desktop-type-" + scope.settings.uploaderType
                );
                scope.uploaderHolder.empty().append($zone);
            };

            var $wrap = this._$inputWrapper(
                scope._getComponentObject("desktop").label
            );
            scope.uploaderHolder = mw.$(
                '<div class="mw-uploader-type-holder"></div>'
            );
            _setdesktopType();

            $wrap.append(scope.uploaderHolder);
            let _uploadTempResult = [];
            scope.uploader = mw.upload({
                element: $wrap[0],
                multiple: scope.settings.multiple,
                type: scope.settings.type,
                on: {
                    progress: function (prg, obj, b) {
                        scope.uploaderHolder
                            .find(".mw-ui-progress-bar")
                            .stop()
                            .animate({ width: prg.percent + "%" }, "fast");
                    },
                    fileUploadError: function (file) {
                        $(scope).trigger("FileUploadError", [file]);
                    },
                    fileAdded: function (file) {
                        $(scope).trigger("FileAdded", [file]);
                        _uploadTempResult = [];
                    },
                    filesUploaded: function () {
                        if (!scope.settings.disableFileAutoSelect) {
                            scope.result(_uploadTempResult.map((o) => o.src));
                        }
                    },
                    fileUploaded: function (file) {
                        scope.setSectionValue(file);

                        _uploadTempResult.push(file);

                        $(scope).trigger("FileUploaded", [file]);

                        // AI-117 / TICKET-CK (cycle-108 2026-05-09):
                        // first-upload-after-empty-library hand-off.
                        // If the picker was started on "desktop"
                        // because MediaLibrary was empty, the FIRST
                        // successful upload populates the library —
                        // switch the user back to the "library" tab
                        // so they see what they just uploaded in
                        // context (and can pick it).
                        // Idempotent: subsequent uploads don't re-
                        // switch (only fires when we WERE on desktop
                        // due to empty-library + this is the first
                        // successful upload).
                        if (
                            !scope.__libraryHandoffDone &&
                            scope.settings.firstActiveComponent === "desktop" &&
                            typeof window !== "undefined" &&
                            window.MwMediaLibraryEmpty === true
                        ) {
                            scope.__libraryHandoffDone = true;
                            window.MwMediaLibraryEmpty = false;
                            var $libraryTab = $(
                                "a.js-filepicker-pick-type-tab-library",
                                scope.$root
                            );
                            if ($libraryTab.length) {
                                setTimeout(function () {
                                    $libraryTab.trigger("click");
                                }, 100);
                            }
                        }

                        if (scope.settings.fileUploaded) {
                            scope.settings.fileUploaded(file);
                        }
                        if (!scope.settings.multiple) {
                            mw.notification.success("File uploaded");
                            scope.uploaderHolder
                                .find(".mw-file-drop-zone-img")
                                .css(
                                    "backgroundImage",
                                    "url(" + file.src + ")"
                                );
                        }
                        // scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                    },
                },
            });
            return $wrap[0];
        },
        server: function () {
            var $wrap = this._$inputWrapper(
                scope._getComponentObject("server").label
            );
            /*mw.load_module('files/admin', $wrap, function () {

            }, {'filetype':'images'});*/

            $(scope).on("$firstOpen", function (e, el, type) {
                if (type === "server") {
                    var fm = mw.FileManager({
                        element: $wrap[0],
                        canSelectFolder: false,
                        selectable: true,
                        multiselect: scope.settings.multiple,
                        stickyHeader: true,
                        selectableRow: true,
                        type: scope.settings.type,
                    });
                    fm.on("insert", function () {
                        scope.result();
                    });
                    fm.on("insertByValue", function (val) {
                        scope.setSectionValue(val.map((a) => a.url));
                        scope.result();
                    });
                    fm.on("selectionChanged", function (val) {
                        scope.setSectionValue(val.map((a) => a.url));
                        if (scope.settings.autoSelect) {
                            scope.result();
                        }
                    });
                }
            });

            return $wrap[0];
        },
        library: function () {
            var $wrap = this._$inputWrapper(
                scope._getComponentObject("library").label
            );
            // task-2026-05-22-a901b0 / AI-901 Phase 1 — shared mount helper
            // task-2026-05-22-faf839 / AI-761 — skeleton loader + empty state
            var _mountLib = function (loadIndicatorEl) {
                if (loadIndicatorEl) mw.tools.loading(loadIndicatorEl, true);

                // Skeleton: 6 shimmer tiles matching the post-load 3-col grid.
                var $skeleton = $('<div class="mw-filepicker-library-skeleton" aria-hidden="true">');
                for (var _si = 0; _si < 6; _si++) {
                    $skeleton.append('<div class="mw-filepicker-library-skeleton-tile">');
                }
                $wrap.append($skeleton);

                var fr = mw
                    .top()
                    .tools.moduleFrameRoute("filament.admin.pages.media-library");
                scope._libraryFrameBaseSrc = fr.src;
                // Keep iframe visually hidden until row-count message arrives
                // so the skeleton is the only visible element during load.
                fr.style.opacity = '0';
                fr.style.position = 'absolute';
                fr.style.pointerEvents = 'none';
                $wrap.append(fr);
                if (scope.settings._frameMaxHeight) {
                    fr.style.maxHeight = "60vh";
                    fr.scrolling = "yes";
                }

                var _emptyEl = null;

                // 8s safety net: unhide iframe even if postMessage never arrives.
                var _safetyId = setTimeout(function () {
                    $skeleton.remove();
                    fr.style.opacity = '';
                    fr.style.position = '';
                    fr.style.pointerEvents = '';
                }, 8000);

                // postMessage listener — media-library iframe posts row count
                // once Livewire has rendered (see media-library-page.blade.php).
                var _onRowCount = function (evt) {
                    if (!evt.data || evt.data.type !== 'mw-filemanager:row-count') return;
                    window.removeEventListener('message', _onRowCount);
                    clearTimeout(_safetyId);
                    if (loadIndicatorEl) mw.tools.loading(loadIndicatorEl, false);
                    $skeleton.remove();

                    if (evt.data.count === 0) {
                        // Truly empty library: show in-picker empty state.
                        fr.style.display = 'none';
                        _emptyEl = $('<div class="mw-filepicker-library-empty">' +
                            '<span class="mw-filepicker-library-empty-icon" aria-hidden="true">&#128444;&#65039;</span>' +
                            '<h3 class="mw-filepicker-library-empty-heading">' +
                            mw.lang('No files yet') +
                            '</h3>' +
                            '<p class="mw-filepicker-library-empty-body">' +
                            mw.lang('Upload your first image — or paste a URL on the URL tab.') +
                            '</p>' +
                            '<button type="button" class="mw-filepicker-library-empty-cta">' +
                            mw.lang('Upload from device →') +
                            '</button>' +
                            '</div>')[0];
                        $wrap.append(_emptyEl);

                        $(_emptyEl).find('.mw-filepicker-library-empty-cta').on('click', function () {
                            var $desktopTab = $('a.js-filepicker-pick-type-tab-desktop', scope.$root);
                            if ($desktopTab.length) {
                                $desktopTab.trigger('click');
                            }
                        });

                        // First upload: remove empty state and reveal library.
                        $(scope).one('FileUploaded', function () {
                            if (_emptyEl) {
                                $(_emptyEl).remove();
                                _emptyEl = null;
                            }
                            fr.style.display = '';
                            fr.style.opacity = '';
                            fr.style.position = '';
                            fr.style.pointerEvents = '';
                            fr.src = scope._libraryFrameBaseSrc;
                        });
                    } else {
                        // Library has files — reveal iframe.
                        fr.style.opacity = '';
                        fr.style.position = '';
                        fr.style.pointerEvents = '';
                    }
                };
                window.addEventListener('message', _onRowCount);

                fr.onload = function () {
                    // Wire file selection back to the picker.
                    try {
                        this.contentWindow.mw.on.hashParam(
                            "select-file",
                            function (pval) {
                                var url = pval.toString();
                                scope.setSectionValue(url);
                                if (scope.settings.autoSelect) {
                                    scope.result();
                                }
                            }
                        );
                    } catch (_e) {
                        // contentWindow.mw unavailable (cross-origin or not yet ready).
                    }
                };
            };
            if (scope.settings.nav === 'action-bar') {
                // action-bar mode: mount library immediately (no lazy-mount per AI-901 designer spec)
                setTimeout(function () { _mountLib(null); }, 0);
            } else {
                $(scope).on("$firstOpen", function (e, el, type) {
                    if (type === "library") {
                        _mountLib(el);
                    }
                });
            }

            /*mw.load_module('pictures/media_library', $wrap);*/
            return $wrap[0];
        },
    };

    this.hideUploaders = function (type) {
        mw.$(".mw-filepicker-component-section", this.$root).hide();
    };

    this.showUploaders = function (type) {
        mw.$(".mw-filepicker-component-section", this.$root).show();
    };

    // task-2026-05-22-a901b0 / AI-901 Phase 1 — overlay helpers for action-bar mode
    this._overlayEls = {};
    this._buildOverlay = function (type) {
        var $overlay = $('<div class="mw-filepicker-overlay mw-filepicker-overlay--' + type + '" hidden>');
        var $closeBtn = $('<button type="button" class="mw-filepicker-overlay-close" aria-label="' + mw.lang('Close') + '">&times;</button>');
        var $content = $('<div class="mw-filepicker-overlay-content">');
        var comp = scope.components[type] ? scope.components[type]() : null;
        if (comp) $content.append(comp);
        $overlay.append($closeBtn, $content);
        $closeBtn.on('click', function () { scope.hideAllOverlays(); });
        scope.$root.find('.mw-filepicker-library-section').append($overlay);
        scope._overlayEls[type] = $overlay[0];
        return $overlay;
    };
    this.toggleOverlay = function (type) {
        var existing = scope._overlayEls[type];
        if (existing && !existing.hidden) {
            scope.hideAllOverlays();
        } else {
            scope.hideAllOverlays();
            if (!existing) scope._buildOverlay(type);
            scope._overlayEls[type].hidden = false;
        }
    };
    this.hideAllOverlays = function () {
        $.each(scope._overlayEls, function (k, el) {
            if (el) el.hidden = true;
        });
    };

    this.settings.components = this.settings.components.filter(function (item) {
        return !!scope.components[item.type];
    });

    this._navigation = null;
    this.__navigation_first = [];

    this.navigation = function () {
        this._navigationHeader = document.createElement("div");
        this._navigationHeader.className =
            "mw-filepicker-component-navigation-header mb-3" +
            (this.settings.boxed ? "card-header no-border" : "");
        if (this.settings.hideHeader) {
            this._navigationHeader.style.display = "none";
        }
        if (this.settings.label) {
            this._navigationHeader.innerHTML =
                "<h6><strong>" + this.settings.label + "</strong></h6>";
        }
        this._navigationHolder = document.createElement("div");
        if (this.settings.nav === false) {
        } else if (this.settings.nav === "tabs") {
            // task-2026-05-17-2c33e0 / AI-768 — picker tab nav carries
            // its own `.mw-filepicker-component-tab-nav` ALONGSIDE the
            // legacy shared `.mw-live-edit-resolutions-wrapper` so it
            // (a) keeps the visual styling (rounded chip-strip via
            // general-styles.css :164+), and (b) is exempt from the
            // mobile hide rule via `:not(.mw-filepicker-component-
            // tab-nav)` in mobile-touch.css. Pre-fix, the picker
            // tabs disappeared at <576px because mobile-touch.css
            // line 150 hides `.mw-live-edit-resolutions-wrapper`
            // (intended for the canvas device-switcher only) and
            // the picker reused that class without escape. Per
            // designer's Option A — the two surfaces no longer
            // share a hide-class policy.
            var ul = $(
                '<div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper mw-filepicker-component-tab-nav mx-0" />'
            );
            this.settings.components.forEach(function (item) {
                ul.append(
                    '<a href="javascript:;" class="js-filepicker-pick-type-tab-' +
                        item.type +
                        ' tblr-body-color live-edit-toolbar-buttons w-100" data-type="' +
                        item.type +
                        '">' +
                        item.label +
                        "</a>"
                );
            });
            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(ul[0]);
            setTimeout(function () {
                scope._tabs = mw.tabs({
                    nav: $("a", ul),
                    tabs: $(".mw-filepicker-component-section", scope.$root),
                    activeClass: "active",
                    // task-2026-05-17-49ad9d / AI-770 v2 CHANGE —
                    // disable mw.tabs default `toggle:true` behaviour
                    // for the picker. Pre-fix order at init:
                    //   1. mw.tabs() sets .active on first tab (library)
                    //   2. AI-770 v1 trigger("click") on library
                    //   3. onclick saw hasClass("active") → toggle-OFF
                    //      branch (removed active, hid content)
                    //   4. $firstOpen only fires in toggle-ON branch
                    //      → never fired for library
                    //   5. Library iframe never mounted
                    // Designer confirmed empirically via probe of the
                    // mw.tabs event handler source. With `toggle:false`
                    // the click on an already-active tab no-ops the
                    // toggle decision and the onclick callback runs
                    // normally → $firstOpen fires → iframe mounts.
                    // Also closes a latent UX bug — for a 1-of-N
                    // picker, accidentally hiding the active tab
                    // content by re-clicking is wrong by design.
                    toggle: false,
                    onclick: function (el, event, i) {
                        if (scope.__navigation_first.indexOf(i) === -1) {
                            scope.__navigation_first.push(i);
                            $(scope).trigger("$firstOpen", [
                                el,
                                this.dataset.type,
                            ]);
                        }
                        scope.manageActiveSectionState();

                        setTimeout(function () {
                            var dialog = mw.top().dialog.get();
                            if (dialog) {
                                dialog.center();
                            }
                        }, 50);
                    },
                });

                // AI-117 / TICKET-CK (cycle-108 2026-05-09): if the
                // caller asked for a non-default first tab (e.g.
                // empty MediaLibrary → "desktop"), trigger a click
                // on the matching nav anchor. Defer one tick so the
                // mw.tabs() init has wired its handlers first.
                if (scope.settings.firstActiveComponent) {
                    setTimeout(function () {
                        var $target = $(
                            'a.js-filepicker-pick-type-tab-' +
                                scope.settings.firstActiveComponent,
                            ul
                        );
                        if ($target.length) {
                            $target.trigger("click");
                        }
                    }, 0);
                } else if (scope.settings.components.length > 0) {
                    // task-2026-05-17-00bdf0 / AI-770 — when no
                    // `firstActiveComponent` override is set, the
                    // first tab in the components array is shown
                    // as visually active by mw.tabs() but its
                    // onclick handler never fires, so the per-tab
                    // `$firstOpen` event never triggers for the
                    // default initial tab. On desktop this left
                    //
                    // task-2026-05-17-49ad9d / AI-770 v2 CHANGE —
                    // pivoted from "trigger('click') on the active
                    // tab" to "fire $firstOpen directly". With the
                    // mw.tabs `toggle: false` flip above, clicking
                    // an already-active tab is a no-op (else branch
                    // in tools/tabs.js:97 doesn't call onclick when
                    // toggle is not true). Per designer's Option B
                    // combined with Option C: bypass the click +
                    // fire $firstOpen directly so the iframe mounts
                    // AND the latent re-click-hides-tab UX bug also
                    // closes. The __navigation_first guard mirrors
                    // the same guard inside mw.tabs.onclick at
                    // lines 837-844, so a subsequent real click on
                    // the library tab won't double-fire.
                    // the default "library" tab body blank — the
                    // iframe-mount in `library: function()` is
                    // gated on `$firstOpen` and that gate never
                    // opened. Mobile coincidentally hit a click-
                    // path that triggered it.
                    //
                    // Fire $firstOpen directly for the first tab
                    // (the visually-active default).
                    setTimeout(function () {
                        var firstType = scope.settings.components[0].type;
                        var $target = $(
                            'a.js-filepicker-pick-type-tab-' + firstType,
                            ul
                        );
                        var $section = $(
                            ".mw-filepicker-component-section",
                            scope.$root
                        ).eq(0);
                        if ($target.length && scope.__navigation_first.indexOf(0) === -1) {
                            scope.__navigation_first.push(0);
                            $(scope).trigger("$firstOpen", [
                                $section[0],
                                firstType,
                            ]);
                        }
                    }, 0);
                }
            }, 78);
        } else if (this.settings.nav === "dropdown") {
            var select = $(
                '<select class="form-select btn-as-link" data-style="btn-sm" data-width="auto" data-title="' +
                    mw.lang("Choose file") /* task-2026-05-17-46d1ac AI-741 — verb-led label */ +
                    '"/>'
            );
            scope._select = select;
            this.settings.components.forEach(function (item) {
                select.append(
                    '<option class="nav-item" value="' +
                        item.type +
                        '">' +
                        item.label +
                        "</option>"
                );
            });

            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(select[0]);
            select.on("changed.bs.select", function (e, xval) {
                var val = select.selectpicker("val");
                var componentObject = scope._getComponentObject(val);
                var index = scope.settings.components.indexOf(componentObject);
                var items = $(".mw-filepicker-component-section", scope.$root);
                if (scope.__navigation_first.indexOf(val) === -1) {
                    scope.__navigation_first.push(val);
                    $(scope).trigger("$firstOpen", [items.eq(index)[0], val]);
                }
                if (scope.settings.dropDownTargetMode === "dialog") {
                    var temp = document.createElement("div");
                    var item = items.eq(index);
                    item.before(temp);
                    item.show();
                    var footer = false;
                    if (scope._getComponentObject("url").index === index) {
                        footer = document.createElement("div");
                        var footerok = $(
                            '<button type="button" class=" btn btn-primary">' +
                                _okLabel() +
                                "</button>"
                        );
                        var footercancel = $(
                            '<button type="button" class=" btn">' +
                                scope.settings.cancelLabel +
                                "</button>"
                        );
                        footerok.disabled = true;
                        footer.appendChild(footercancel[0]);
                        footer.appendChild(footerok[0]);
                        footer.appendChild(footercancel[0]);
                        footercancel.on("click", function () {
                            scope.__pickDialog.remove();
                        });
                        footerok.on("click", function () {
                            scope.setSectionValue(
                                scope.$urlInput.val().trim() || null
                            );
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                            // scope.__pickDialog.remove();
                        });
                    }

                    scope.__pickDialog = mw.top().dialog({
                        overlay: true,
                        content: item,
                        beforeRemove: function () {
                            $(temp).replaceWith(item);
                            item.hide();
                            scope.__pickDialog = null;
                        },
                        footer: footer,
                    });
                } else {
                    items.hide().eq(index).show();
                }
            });
        } else if (this.settings.nav === 'action-bar') {
            // task-2026-05-22-a901b0 / AI-901 Phase 1 — action bar replaces tabs
            this._navigationHolder.appendChild(this._navigationHeader);

            var $actionBar = $('<div class="mw-filepicker-action-bar">');

            scope.$searchInput = $('<input type="search" class="mw-filepicker-search-input form-control" placeholder="' + mw.lang('Search media') + '" aria-label="' + mw.lang('Search media') + '">');

            var _iconBtn = function (cls, label, svgPath) {
                return $(
                    '<button type="button" class="mw-filepicker-action-btn btn btn-outline-secondary ' + cls + '" aria-label="' + label + '" title="' + label + '">' +
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">' + svgPath + '</svg>' +
                    '</button>'
                );
            };

            var $btnUpload = _iconBtn('mw-filepicker-action-btn--upload', mw.lang('Upload'),
                '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17,8 12,3 7,8"/><line x1="12" y1="3" x2="12" y2="15"/>');
            var $btnAi = _iconBtn('mw-filepicker-action-btn--ai', mw.lang('Generate AI image'),
                '<circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>');
            var $btnUrl = _iconBtn('mw-filepicker-action-btn--url', mw.lang('Image URL'),
                '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>');

            $actionBar.append(scope.$searchInput, $btnUpload, $btnAi, $btnUrl);
            this._navigationHeader.appendChild($actionBar[0]);

            // Search: 150ms debounce → reload library iframe with keywords param
            var _searchTimer;
            scope.$searchInput.on('input', function () {
                clearTimeout(_searchTimer);
                var q = scope.$searchInput.val();
                _searchTimer = setTimeout(function () {
                    var fr = scope.$root.find('iframe')[0];
                    if (fr) {
                        try { fr.contentWindow.postMessage({ action: 'mw-filepicker-search', q: q }, '*'); } catch (e) {}
                        var baseSrc = scope._libraryFrameBaseSrc;
                        if (baseSrc) {
                            fr.src = q ? baseSrc + (baseSrc.indexOf('?') >= 0 ? '&' : '?') + 'keywords=' + encodeURIComponent(q) : baseSrc;
                        }
                    }
                }, 150);
            });

            // Drag-enter on picker root shows upload overlay
            scope.$root.on('dragenter dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (!scope._overlayEls.desktop || scope._overlayEls.desktop.hidden) {
                    scope.toggleOverlay('desktop');
                }
            });

            $btnUpload.on('click', function () { scope.toggleOverlay('desktop'); });
            $btnAi.on('click', function () { scope.toggleOverlay('ai'); });
            $btnUrl.on('click', function () { scope.toggleOverlay('url'); });
        }
        this.$root.prepend(this._navigationHolder);
    };
    this.__displayControllerByTypeTime = null;

    this.displayControllerByType = function (type) {
        type = (type || "").trim();
        var item = this._getComponentObject(type);
        clearTimeout(this.__displayControllerByTypeTime);
        this.__displayControllerByTypeTime = setTimeout(function () {
            if (scope.settings.nav === "tabs") {
                mw.$('[data-type="' + type + '"]', scope.$root).click();
            } else if (scope.settings.nav === "dropdown") {
                $(scope._select).selectpicker("val", type);
            }
        }, 10);
    };

    this.footer = function () {
        if (!this.settings.footer || this.settings.autoSelect) return;
        this._navigationFooter = document.createElement("div");
        this._navigationFooter.className =
            "modal-footer mw-ui-form-controllers-footer mw-filepicker-footer " +
            (this.settings.boxed ? "card-footer" : "");
        this.$ok = $(
            '<button type="button" class="btn btn-primary mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component">' +
                _okLabel() +
                "</button>"
        );
        this.$cancel = $(
            '<button type="button" class="btn mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component">' +
                this.settings.cancelLabel +
                "</button>"
        );
        this._navigationFooter.appendChild(this.$cancel[0]);
        this._navigationFooter.appendChild(this.$ok[0]);
        this.$root.append(this._navigationFooter);
        this.$ok[0].disabled = true;
        this.$ok.on("click", function () {
            scope.result();
        });
        this.$cancel.on("click", function () {
            scope.settings.cancel();
        });
    };

    this.result = function (result) {
        var activeSection = this.activeSection();
        if (!result) {
            result = activeSection._filePickerValue;
        }
        if (this.settings.onResult) {
            this.settings.onResult.call(this, result);
        }
        result = Array.isArray(result) ? result : [result];
        $(scope).trigger("Result", [...result]);
    };

    this.getValue = function () {
        return this.activeSection()._filePickerValue;
    };

    this._getComponentObject = function (type) {
        return this.settings.components.find(function (comp) {
            return comp.type && comp.type === type;
        });
    };

    this._sections = [];
    this.buildComponentSection = function () {
        var main = mw.$(
            '<div class="' +
                (this.settings.boxed ? "card-body" : "") +
                ' mw-filepicker-component-section"></div>'
        );
        this.$root.append(main);
        this._sections.push(main[0]);
        return main;
    };

    this.buildComponent = function (component) {
        if (this.components[component.type]) {
            return this.components[component.type]();
        }
    };

    this.buildComponents = function () {
        $.each(this.settings.components, function () {
            var component = scope.buildComponent(this);
            if (component) {
                var sec = scope.buildComponentSection();
                sec.append(component);
            }
        });
    };

    this.build = function () {
        this.navigation();
        if (this.settings.nav === 'action-bar') {
            // action-bar: only library section is always-visible; other components are overlays
            var _libComp = scope.buildComponent({ type: 'library' });
            if (_libComp) {
                var _libSec = scope.buildComponentSection();
                _libSec.addClass('mw-filepicker-library-section');
                _libSec.append(_libComp);
            }
        } else {
            this.buildComponents();
        }
        if (this.settings.nav === "dropdown") {
            $(".mw-filepicker-component-section", scope.$root)
                .hide()
                .eq(0)
                .show();
        }
        this.footer();
    };

    this.init = function () {
        // task-2026-05-22-a901b0 / AI-901 Phase 1: action-bar mode overrides
        if (this.settings.nav === 'action-bar') {
            this.settings.autoSelect = true;
            this.settings.footer = false;
        }
        this.build();
        if (this.settings.element) {
            $(this.settings.element).eq(0).append(this.$root);
        }
        if ($.fn.selectpicker) {
            $("select", scope.$root).selectpicker();
        }
    };

    this.hide = function () {
        this.$root.hide();
    };
    this.show = function () {
        this.$root.show();
    };

    this.activeSection = function () {
        return $(this._sections).filter(function () {
            return $(this).css("display") !== "none";
        })[0];
    };

    this.setSectionValue = function (val, close) {
        close = typeof close === "undefined" ? true : close;
        var activeSection = this.activeSection();
        if (activeSection) {
            activeSection._filePickerValue = val;
        }

        if (close && scope.__pickDialog) {
            scope.__pickDialog.remove();
        }
        this.manageActiveSectionState();
    };
    this.manageActiveSectionState = function () {
        // if user provides value for more than one section, the active value will be the one in the current section
        var activeSection = this.activeSection();
        if (this.$ok && this.$ok[0]) {
            this.$ok[0].disabled = !(
                activeSection && activeSection._filePickerValue
            );
        }
        _okLabel(activeSection?._filePickerValue);
    };

    this.init();
};
