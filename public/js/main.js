"use strict";

window.addEventListener('load', function () {
    // Show/hide sidebar
    var btnSidebar = document.querySelectorAll('.sidebar .btn-size-holder'),
        sidebar = document.querySelector('.sidebar');
    if (btnSidebar.length) {
        btnSidebar.forEach(function (btn) {
            btn.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                if (sidebar.classList.contains('active')) {
                    document.documentElement.style.setProperty('--sidebar-width', "64px");
                    setCookie('sidebar', 'small', {
                        secure: true,
                        'max-age': 3600 * 24 * 30
                    });
                } else {
                    document.documentElement.style.removeProperty('--sidebar-width');
                    deleteCookie('sidebar');
                }
            });
        });
    }
    if (getCookie('sidebar')) {
        sidebar.classList.add('active');
        document.documentElement.style.setProperty('--sidebar-width', "64px");
    }
    document.addEventListener('click', function (e) {
        var target = e.target;
        if (target.closest('.search-block') === null && searchBlock) {
            searchBlock.classList.remove('show-form');
        }
    });

    // Have pagination block
    if (document.querySelector('.pagination')) {
        document.documentElement.style.setProperty('--pagination-height', document.querySelector('.pagination').clientHeight + 'px');
    }

    // Page name block height
    if (document.querySelector('.page-name')) {
        document.documentElement.style.setProperty('--page-name-height', document.querySelector('.page-name').clientHeight + 'px');
    }

    // Open search
    var searchBlock = document.querySelector('.search-block'),
        searchFakeInput = document.querySelector('.search-block__result');
    if (searchBlock) {
        searchFakeInput.addEventListener('click', function (e) {
            var target = e.target;
            if (target.closest('.placeholder-item') === null) {
                searchBlock.classList.toggle('show-form');
            }
        });
    }

    // Show filters
    if (document.querySelectorAll('._js-show-filters').length) {
        document.querySelectorAll('._js-show-filters').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelector('.filters').classList.toggle('active');
                sidebar.classList.toggle('hide-btn');
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    document.documentElement.style.removeProperty('--sidebar-width');
                    deleteCookie('sidebar');
                }
                setTimeout(function () {
                    datepickerPositionInScroll();
                }, 500);
            });
        });
    }

    // Select default
    var selects = document.querySelectorAll('select');
    if (selects.length > 0) {
        selects.forEach(function (select) {
            if (select.querySelector('option[value="-1"]')) {
                var val = select.value;
                if (val === '-1') {
                    select.closest('.form-group').classList.add('show-placeholder');
                }
                select.addEventListener('change', function () {
                    var val = select.value;
                    if (val === '-1') {
                        select.closest('.form-group').classList.add('show-placeholder');
                    } else {
                        select.closest('.form-group').classList.remove('show-placeholder');
                    }
                });
            }
        });
    }
});

// File uploader
var fileInput = document.querySelector('.form-group.file input');
var fileCountUploaded = 0;
var maxFileCount = 1000;
var uploadedFileList = [];
if (fileInput) {
    fileInput.addEventListener("change", handleFiles, false);
}
function handleFiles() {
    var input = this;
    var fileList = this.files,
        formGroup = this.closest('.form-group'),
        helpBlock = formGroup.querySelector('.help-block');
    // console.log(input.files);
    var validation = validationFiles(fileList, ['jpg', 'jpeg', 'png'], 5000000);
    formGroup.classList.remove('has-error');
    if (!validation.status) {
        if (validation.text_status === 1) {
            helpBlock.innerHTML = helpBlock.dataset.errorFormat;
        } else if (validation.text_status === 2) {
            helpBlock.innerHTML = helpBlock.dataset.errorSize;
        }
        formGroup.classList.add('has-error');
    }
    if (validation.files.length > 0) {
        if (validation.files.length <= maxFileCount && fileCountUploaded <= maxFileCount && validation.files.length + fileCountUploaded <= maxFileCount) {
            Object.values(validation.files).forEach(function (file) {
                uploadedFileList.push(file);

                // var reader = new FileReader();
                // reader.addEventListener('loadend', function (e) {
                //     uploadedFileList.push(file);

                //     console.log(file, );
                // });
                // reader.readAsDataURL(file);
            });
            input.value = '';
            var newInput = input.cloneNode(true);
            input.replaceWith(newInput);
            input = newInput;
            input.addEventListener('change', handleFiles);
            fileCountUploaded += validation.files.length;
            if (fileCountUploaded < maxFileCount) {
                this.removeEventListener('change', handleFiles);
            }
            if (fileCountUploaded === maxFileCount) {
                formGroup.classList.add('_no-clickable');
            }
        } else {
            helpBlock.innerHTML = helpBlock.dataset.errorMaxCount;
            formGroup.classList.add('has-error');
        }
    } else {
        console.log('Photo not valid');
    }

    // setTimeout(() => {
    drawUploadedPhoto(uploadedFileList);
    // }, 2000)
}
function drawUploadedPhoto(uploadedFileList, input) {
    var wrapper = document.querySelector('.image-preview'),
        uploadedImages = wrapper.querySelectorAll('._uploaded-image'),
        form = wrapper.closest('form'),
        fileInputElement = document.querySelector('#file'),
        containerDataTransfer = new DataTransfer();
    form.addEventListener('submit', function (e) {
        e.preventDefault();
    });

    //wrapper.innerHTML = '';
    if (uploadedImages.length > 0) {
        uploadedImages.forEach(function (uploadedImage) {
            uploadedImage.remove();
        });
    }
    if (uploadedFileList.length > 0) {
        uploadedFileList.forEach(function (file, index) {
            var reader = new FileReader();
            reader.addEventListener('loadend', function (e) {
                // uploadedFileList.push(file);

                var block = document.createElement('div');
                block.className = 'img _uploaded-image';
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'icon-trash';
                btn.dataset.index = index;
                btn.addEventListener('click', removeImagePreview);
                var img = document.createElement('img');
                img.src = reader.result;
                block.insertAdjacentElement('beforeend', img);
                block.insertAdjacentElement('beforeend', btn);
                wrapper.insertAdjacentElement('beforeend', block);
            });
            reader.readAsDataURL(file);
            var blob = new Blob([file], {
                type: file.type
            });
            var file1 = new File([blob], file.name, {
                type: file.type,
                lastModified: file.lastModified,
                size: file.size,
                webkitRelativePath: file.webkitRelativePath,
                lastModifiedDate: file.lastModifiedDate
            });
            containerDataTransfer.items.add(file1);
            fileInputElement.files = containerDataTransfer.files;
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
    var max_file = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var valid = true,
        text_error = '',
        file_list = [],
        text_status = 0;
    for (var i = 0; i < files.length; i++) {
        var type = files[i].type,
            size = files[i].size,
            name = files[i].name;

        // console.log(files[i]);

        if (size <= max_size) {
            if (!format.includes(name.split('.').pop())) {
                valid = false;
                text_error = 'Inappropriate image format';
                text_status = 1;
                break;
            }
        } else {
            valid = false;
            text_error = 'The photo size is more than 5 mb';
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
        text_status: text_status
    };
}

// Password visible
if (document.querySelectorAll('.btn-password-visible').length > 0) {
    document.querySelectorAll('.btn-password-visible').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.closest('.form-group').querySelector('input');
            btn.classList.remove('btn-password-visible');
            if (input.type === 'password') {
                input.type = 'text';
                btn.classList.remove('icon-visible');
                btn.classList.add('icon-invisible', 'btn-password-visible');
            } else {
                input.type = 'password';
                btn.classList.add('icon-visible', 'btn-password-visible');
                btn.classList.remove('icon-invisible');
            }
        });
    });
}

// initial datepicker

var datepickerInputs = document.querySelectorAll('._js-datepicker');
if (datepickerInputs.length > 0) {
    datepickerInputs.forEach(function (input, index) {
        var container = document.createElement('div');
        container.className = 'datepicker-container';
        container.id = 'datepicker-container-' + index;
        document.body.insertAdjacentElement('beforeend', container);
        input.dataset.containerId = 'datepicker-container-' + index;
        new Datepicker(input, {
            container: '#datepicker-container-' + index,
            autohide: true,
            format: 'yyyy-mm-dd'
        });
        input.addEventListener('input', function () {
            input.value = '';
        });
        setTimeout(function () {
            datepickerPositionInScroll();
        }, 1000);
    });
}
if (document.querySelector('.custom-scrollbar')) {
    document.querySelectorAll('.custom-scrollbar').forEach(function (item) {
        if (item.querySelector('._js-datepicker')) {
            item.addEventListener('scroll', datepickerPositionInScroll);
        }
    });
}
window.addEventListener('scroll', datepickerPositionInScroll);
function datepickerPositionInScroll() {
    if (datepickerInputs.length > 0) {
        datepickerInputs.forEach(function (input) {
            var container = document.querySelector("#".concat(input.dataset.containerId));
            if (container) {
                container.style.setProperty('--left-datepicker', input.getBoundingClientRect().left + "px");
                container.style.setProperty('--top-datepicker', input.getBoundingClientRect().top + input.getBoundingClientRect().height + 4 + "px");
            }
        });
    }
}

// Swiper gallery

if (document.querySelector('.swiper-gallery')) {
    new Swiper('.swiper-gallery', {
        navigation: {
            nextEl: '.gallery-next',
            prevEl: '.gallery-prev'
        },
        pagination: {
            el: '.swiper-pagination',
            type: "fraction"
        }
    });
}

// Form validation before submit
var formsValidation = document.querySelectorAll('.js-form-validation');
if (formsValidation.length > 0) {
    formsValidation.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (validateForm(form)) {
                form.submit();
            }
        });
    });
}
if (document.querySelector('._js-button-validation')) {
    document.querySelectorAll('._js-button-validation').forEach(function (btn) {
        var form = btn.form;
        btn.addEventListener('click', function (e) {
            if (form) {
                e.preventDefault();
                form.querySelector('input[name="button"]').value = btn.value;
                if (validateForm(btn.form)) {
                    btn.form.submit();
                }
            }
        });
        window.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && event.target.closest('textarea') === null) {
                event.preventDefault();
                return false;
            }
        });
    });
}

/// Select 2

$(document).ready(function () {
    $('._js-select-2').select2({
        "language": {
            "noResults": function () {
                return "Результатів не знайдено";
            }
        },
    });

    // $('._js-select-2').on('change.select2', function(e) {
    //     console.log('Selecting: ' , e;
    // });

    $('._js-select-2').on('select2:select', function (event) {
        event.target.dispatchEvent(new Event("change"));
    });
});

// Btn required switcher
var btnRequiredSwitcher = document.querySelectorAll('.js-btn-required-switcher');
if (btnRequiredSwitcher.length > 0) {
    btnRequiredSwitcher.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.dataset.requiredSwitcher && btn.dataset.requiredSwitcher !== "") {
                var form = btn.closest('form'),
                    action;
                if (btn.getAttribute('form')) {
                    form = document.getElementById(btn.getAttribute('form'));
                }
                if (btn.dataset.requiredSwitcher === 'add') {
                    action = 'add';
                } else if (btn.dataset.requiredSwitcher === 'remove') {
                    action = 'remove';
                }
                if (form && action) {
                    toggleRequired(form, action);
                }
            }
        });
    });
}

// Remove image
var btnsRemoveImage = document.querySelectorAll('.js-remove-image');
if (btnsRemoveImage.length > 0) {
    btnsRemoveImage.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var action = btn.dataset.action;
            fetch(action, {
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(function (response) {
                return response.json();
            }).then(function (response) {
                if (response.success) {
                    btn.closest('.img').remove();
                }
            });
        });
    });
}


/// Chats
const btnOpenChat = document.querySelectorAll('.js-btn-open-chat');
if (btnOpenChat.length > 0) {

    // Open chat
    btnOpenChat.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const modal_node = document.querySelector(".js-modal-".concat(btn.dataset.modal)),
                form = modal_node.querySelector('#chat-form');



            const currentClaimId = btn.dataset.claimId;
            form.action = `/warranty-claims/${currentClaimId}/comments`;
            form.dataset.actionSend = `/warranty-claims/${currentClaimId}/comments`;

            modal_node.querySelector('#chat-form').dataset.claimId = currentClaimId;

            closeAllModal();
            fadeIn(overlayModal, 200);

            responseComments(currentClaimId).then(data => {
                drawComments(data, modal_node);

                fadeIn(modal_node, 300);
                bodyLock();
            }).catch(function (error) {
                fadeOut(overlayModal);
                closeAllModal();
                bodyUnlock();

                console.error(error);
            });
        })
    });

    async function responseComments(currentClaimId) {
        const response = await fetch(`/warranty-claims/${currentClaimId}/comments`);
        const data = await response.json();

        return data;
    }

    // Send message
    if (document.querySelector('#chat-form')) {
        document.querySelector('#chat-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const form = e.target,
                modal = form.closest('.js-modal'),
                inputFormGroup = form.querySelector('input[name="chat-text"]').closest('.form-group'),
                input = form.querySelector('input[name="chat-text"]'),
                text = input.value.trim(),
                currentClaimId = form.dataset.claimId,
                button = form.querySelector('button[type="submit"]')

            if (text === '') {
                inputFormGroup.classList.add('has-error');
                return;
            }
            inputFormGroup.classList.remove('has-error');
            button.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comment: text })
            })
                .then(response => response.json())
                .then(data => {
                    input.value = '';


                    responseComments(currentClaimId).then(data => {
                        drawComments(data, modal);
                    })
                    button.disabled = false;


                    if (form.classList.contains('edit-mode')) {
                        form.action = form.dataset.actionSend;
                        const oldBtnText = button.textContent;
                        button.innerHTML = button.dataset.text;
                        button.dataset.text = oldBtnText;
                        form.classList.remove('edit-mode');
                    }
                })
                .catch(function (error) {
                    button.disabled = false;
                    console.error(error);
                });
        })
    }
}

function drawComments(comments, modal) {
    const autjUserId = document.querySelector('input[name="auth-user-id"]').value,
        modalBody = modal.querySelector('.chat-main__wrapper');


    modalBody.innerHTML = '';

    comments.forEach(comment => {
        const commentHtml = `
            <div class="message ${(+autjUserId === comment.user_id) ? 'sender' : ''}">
                <div class="message-controls">
                    <button type="button" class="btn-delete"></button>
                    <ul class="controls-list">
                        <li>
                            <button type="button" class="icon-edit js-edit-msg" data-action="/warranty-claims/${comment.warranty_claim_id}/comments/update/${comment.id}/">Редагувати</button>
                        </li>
                        <li>
                            <button type="button" class="icon-trash js-del-msg" data-action="/warranty-claims/${comment.warranty_claim_id}/comments/delete/${comment.id}/">Видалити</button>
                        </li>
                    </ul>
                </div>

                <p class="message-author">${comment.user_name} (${(+autjUserId === comment.user_id) ? 'Ви' : 'Менеджер'})</p>
                <div class="message-text">
                    ${comment.comment}
                </div>
                <div class="message-date">${new Date(comment.created_at).toLocaleString()}</div>
            </div>
        `;
        modalBody.insertAdjacentHTML('beforeend', commentHtml);
    });
}


// Delete and edit message 
document.documentElement.addEventListener('click', (e) => {
    const target = e.target;


    // Delete message
    if (target.closest('.js-del-msg')) {
        const btn = target.closest('.js-del-msg'),
            action = btn.dataset.action;

        fetch(action, {
            method: 'delete',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(function (response) {
            return response.json();
        }).then(function (response) {
            if (response.success) {
                btn.closest('.message').remove();
            }
        });
    }



    // Edit message
    if (target.closest('.js-edit-msg')) {
        const btn = target.closest('.js-edit-msg'),
            action = btn.dataset.action;

        const form = btn.closest('.js-modal').querySelector('#chat-form'),
            input = form.querySelector('input[name="chat-text"]'),
            msg = btn.closest('.message').querySelector('.message-text').textContent.trim(),
            button = form.querySelector('button[type="submit"]')

        form.action = action;
        input.value = msg;
        const oldBtnText = button.textContent;
        button.innerHTML = button.dataset.text;
        button.dataset.text = oldBtnText;
        form.classList.add('edit-mode');
    }

})
