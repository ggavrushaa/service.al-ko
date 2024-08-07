"use strict";

window.addEventListener("load", function () {
    // Show/hide sidebar
    var btnSidebar = document.querySelectorAll(".sidebar .btn-size-holder"),
        sidebar = document.querySelector(".sidebar");
    if (btnSidebar.length) {
        btnSidebar.forEach(function (btn) {
            btn.addEventListener("click", function () {
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    document.documentElement.style.setProperty(
                        "--sidebar-width",
                        "64px"
                    );
                    setCookie("sidebar", "small", {
                        secure: true,
                        "max-age": 3600 * 24 * 30,
                    });
                } else {
                    document.documentElement.style.removeProperty(
                        "--sidebar-width"
                    );
                    deleteCookie("sidebar");
                }
            });
        });
    }
    if (getCookie("sidebar")) {
        sidebar.classList.add("active");
        document.documentElement.style.setProperty("--sidebar-width", "64px");
    }
    document.addEventListener("click", function (e) {
        var target = e.target;
        if (target.closest(".search-block") === null && searchBlock) {
            searchBlock.classList.remove("show-form");
        }
    });

    // Have pagination block
    if (document.querySelector(".pagination")) {
        document.documentElement.style.setProperty(
            "--pagination-height",
            document.querySelector(".pagination").clientHeight + "px"
        );
    }

    // Page name block height
    if (document.querySelector(".page-name")) {
        document.documentElement.style.setProperty(
            "--page-name-height",
            document.querySelector(".page-name").clientHeight + "px"
        );
    }

    // Open search
    var searchBlock = document.querySelector(".search-block"),
        searchFakeInput = document.querySelector(".search-block__result");
    if (searchBlock) {
        searchFakeInput.addEventListener("click", function (e) {
            var target = e.target;
            if (target.closest(".placeholder-item") === null) {
                searchBlock.classList.toggle("show-form");
            }
        });
    }

    // Show filters
    if (document.querySelectorAll("._js-show-filters").length) {
        document.querySelectorAll("._js-show-filters").forEach(function (btn) {
            btn.addEventListener("click", function () {
                document.querySelector(".filters").classList.toggle("active");
                sidebar.classList.toggle("hide-btn");
                if (sidebar.classList.contains("active")) {
                    sidebar.classList.remove("active");
                    document.documentElement.style.removeProperty(
                        "--sidebar-width"
                    );
                    deleteCookie("sidebar");
                }
                setTimeout(function () {
                    datepickerPositionInScroll();
                }, 500);
            });
        });
    }

    // Select default
    var selects = document.querySelectorAll("select");
    if (selects.length > 0) {
        selects.forEach(function (select) {
            if (select.querySelector('option[value="-1"]')) {
                var val = select.value;
                if (val === "-1") {
                    select
                        .closest(".form-group")
                        .classList.add("show-placeholder");
                }
                select.addEventListener("change", function () {
                    var val = select.value;
                    if (val === "-1") {
                        select
                            .closest(".form-group")
                            .classList.add("show-placeholder");
                    } else {
                        select
                            .closest(".form-group")
                            .classList.remove("show-placeholder");
                    }
                });
            }
        });
    }
});

// File uploader
var fileInput = document.querySelector(".form-group.file input");
var fileCountUploaded = 0;
var maxFileCount = 1000;
var uploadedFileList = [];
if (fileInput) {
    fileInput.addEventListener("change", handleFiles, false);
}
function handleFiles() {
    var input = this;
    var fileList = this.files,
        formGroup = this.closest(".form-group"),
        helpBlock = formGroup.querySelector(".help-block");
    console.log(input.files);

    var validation = validationFiles(fileList, ["jpg", "jpeg", "png"], 5000000);
    formGroup.classList.remove("has-error");
    if (!validation.status) {
        if (validation.text_status === 1) {
            helpBlock.innerHTML = helpBlock.dataset.errorFormat;
        } else if (validation.text_status === 2) {
            helpBlock.innerHTML = helpBlock.dataset.errorSize;
        }
        formGroup.classList.add("has-error");
    }
    if (validation.files.length > 0) {
        if (
            validation.files.length <= maxFileCount &&
            fileCountUploaded <= maxFileCount &&
            validation.files.length + fileCountUploaded <= maxFileCount
        ) {
            Object.values(validation.files).forEach(function (file) {
                uploadedFileList.push(file);

                // var reader = new FileReader();
                // reader.addEventListener('loadend', function (e) {
                //     uploadedFileList.push(file);

                //     console.log(file, );
                // });
                // reader.readAsDataURL(file);
            });
            // input.value = '';
            var newInput = input.cloneNode(false);
            input.replaceWith(newInput);
            input = newInput;
            input.addEventListener("change", handleFiles);
            fileCountUploaded += validation.files.length;
            if (fileCountUploaded < maxFileCount) {
                this.removeEventListener("change", handleFiles);
            }
            if (fileCountUploaded === maxFileCount) {
                formGroup.classList.add("_no-clickable");
            }
        } else {
            helpBlock.innerHTML = helpBlock.dataset.errorMaxCount;
            formGroup.classList.add("has-error");
        }
    } else {
        console.log("Photo not valid");
    }

    // setTimeout(() => {
    drawUploadedPhoto(uploadedFileList);
    // }, 2000)
}
function drawUploadedPhoto(uploadedFileList, input) {
    var wrapper = document.querySelector(".image-preview"),
        form = wrapper.closest("form");
    wrapper.innerHTML = "";
    console.log(form);
    if (uploadedFileList.length > 0) {
        uploadedFileList.forEach(function (file, index) {
            var reader = new FileReader();
            reader.addEventListener("loadend", function (e) {
                // uploadedFileList.push(file);

                var block = document.createElement("div");
                block.className = "img";
                var btn = document.createElement("button");
                btn.type = "button";
                btn.className = "icon-trash";
                btn.dataset.index = index;
                btn.addEventListener("click", removeImagePreview);
                var img = document.createElement("img");
                img.src = reader.result;
                block.insertAdjacentElement("beforeend", img);
                block.insertAdjacentElement("beforeend", btn);
                wrapper.insertAdjacentElement("beforeend", block);
            });
            reader.readAsDataURL(file);
            if (form) {
                var formData = new FormData(form);
                var blob = new Blob([file], {
                    type: file.type,
                });
                formData.append("files[]", blob, file.name);
                for (var pair of formData.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]); 
                }
            }
        });
    }
    function removeImagePreview() {
        var index = this.dataset.index;
        if (index) {
            uploadedFileList.splice(index, 1);
            drawUploadedPhoto(uploadedFileList);
        }
    }
}
function validationFiles(files, format, max_size) {
    var max_file =
        arguments.length > 3 && arguments[3] !== undefined
            ? arguments[3]
            : null;
    var valid = true,
        text_error = "",
        file_list = [],
        text_status = 0;
    for (var i = 0; i < files.length; i++) {
        var type = files[i].type,
            size = files[i].size,
            name = files[i].name;

        // console.log(files[i]);

        if (size <= max_size) {
            if (!format.includes(name.split(".").pop())) {
                valid = false;
                text_error = "Inappropriate image format";
                text_status = 1;
                break;
            }
        } else {
            valid = false;
            text_error = "The photo size is more than 5 mb";
            text_status = 2;
            break;
        }
        file_list.push(files[i]);
    }
    if (max_file !== null) {
        if (files.length > max_file) {
            file_list.splice(max_file, files.length - max_file);
        }
    }
    return {
        status: valid,
        text_error: text_error,
        files: file_list,
        text_status: text_status,
    };
}

// Password visible
if (document.querySelectorAll(".btn-password-visible").length > 0) {
    document.querySelectorAll(".btn-password-visible").forEach(function (btn) {
        btn.addEventListener("click", function () {
            var input = btn.closest(".form-group").querySelector("input");
            btn.classList.remove("btn-password-visible");
            if (input.type === "password") {
                input.type = "text";
                btn.classList.remove("icon-visible");
                btn.classList.add("icon-invisible", "btn-password-visible");
            } else {
                input.type = "password";
                btn.classList.add("icon-visible", "btn-password-visible");
                btn.classList.remove("icon-invisible");
            }
        });
    });
}

// initial datepicker

var datepickerInputs = document.querySelectorAll("._js-datepicker");
if (datepickerInputs.length > 0) {
    datepickerInputs.forEach(function (input, index) {
        var container = document.createElement("div");
        container.className = "datepicker-container";
        container.id = "datepicker-container-" + index;
        document.body.insertAdjacentElement("beforeend", container);
        input.dataset.containerId = "datepicker-container-" + index;
        new Datepicker(input, {
            container: "#datepicker-container-" + index,
            autohide: true,
        });
        input.addEventListener("input", function () {
            input.value = "";
        });
        setTimeout(function () {
            datepickerPositionInScroll();
        }, 1000);
    });
}
if (document.querySelector(".custom-scrollbar")) {
    document.querySelectorAll(".custom-scrollbar").forEach(function (item) {
        if (item.querySelector("._js-datepicker")) {
            item.addEventListener("scroll", datepickerPositionInScroll);
        }
    });
}
window.addEventListener("scroll", datepickerPositionInScroll);
function datepickerPositionInScroll() {
    console.log("ddd");
    if (datepickerInputs.length > 0) {
        datepickerInputs.forEach(function (input) {
            var container = document.querySelector(
                "#".concat(input.dataset.containerId)
            );
            if (container) {
                container.style.setProperty(
                    "--left-datepicker",
                    input.getBoundingClientRect().left + "px"
                );
                container.style.setProperty(
                    "--top-datepicker",
                    input.getBoundingClientRect().top +
                        input.getBoundingClientRect().height +
                        4 +
                        "px"
                );
            }
        });
    }
}

// Swiper gallery

if (document.querySelector(".swiper-gallery")) {
    new Swiper(".swiper-gallery", {
        navigation: {
            nextEl: ".gallery-next",
            prevEl: ".gallery-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            type: "fraction",
        },
    });
}
