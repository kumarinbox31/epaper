
var CSp646ae1131f655 = {
    toUrlParams: function (obj) {
        var str = "";
        for (var key in obj) {
            if (str != "") {
                str += "&";
            }
            str += key + "=" + encodeURIComponent(obj[key]);
        }
        return str;
    },
    intialState: false,
    selection: {},
    init: function () {
        var thisobj = this;
        $(".btnCropp646ae1131f655").hide();
        $(".formSharep646ae1131f655").dialog({
            autoOpen: false,
            width: 300,
            modal: true,
            open: function () {
                Cropping = true;
            },
            close: function () {
                Cropping = false;
            }
        });
        $(".btnClipp646ae1131f655").on("click", function () {
            Cropping = true;

            thisobj.cropperStart();
        });
        $(document).on("click", ".btnCropp646ae1131f655", function () {
            Cropping = false;
            thisobj.cropperEnd();
            $(".btnCancelCropp646ae1131f655").remove();
            $(".btnCropp646ae1131f655").remove();
        });
        $(document).on("click", ".btnCancelCropp646ae1131f655", function () {
            Cropping = false;
            $(".btnCancelCropp646ae1131f655").remove();
            $(".btnCropp646ae1131f655").remove();

            var $image = $('.crop-image');
            $image.cropper('destroy');
            thisobj.initialState = false;
            $("#maparea, .epaper-page-viewer").show();
            $(".crop-image").addClass("d-none");

        });
    },
    cropperStart: function () {
        var $image = $('.crop-image');
        if (this.initialState) {
            $image.cropper('destroy');
            this.initialState = false;

            $(".btnCropp646ae1131f655").hide();
            $(".btnCancelCropp646ae1131f655").hide();

            $("#maparea, .epaper-page-viewer").show();

            $(".crop-image").addClass("d-none");
        } else {
            this.initialState = true;
            $(".btnCropp646ae1131f655").show();
            $(".btnCancelCropp646ae1131f655").show();

            $("#maparea, .epaper-page-viewer").hide();
            $(".crop-image").removeClass("d-none");

            var buttons = '<button type="button" style="position:absolute; margin-top:-30px; z-index:1000" class="btn btn-light btn-sm btnCrop btnCropp646ae1131f655">' +
                '<i class="fas fa-share-alt"></i> Share</button>' +
                '<button type="button" style="position:absolute; margin-top:-30px; margin-left:70px; z-index:1000" class="btn btn-danger btn-sm btnCancelCrop btnCancelCropp646ae1131f655">' +
                '<i class="fas fa-times"></i> Cancel</button>';


            $(".crop-image").before(buttons);

            var thisobj = this;
            $image.cropper({
                zoomable: false,
                autoCropArea: 0.4,
                dragMode: 'none',
                crop: function (event) {
                    thisobj.selection = event.detail;

                    //The following lines stick the cropping buttons with box
                    $(".btnCropp646ae1131f655, .btnCancelCropp646ae1131f655").css("transform", $(".cropper-crop-box").css("transform"));

                    //$(".cropper-crop-box .btnCrop, .cropper-crop-box .btnCancelCrop").remove();
                    //$(".cropper-crop-box").append($(".btnCropOuterp646ae1131f655").html());
                }
            });
            var cropper = $image.data('cropper');
        }
    },
    cropperEnd: function () {
        var data = this.selection;
        let state = currentState.getState();
        data.pg_id = state.pg_id;
        //data.pg_id = 1536;
        var thisobj = this;
        this.initialState = false;
        $.ajax({
            type: "post", dataType: "json", data: data,
            url: "/epaper/default/create-clip",
            success: function (r) {
                if (ajaxValidate(r)) {
                    $(".share-clip-icons").html(thisobj.generateSocialIcons(r.data.url, state.title));
                    $(".formSharep646ae1131f655").dialog("open");
                    $(".formSharep646ae1131f655 [name=clip_url]").val(r.data.url);
                    $(".formSharep646ae1131f655 img").prop("src", r.data.img);

                    $(".formSharep646ae1131f655 a.download").prop("href", r.data.img + "?download");

                    var $image = $('.crop-image');
                    $image.cropper('destroy');
                    this.initialState = false;
                    //$(".btnCropp646ae1131f655").hide();
                    $("#maparea, .epaper-page-viewer").show();
                    $(".crop-image").addClass("d-none");
                    window.dispatchEvent(new CustomEvent("onClipShareDialogOpens", { detail: r.data }));
                }
            }
        })
    },
    generateSocialIcons: function (url, title) {
        var social = { "facebook": "<a target='_blank' style='display:inline-block; background-color:#546CA3; padding:7px 15px 7px 15px; color:white' href='https:\/\/www.facebook.com\/sharer\/sharer.php?u={link}'><i class='fab fa-facebook'><\/i><\/a>", "twitter": "<a target='_blank' style='display:inline-block; background-color:#5BB0E3; padding:7px 15px 7px 15px; color:white' href='https:\/\/twitter.com\/intent\/tweet?text={title}+{link}'><i class='fab fa-twitter'><\/i><\/a>", "whatsapp": "<a style='display:inline-block; background-color:#6CBA5E; padding:7px 15px 7px 15px; color:white'  onclick='popupWindow('WA_TEMPLATE'); return false;' data-action='share\/whatsapp\/share' target='_blank'  href='WA_TEMPLATE'><i class='fab fa-whatsapp'><\/i><\/a>", "email": "<a style='display:inline-block; background-color:#E13894; padding:7px 15px 7px 15px; color:white' target='_blank' href='mailto:?Subject={title}&body={link}'><i class='fas fa-envelope'><\/i><\/a><br \/>", "link": "<a href='{link}' target='_blank' style='margin-top:5px; display:inline-block; font-size:16px; background-color:gray; padding:7px 10px 7px 10px;   color:white'><i class='fas fa-external-link-alt'><\/i> Open<\/a>", "download": "<a href='#' class='download' target='_blank' style='margin-top:5px; display:inline-block; font-size:16px; background-color:#546CA3; padding:7px 10px 7px 10px;   color:white'><i class='fas fa-download'><\/i> Download<\/a>" };
        var str = "";

        $.each(social, function (k, v) {
            if (k == "whatsapp") {
                if (isMobile.any()) {
                    v = v.replace("WA_TEMPLATE", "whatsapp://send?text={link}").replace("WA_TEMPLATE", "whatsapp://send?text={link}");
                } else {
                    v = v.replace("WA_TEMPLATE", "https://api.whatsapp.com/send?text={link}").replace("WA_TEMPLATE", "https://api.whatsapp.com/send?text={link}");
                }
            }
            str += v.replace("{link}", url).replace("{link}", url).replace("{title}", title);


        })
        return str;
    }
};
CSp646ae1131f655.init();
