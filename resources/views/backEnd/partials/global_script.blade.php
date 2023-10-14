<script>
    let valuesArray = [], previousQuantity, currentEl = [];

    @if(str_contains(url()->current(), '/products'))
        valuesArray.push($('.image_selected_files').val().split(','));
        if($('.image_selected_files').val() == ''){
            valuesArray[0].reverse();
        }

        if(valuesArray[0] != ''){
            document.cookie = "previousQuantity=" + valuesArray[0].length;
        }else{
            valuesArray.length = 0;
        }
        @if(str_contains(url()->current(), 'products') && (str_contains(url()->current(), 'create')))
            if(currentEl.length === 0){
                document.cookie = "previousQuantity=" + 0;
            }
        @endif
        function getCookie(name) {
        var cookies = document.cookie.split(";");

        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.startsWith(name + "=")) {
                return cookie.substring(name.length + 1, cookie.length);
            }
        }

        return null;
    }
    @endif

    function addImageAlts(addNew = null){
        @if(str_contains(url()->current(), 'products') && (str_contains(url()->current(), 'edit') || str_contains(url()->current(), 'create')))

        previousQuantity = Number(getCookie("previousQuantity"));

        console.log(previousQuantity, addNew);

        for (let i = previousQuantity; i < addNew; i++){
            $('.images_alt').append('<div class="col-lg-12 val_' + (i + 1) + '"><div class="primary_input mb-15"><label class="primary_input_label" for="">{{ __('common.image_alt') }} ' + (i + 1) +'</label><input class="primary_input_field" name="images_alt[]" placeholder="{{ __('common.image_alt') }}" type="text" value=""></div></div>');
        }

        document.cookie = "previousQuantity=" + addNew;

        @endif
    }

    // add to cart
    function addToCart(product_sku_id, seller_id, qty, price, shipping_type, type) {
        $('#add_to_cart_btn').prop('disabled',true);
        $('#add_to_cart_btn').html("{{__('defaultTheme.adding')}}");
        var formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('price', price);
        formData.append('qty', qty);
        formData.append('product_id', product_sku_id);
        formData.append('seller_id', seller_id);
        formData.append('shipping_method_id', shipping_type);
        formData.append('type', type);
        $('#pre-loader').removeClass('d-none');

        var base_url = $('#url').val();
        $.ajax({
            url: base_url + "/cart/store",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                console.log(response);
                if(response == 'out_of_stock'){
                    toastr.error('No more product to buy.');
                    $('#pre-loader').addClass('d-none');
                    $('#add_to_cart_btn').prop('disabled',false);
                    $('#add_to_cart_btn').html("{{__('defaultTheme.add_to_cart')}}");
                }else{
                    toastr.success("{{__('defaultTheme.product_successfully_added_to_cart')}}", "{{__('common.success')}}");
                    if (window.location.pathname.split( '/' ) == ",my-wishlist") {
                        location.reload();
                    }
                    $('#add_to_cart_btn').prop('disabled',false);
                    $('#add_to_cart_btn').html("{{__('defaultTheme.add_to_cart')}}");

                    $('#cart_inner').html(response);
                    if ($(".add-product-to-cart-using-modal").length){
                        $('.add_to_cart_modal').modal('hide');
                    }
                    $('#pre-loader').addClass('d-none');
                }
            },
            error: function (response) {
                toastr.error("{{__('defaultTheme.product_not_added')}}","{{__('common.error')}}");
                $('#add_to_cart_btn').prop('disabled',false);
                $('#add_to_cart_btn').html("{{__('defaultTheme.add_to_cart')}}");
                $('#pre-loader').addClass('d-none');
            }
        });
    }

    // add to wishlist
    function addToWishlist(seller_product_id, seller_id, type) {
        $('#wishlist_btn').addClass('wishlist_disabled');
        $('#wishlist_btn').html("{{__('defaultTheme.adding')}}");
        $('#pre-loader').show();

        $.post('{{ route('frontend.wishlist.store') }}', {_token:'{{ csrf_token() }}', seller_product_id:seller_product_id, seller_id:seller_id, type: type}, function(data){
            if(data == 1){
                toastr.success("{{__('defaultTheme.successfully_added_to_wishlist')}}","{{__('common.success')}}");
                $('#wishlist_btn').removeClass('wishlist_disabled');
                $('#wishlist_btn').html("{{__('defaultTheme.add_to_wishlist')}}");
            }else if(data == 3){
                toastr.warning("{{__('defaultTheme.product_already_in_wishList')}}","{{__('defaultTheme.thanks')}}");
                $('#wishlist_btn').removeClass('wishlist_disabled');
                $('#wishlist_btn').html("{{__('defaultTheme.add_to_wishlist')}}");
            }
            else{
                toastr.error("{{__('common.error_message')}}","{{__('common.error')}}");
                $('#wishlist_btn').removeClass('wishlist_disabled');
                $('#wishlist_btn').html("{{__('defaultTheme.add_to_wishlist')}}");
            }
            $('#pre-loader').hide();
        });
    }
    function wishlistToggle(id){
        $('#'+id).addClass('is_wishlist');
    }

    // add to comparegit
    function addToCompare(product_sku_id, product_type, type){
        if(product_sku_id && type){
            $('#pre-loader').show();
            let data = {
                '_token' : '{{ csrf_token() }}',
                'product_sku_id' : product_sku_id,
                'data_type' : type,
                'product_type' : product_type
            }

            $.post("{{route('frontend.compare.store')}}", data, function(response){
                if(response == 1){
                    toastr.success("{{__('defaultTheme.product_added_to_compare_list_successfully')}}","{{__('common.success')}}")
                    $("#theme_modal").modal('hide');
                }else{
                    toastr.error("{{__('defaultTheme.not_added')}}","{{__('common.error')}}")

                }
                $('#pre-loader').hide();
            });
        }
    }

    $.fn.toggleAttr = function (attr, attr1, attr2) {
        return this.each(function () {
            var self = $(this);
            if (self.attr(attr) == attr1) self.attr(attr, attr2);
            else self.attr(attr, attr1);
        });
    };


    const Amaz = new Object();
    Amaz.data = {
        csrf: $('meta[name="_token"]').attr("content"),
        appUrl: '{{url("/")}}',
        fileBaseUrl: '{{url("/")}}' + '/public/'
    };
    Amaz.uploader = {
        data: {
            selectedFiles: [],
            selectedFilesObject: [],
            clickedForDelete: null,
            allFiles: [],
            multiple: false,
            type: "all",
            next_page_url: null,
            prev_page_url: null,
            for_name:''
        },
        amazUppy: function () {
            if ($(".AmazUppyDragDrop").length > 0) {
                var uppy = new Uppy.Core({
                    autoProceed: true,
                });
                uppy.use(Uppy.Dashboard, {
                    target: ".AmazUppyDragDrop",
                    inline: true,
                    showLinkToFileUploadResult: false,
                    showProgressDetails: true,
                    hideCancelButton: true,
                    hidePauseResumeButton: true,
                    hideUploadButton: true,
                    proudlyDisplayPoweredByUppy: false,
                    locale: {
                        strings: {

                        }
                    }
                });
                uppy.use(Uppy.XHRUpload, {
                    endpoint: Amaz.data.appUrl + "/media-manager/new-upload-store",
                    fieldName: "file",
                    formData: true,
                    headers: {
                        'X-CSRF-TOKEN': Amaz.data.csrf,
                    },
                });
                uppy.on("upload-success", function () {
                    Amaz.uploader.getAllUploads(
                        Amaz.data.appUrl + "/media-manager/get-files-modal"
                    );
                });
            }
        },
        getAllUploads: function (url, search_key = null, sort_key = null) {
            $("#all_files_div").html(
                '<div class="loader_media"><div class="hhhdots_1"></div></div>'
            );
            var params = {};
            if (search_key != null && search_key.length > 0) {
                params["search"] = search_key;
            }
            if (sort_key != null && sort_key.length > 0) {
                params["sort"] = sort_key;
            }
            else{
                params["sort"] = 'newest';
            }
            $.get(url, params, function (data, status) {

                if(typeof data == 'string'){
                    data = JSON.parse(data);
                }
                Amaz.uploader.data.allFiles = data.files.data;
                Amaz.uploader.allowedFileType();
                Amaz.uploader.addSelectedValue();
                Amaz.uploader.addHiddenValue();
                Amaz.uploader.updateUploaderFiles();
                if (data.files.next_page_url != null) {
                    Amaz.uploader.data.next_page_url = data.files.next_page_url;
                    $("#uploader_next_btn").removeAttr("disabled");
                } else {
                    $("#uploader_next_btn").attr("disabled", true);
                }
                if (data.files.prev_page_url != null) {
                    Amaz.uploader.data.prev_page_url = data.files.prev_page_url;
                    $("#uploader_prev_btn").removeAttr("disabled");
                } else {
                    $("#uploader_prev_btn").attr("disabled", true);
                }
            });
        },
        allowedFileType: function () {
            if (Amaz.uploader.data.type !== "all") {
                let type = Amaz.uploader.data.type.split(',')
                Amaz.uploader.data.allFiles = Amaz.uploader.data.allFiles.filter(
                    function (item) {
                        return type.includes(item.type);
                    }
                );
            }
        },
        addHiddenValue: function () {
            for (var i = 0; i < Amaz.uploader.data.allFiles.length; i++) {
                Amaz.uploader.data.allFiles[i].aria_hidden = false;
            }
        },
        addSelectedValue: function () {
            for (var i = 0; i < Amaz.uploader.data.allFiles.length; i++) {
                if (
                    !Amaz.uploader.data.selectedFiles.includes(
                        Amaz.uploader.data.allFiles[i].id
                    )
                ) {
                    Amaz.uploader.data.allFiles[i].selected = false;
                } else {
                    Amaz.uploader.data.allFiles[i].selected = true;
                }
            }
        },
        updateUploaderSelected: function () {
            $(".upload_files_selected").html(
                Amaz.uploader.updateFileHtml(Amaz.uploader.data.selectedFiles)
            );
        },
        updateFileHtml: function (array) {
            var fileText = "";
            if (array.length > 1) {
                var fileText = 'File Selected';
            } else {
                var fileText = 'Files Selected';
            }
            return array.length + " " + fileText;
        },
        updateUploaderFiles: function () {
            $("#all_files_div").html(
                '<div class="loader_media"><div class="hhhdots_1"></div></div>'
            );

            var data = Amaz.uploader.data.allFiles;

            setTimeout(function () {
                $("#all_files_div").html(null);

                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        var thumb = "";
                        var hidden = "";
                        if (data[i].type === "image") {
                            if(data[i].storage == 'local'){
                                thumb =
                                '<img src="' +
                                Amaz.data.fileBaseUrl +
                                data[i].file_name +
                                '" class="">';
                            }else{
                                thumb =
                                '<img src="' + data[i].file_name +'" class="">';
                            }

                        } else {
                            thumb = '<i class="ti-files"></i>';
                        }
                        var html = `
                            <div class="amazcart_file_body single_files" aria-hidden="${data[i].aria_hidden}" data-selected="${data[i].selected}">
                                <div class="modal_file_box" data-value="${data[i].id}">
                                    <div class="img-box">
                                        ${thumb}
                                    </div>
                                    <div class="amazcart_file_content-box">
                                        <div class="file-content-wrapper">
                                            <h5>${data[i].orginal_name}</h5>
                                            <p>${data[i].size} kb</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;


                        $("#all_files_div").append(html);
                    }
                } else {
                    $("#all_files_div").html(
                        '<div class="align-items-center d-flex justify-content-center w-100"><div class="text-center"><h3>No files found</h3></div></div>'
                    );
                }
                Amaz.uploader.uploadSelect();
                // Amaz.uploader.deleteUploaderFile();
            }, 300);
        },
        searchUploaderFiles: function () {
            var timeout;
            $('[name="amaz_media_search"]').on("keyup", function () {
                var value = $(this).val();

                if(timeout) {
                    clearTimeout(timeout);
                }
                timeout = setTimeout(function() {
                    Amaz.uploader.getAllUploads(
                        Amaz.data.appUrl + "/media-manager/get-files-modal",
                        value,
                        $('[name="Amaz_media_sort"]').val()
                    );
                }, 300);
            });

        },
        sortUploaderFiles: function () {
            $('[name="Amaz_media_sort"]').on("change", function () {
                var value = $(this).val();
                Amaz.uploader.getAllUploads(
                    Amaz.data.appUrl + "/media-manager/get-files-modal",
                    $('[name="amaz_media_search"]').val(),
                    value
                );
            });
        },
        uploadSelect: function () {
            $(".modal_file_box").each(function () {
                var elem = $(this);
                elem.on("click", function (e) {
                    var value = $(this).data("value");
                    var valueObject =
                        Amaz.uploader.data.allFiles[
                            Amaz.uploader.data.allFiles.findIndex(
                                (x) => x.id === value
                            )
                        ];

                    elem.closest(".single_files").toggleAttr(
                        "data-selected",
                        "true",
                        "false"
                    );
                    if (!Amaz.uploader.data.multiple) {
                        elem.closest(".single_files")
                            .siblings()
                            .attr("data-selected", "false");
                    }
                    if (!Amaz.uploader.data.selectedFiles.includes(value)) {
                        if (!Amaz.uploader.data.multiple) {
                            Amaz.uploader.data.selectedFiles = [];
                            Amaz.uploader.data.selectedFilesObject = [];
                        }
                        Amaz.uploader.data.selectedFiles.push(value);
                        Amaz.uploader.data.selectedFilesObject.push(valueObject);
                    } else {
                        Amaz.uploader.data.selectedFiles = Amaz.uploader.data.selectedFiles.filter(
                            function (item) {
                                return item !== value;
                            }
                        );
                        Amaz.uploader.data.selectedFilesObject = Amaz.uploader.data.selectedFilesObject.filter(
                            function (item) {
                                return item !== valueObject;
                            }
                        );
                    }
                    Amaz.uploader.addSelectedValue();
                    Amaz.uploader.updateUploaderSelected();
                });
            });
        },
        showSelectedFiles: function () {
            $('[name="selected_only"]').on("change", function () {
                if ($(this).is(":checked")) {
                    Amaz.uploader.data.allFiles = Amaz.uploader.data.selectedFilesObject;
                    Amaz.uploader.updateUploaderFiles();
                } else {
                    Amaz.uploader.getAllUploads(
                        Amaz.data.appUrl + "/media-manager/get-files-modal",
                        $('[name="amaz_media_search"]').val(),
                        $('[name="Amaz_media_sort"]').val()
                    );
                }
            });
        },
        clearUploaderSelected: function () {
            $(".reset_selected").on("click", function (e) {
                e.preventDefault();
                Amaz.uploader.data.selectedFiles = [];
                Amaz.uploader.addSelectedValue();
                Amaz.uploader.addHiddenValue();
                Amaz.uploader.resetFilter();
                Amaz.uploader.updateUploaderSelected();
                Amaz.uploader.updateUploaderFiles();
            });
        },
        resetFilter: function () {
            $('[name="amaz_media_search"]').val("");
            $('[name="selected_only"]').prop("checked", false);
            $('[name="Amaz_media_sort"] option[value=newest]').prop(
                "selected",
                true
            );
            $('[name="Amaz_media_sort"]').niceSelect('update');
        },

        trigger: function (
            elem = null,
            from = "",
            type = "all",
            selected = "",
            multiple = false,
            callback = null
        ) {
            var elem = $(elem);
            var multiple = multiple;
            var type = type;
            var oldSelectedFiles = selected;
            if (oldSelectedFiles !== "") {
                Amaz.uploader.data.selectedFiles = oldSelectedFiles
                    .split(",")
                    .map(Number);

            } else {
                Amaz.uploader.data.selectedFiles = [];
            }
            if ("undefined" !== typeof type && type.length > 0) {
                Amaz.uploader.data.type = type;
            }
            if (multiple) {
                Amaz.uploader.data.multiple = true;
            }else{
                Amaz.uploader.data.multiple = false;
            }
            $('#pre-loader').removeClass('d-none');

            $.post(
                Amaz.data.appUrl + "/media-manager/get-modal",
                { _token: Amaz.data.csrf },
                function (data) {
                    $('#pre-loader').addClass('d-none');
                    $("#mediaManagerDiv").html(data);
                    $("#media_modal").modal("show");
                    $('#status').niceSelect();
                    Amaz.uploader.amazUppy();
                    Amaz.uploader.getAllUploads(
                        Amaz.data.appUrl + "/media-manager/get-files-modal",
                        null,
                        $('[name="Amaz_media_sort"]').val()
                    );

                    Amaz.uploader.updateUploaderSelected();
                    Amaz.uploader.clearUploaderSelected();
                    Amaz.uploader.sortUploaderFiles();
                    Amaz.uploader.searchUploaderFiles();
                    Amaz.uploader.showSelectedFiles();

                    $("#uploader_next_btn").on("click", function () {
                        if (Amaz.uploader.data.next_page_url != null) {
                            $('[name="aiz-show-selected"]').prop(
                                "checked",
                                false
                            );
                            Amaz.uploader.getAllUploads(
                                Amaz.uploader.data.next_page_url
                            );
                        }
                    });

                    $("#uploader_prev_btn").on("click", function () {
                        if (Amaz.uploader.data.prev_page_url != null) {
                            $('[name="aiz-show-selected"]').prop(
                                "checked",
                                false
                            );
                            Amaz.uploader.getAllUploads(
                                Amaz.uploader.data.prev_page_url
                            );
                        }
                    });

                    $(".aiz-uploader-search i").on("click", function () {
                        $(this).parent().toggleClass("open");
                    });

                    $('[data-toggle="amazUploaderAddSelected"]').on(
                        "click",
                        function () {
                            if($('.image_selected_files').val() == ''){
                                currentEl.length = 0;
                                document.cookie = "previousQuantity=" + 0;
                            }
                            if (from === "input") {
                                Amaz.uploader.inputSelectPreviewGenerate(elem);
                            } else if (from === "direct") {
                                callback(Amaz.uploader.data.selectedFiles);
                            }
                            $("#media_modal").modal("hide");
                            $('#pre-loader').removeClass('d-none');

                            @if(str_contains(url()->current(), '/products'))

                            currentEl.length = 0;
                            currentEl.push($('.image_selected_files').val().split(','));

                            valuesArray.length = 0;
                            valuesArray.push($('.image_selected_files').val().split(','));
                            currentEl[0].reverse();

                            @endif

                            @if(str_contains(url()->current(), 'products') && (str_contains(url()->current(), 'create')))
                                if(valuesArray.length === 0){
                                    for (let i = 1; i <= currentEl[0].length; i++){
                                        $('.images_alt').append('<div class="col-lg-12 val_' + i + '"><div class="primary_input mb-15"><label class="primary_input_label" for="">{{ __('common.image_alt') }} ' + i +'</label><input class="primary_input_field" name="images_alt[]" placeholder="{{ __('common.image_alt') }}" type="text" value=""></div></div>');
                                    }
                                }else{
                                    addImageAlts(currentEl[0].length);
                                }
                            @else

                                @if(str_contains(url()->current(), '/products'))
                                    addImageAlts(currentEl[0].length);
                                @endif
                            @endif
                        }
                    );
                }
            );
        },
        initForInput: function () {
            $(document).on("click",'[data-toggle="amazuploader"]', function (e) {
                if (e.detail === 1) {
                    var elem = $(this);
                    var multiple = elem.data("multiple");
                    var type = elem.data("type");
                    var oldSelectedFiles = elem.find(".selected_files").val();
                    multiple = !multiple ? "" : multiple;
                    type = !type ? "" : type;
                    oldSelectedFiles = !oldSelectedFiles? "": oldSelectedFiles;
                    Amaz.uploader.data.for_name = elem.data('name');
                    Amaz.uploader.trigger(
                        this,
                        "input",
                        type,
                        oldSelectedFiles,
                        multiple
                    );
                }
            });
        },
        inputSelectPreviewGenerate: function (elem) {
            var prev_data = elem.find(".selected_files").val();
            elem.find(".selected_files").val(Amaz.uploader.data.selectedFiles);
            elem.next(".product_image_all_div").html(null);

            if (Amaz.uploader.data.selectedFiles.length > 0) {
                $.post(
                    Amaz.data.appUrl + "/media-manager/get_media_by_id",
                    { _token: Amaz.data.csrf, ids: Amaz.uploader.data.selectedFiles, prev_ids: prev_data},
                    function (data) {
                        $('#pre-loader').addClass('d-none');

                        elem.next(".product_image_all_div").html(null);

                        if (data.length > 0) {
                            elem.find(".file_amount").attr('placeholder',Amaz.uploader.updateFileHtml(data));
                            for (var i = 0;i < data.length;i++) {
                                var thumb = "";
                                if (data[i].type === "image") {
                                    if(data[i].storage == 'local'){
                                        thumb = `<img id="ThumbnailImg" src="`+Amaz.data.fileBaseUrl+`${data[i].file_name}" alt="">`;
                                    }else{
                                        thumb = `<img id="ThumbnailImg" src="${data[i].file_name}" alt="">`;
                                    }
                                } else {
                                    thumb = '<i class="la la-file-text"></i>';
                                }

                                var html = `
                                    <div class="thumb_img_div" data-id="${data[i].id}">
                                        <div class="img_remove_btn">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        ${thumb}
                                        <input type="hidden" class="product_images_hidden" name="${Amaz.uploader.data.for_name}" value="${data[i].id}">
                                    </div>
                                `;

                                elem.next(".product_image_all_div").append(html);
                            }
                        } else {
                            elem.find(".file_amount").html('Choose File');
                        }
                });
            } else {
                elem.find(".file_amount").html('Choose File');
            }
        },
        previewGenerate: function(){
            $('[data-toggle="amazuploader"]').each(function () {
                var $this = $(this);
                var files = $this.find(".selected_files").val().split(",").map(Number);
                if(files != ""){
                    $.post(
                        Amaz.data.appUrl + "/media-manager/get_media_by_id",
                        { _token: Amaz.data.csrf, ids: files },
                        function (datas) {
                            Amaz.uploader.data.for_name = $this.data('name');
                            data = [];
                            files.forEach(function(key) {
                                var found = false;
                                datas = datas.filter(function(file) {
                                    if(!found && file.id == key) {
                                        data.push(file);
                                        found = true;
                                        return false;
                                    } else
                                        return true;
                                });
                            });
                            Amaz.uploader.data.selectedFilesObject = data;

                            if (data.length > 0) {
                                $this.next(".product_image_all_div").html(null);
                                $this.find(".file_amount").attr('placeholder',Amaz.uploader.updateFileHtml(data) );
                                for (var i = 0;i < data.length;i++) {
                                    var thumb = "";
                                if (data[i].type === "image") {
                                    if(data[i].storage == 'local'){
                                        thumb = `<img id="ThumbnailImg" src="`+Amaz.data.fileBaseUrl+`${data[i].file_name}" alt="">`;
                                    }else{
                                        thumb = `<img id="ThumbnailImg" src="${data[i].file_name}" alt="">`;
                                    }
                                } else {
                                    thumb = '<i class="la la-file-text"></i>';
                                }
                                var html = `
                                    <div class="thumb_img_div" data-id="${data[i].id}">
                                        <div class="img_remove_btn">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        ${thumb}
                                        <input type="hidden" name="${Amaz.uploader.data.for_name}" class="product_images_hidden" value="${data[i].id}">
                                    </div>
                                `;

                                $this.next(".product_image_all_div").append(html);
                                }
                            } else {
                                $this.find(".file_amount").html('Choose File');
                            }
                    });
                }
            });
        },
        removeAttachment: function () {
            $(document).on("click",'.img_remove_btn', function () {
                var value = $(this)
                    .closest(".thumb_img_div")
                    .data("id");
                var selected = $(this)
                    .closest(".product_image_all_div")
                    .prev('[data-toggle="amazuploader"]')
                    .find(".selected_files")
                    .val()
                    .split(",")
                    .map(Number);

                Amaz.uploader.removeInputValue(
                    value,
                    selected,
                    $(this)
                        .closest(".product_image_all_div")
                        .prev('[data-toggle="amazuploader"]')
                );
                $(this).closest(".thumb_img_div").remove();

                @if(str_contains(url()->current(), 'products') && (str_contains(url()->current(), 'edit') || str_contains(url()->current(), 'create')))
                    @if(str_contains(url()->current(), 'products') && (str_contains(url()->current(), 'create')))
                        for(let i = 1; i <= currentEl[0].length; i++){
                            if(currentEl[0][i-1] == value){
                                $('.images_alt .val_' + i).remove();
                            }
                        }

                        document.cookie = "previousQuantity=" + (selected.length - 1);
                    @else
                        for(let i = 1; i <= valuesArray[0].length; i++){
                            if(valuesArray[0][i-1] == value){
                                $('.images_alt .val_' + i).remove();
                            }
                        }
                        document.cookie = "previousQuantity=" + (selected.length - 1);

                    @endif
                @endif
            });
        },
        removeInputValue: function (id, array, elem) {
            var selected = array.filter(function (item) {
                return item !== id;
            });
            if (selected.length > 0) {
                $(elem)
                    .find(".file_amount")
                    .attr('placeholder',Amaz.uploader.updateFileHtml(selected));
            } else {
                elem.find(".file_amount").attr('placeholder','Choose File');
            }
            $(elem).find(".selected_files").val(selected);
        },
        sortImage:function(){
            $(".product_image_all_div").sortable({
                cursor: "move",
                connectWith: ".thumb_img_div",
                update:function(event, ui){
                    var imageids = [];
                    $(this).find('.product_images_hidden').each(function(id){
                        imageids.push($(this).val());
                    });
                    console.log(imageids.join(','))
                    $(this).prev('[data-toggle="amazuploader"]').find('.selected_files').val(imageids.join(','));
                }
            });
        },

    };

    Amaz.uploader.clearUploaderSelected();
    Amaz.uploader.initForInput();
    Amaz.uploader.removeAttachment();
    Amaz.uploader.sortImage();
    Amaz.uploader.previewGenerate();

</script>