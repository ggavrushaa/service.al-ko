<x-layouts.base>
    <div class="main" id="main">
        <div class="page-warranty-create">
            <div class="page-name sticky">
                <ul class="fake-breadcrumb">
                    <li>
                        <h1><a href="{{ route('app.warranty.edit', $warrantyClaim->id) }}">Гарантійна заява </a></h1>
                    </li>
                    <li>
                        Акт технічної експертизи
                    </li>
                </ul>
                <div class="btns">
                    @if ($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::review && (auth()->user()->role_id === 2 || auth()->user()->role_id === 3))
                        <button type="submit" class="btn-primary btn-blue _js-button-validation" form="form-create" value="approve">Затвердити</button>
                        <button type="submit" class="btn-border btn-blue _js-button-validation" form="form-create" value='save'>Зберегти</button>
                        <button type="submit" class="btn-border btn-red _js-button-validation" form="form-create" value="save-exit">Зберегти і Вийти</button>
                    @elseif ($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved)
                        <span class="btn-link btn-green text-only">Затверджено</span>
                    @endif
                </div>
            </div>

            <form action="{{ route('technical-conclusions.update', $warrantyClaim->id) }}" id="form-create" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-lists">
                    <div class="card-content card-form">
                        <p class="card-title">Загальна інформація</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="article">Номер документу</label>
                                <input type="text" id="article" value="{{ $warrantyClaim->number }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="document_date">Дата документу</label>
                                <input type="text" id="document_date" value="{{ $warrantyClaim->created_at }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="responsible_person">Відповідальний</label>
                                <input type="text" id="responsible_person" value="{{ $autor->full_name_ru ?? 'Не вказано' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="base_document">Документ-підстава</label>
                                <div class="input-wrapper">
                                    <input type="text" id="base_document" value="{{ $warrantyClaim->id }}" readonly>
                                    <a href="{{ route('app.warranty.edit', $warrantyClaim->id) }}" class="icon-link-external text-blue"></a>
                                </div>
                            </div>
                        </div>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="customer_name">ПІБ покупця</label>
                                <input type="text" id="customer_name" value="{{ $warrantyClaim->client_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="contact_phone">Контактний телефон</label>
                                <input type="text" id="contact_phone" value="{{ $warrantyClaim->client_phone }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="article_number">Артикул</label>
                                <input type="text" id="article_number" value="{{ $warrantyClaim->product_article }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="product_name">Назва виробу</label>
                                <input type="text" id="product_name" value="{{ $warrantyClaim->product_name }}" readonly>
                            </div>
                        </div>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="place_of_sale">Місце продажу</label>
                                <input type="text" id="place_of_sale" value="{{ $warrantyClaim->point_of_sale ?? 'Не вказно' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="sale_date">Дата продажу</label>
                                <input type="text" id="sale_date" value="{{ $warrantyClaim->date_of_sale }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="service_center_date">Дата звернення в сервісний центр</label>
                                <input type="text" id="service_center_date" value="{{ $warrantyClaim->date_of_claim }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="receipt_number">Номер квитанції сервісного центру</label>
                                <input type="text" id="receipt_number" value="{{ $warrantyClaim->receipt_number }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Технічна інформація</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="factory_number">Заводський номер</label>
                                <input type="text" id="factory_number" value="{{ $warrantyClaim->factory_number }}" readonly>
                            </div>
                            <div class="form-group required default-select" data-valid="vanilla-select">
                                <label for="defect_code">Код дефекту</label>
                                <select name="defect_code" id="defect_code" required @if($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved) disabled @endif>
                                    <option value="-1">Виберіть код дефекту</option>
                                    @foreach($defectCodes[0] ?? [] as $folder)
                                        <optgroup label="{{ $folder->name }}">
                                            @foreach($defectCodes[$folder->code_1C] ?? [] as $subCode)
                                                <option value="{{ $subCode->id }}" @if(isset($conclusion) && $conclusion->defect_code == $subCode->id) selected @endif>{{ $subCode->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group required default-select" data-valid="vanilla-select">
                                <label for="symptom_code">Код симптому</label>
                                <select name="symptom_code" id="symptom_code" required @if($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved) disabled @endif>
                                    <option value="-1">Виберіть код симптому</option>
                                    @foreach($symptomCodes[0] ?? [] as $folder)
                                        <optgroup label="{{ $folder->name }}">
                                            @foreach($symptomCodes[$folder->code_1C] ?? [] as $subCode)
                                                <option value="{{ $subCode->id }}" @if(isset($conclusion) && $conclusion->symptom_code == $subCode->id) selected @endif>{{ $subCode->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group default-select">
                                <label for="appeal_type">Тип звернення</label>
                                <select name="appeal_type" id="appeal_type" @if($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved) disabled @endif>
                                    @foreach($appealTypes as $type)
                                    <option value="{{ $type->value }}" @if(isset($conclusion) && $conclusion->appeal_type == $type) selected @endif>
                                        {{ $type->name() }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inputs-group one-row">
                            <div class="form-group required" data-valid="empty">
                                <div class="form-group__top">
                                    <label for="conclusion">Висновок</label>
                                </div>
                                <textarea name="conclusion" id="conclusion" placeholder="Висновок" rows="3" @if($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved) readonly @endif>{{ $conclusion->conclusion ?? '' }}</textarea>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>

                            
                            <div class="form-group required" data-valid="empty">

                                <div class="form-group__top">
                                    <label for="resolution" style="text-wrap: nowrap;">Резолюція</label>

                                    <div class="form-group default-select show-placeholder">
                                        <select class="select-template" name="resolution" id=""  @if($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved) disabled @endif>
                                            <option value="-1" @if(!isset($conclusion) || $conclusion->resolution === null || $conclusion->resolution == -1) selected @endif>Оберіть шаблон</option>
                                            @foreach($resolutionTemplates as $template)
                                            <option data-description="{{ $template->description }}" value="{{ $template->id }}" @if(isset($conclusion) && $conclusion->resolution == $template->id) selected @endif>
                                                {{ $template->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <textarea name="resolution" id="resolution" placeholder="Резолюція" rows="3" @if($warrantyClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved) readonly @endif>{{ $conclusion->resolution ?? '' }}</textarea>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden" name="button">
            </form>
        </div>
    </div>

    <div class="modal-overlay"></div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        /* z-index: 10; */
        transition: opacity 0.4s;
    }
</style>

    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
    <script src="/cdn/js/swiper-bundle.min.js" ></script>
    <script src="/cdn/js/popper.min.js" ></script>
    <script src="/cdn/js/tippy-bundle.umd.min.js"></script>
    <script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
    <!--<script src="/cdn/js/custom-select.js"></script>-->
    <script src="/js/components.js?v=002"></script>
    <script src="/js/main.js?v=002"></script>


    <!-- шаблони резолюцій -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            /// New 
            if (document.querySelector('.select-template')) {
                document.querySelector('.select-template').addEventListener('change', (e) => {
                    const select = e.target,
                        input = e.target.closest('.form-group:not(.default-select)').querySelector('textarea');
                    
                    if(select.value === "-1"){
                        input.value = '';
                        return false;
                    }
                    input.value = select.options[select.selectedIndex].dataset.description;
                })
            }



            const showTemplateModalButton = document.querySelector('.btn-copy._js-btn-show-template-modal');
            const templateModal = document.querySelector('.js-modal-template');
            const templateModalBody = templateModal ? templateModal.querySelector('.manager-body') : null;
            const closeTemplateModalButton = templateModal ? templateModal.querySelector('.btn-close') : null;
            const searchTemplateInput = templateModal ? templateModal.querySelector('input[name="template-search"]') : null;
            const selectTemplateButton = templateModal ? templateModal.querySelector('.select-template-btn') : null;
            const modalOverlay = document.querySelector('.modal-overlay');
            let templatesList = [];

            if (!showTemplateModalButton || !templateModal || !templateModalBody || !closeTemplateModalButton || !searchTemplateInput || !selectTemplateButton || !modalOverlay) {
                console.error('One or more elements are missing:', { showTemplateModalButton, templateModal, templateModalBody, closeTemplateModalButton, searchTemplateInput, selectTemplateButton, modalOverlay });
                return;
            }

            function fadeIn(element) {
                if (!element) return;
                element.style.display = 'block';
                element.style.opacity = 0;
                let last = +new Date();
                const tick = function () {
                    element.style.opacity = +element.style.opacity + (new Date() - last) / 400;
                    last = +new Date();
                    if (+element.style.opacity < 1) {
                        (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
                    }
                };
                tick();
            }

            function fadeOut(element, callback) {
                if (!element) return;
                element.style.opacity = 1;
                let last = +new Date();
                const tick = function () {
                    element.style.opacity = +element.style.opacity - (new Date() - last) / 400;
                    last = +new Date();
                    if (+element.style.opacity > 0) {
                        (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
                    } else {
                        element.style.display = 'none';
                        if (callback) callback();
                    }
                };
                tick();
            }

            showTemplateModalButton.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                fetch('{{ route('resolution.list') }}')
                    .then(response => response.json())
                    .then(data => {
                        templatesList = data;
                        displayTemplates(templatesList);
                        fadeIn(templateModal);
                        fadeIn(modalOverlay);
                    })
                    .catch(error => console.error('Error fetching templates:', error));
            });

            searchTemplateInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                const filteredTemplates = templatesList.filter(template => 
                    template.name.toLowerCase().includes(query)
                );
                displayTemplates(filteredTemplates);
            });

            closeTemplateModalButton.addEventListener('click', function () {
                fadeOut(templateModal, function () {
                    templateModal.classList.remove('open');
                });
                fadeOut(modalOverlay);
            });

            modalOverlay.addEventListener('click', function () {
                fadeOut(templateModal, function () {
                    templateModal.classList.remove('open');
                });
                fadeOut(modalOverlay);
            });

            selectTemplateButton.addEventListener('click', function () {
                const selectedRadio = templateModalBody.querySelector('input[type="radio"][name="template"]:checked');
                if (!selectedRadio) {
                    alert('Виберіть шаблон');
                    return;
                }
                const selectedTemplateId = selectedRadio.value;
                console.log('Selected Template ID:', selectedTemplateId);

                const selectedTemplate = templatesList.find(template => template.id == selectedTemplateId);
                if (selectedTemplate) {
                    document.getElementById('resolution').value = selectedTemplate.description;
                }

                fadeOut(templateModal, function () {
                    templateModal.classList.remove('open');
                });
                fadeOut(modalOverlay);
            });

            function displayTemplates(templates) {
                templateModalBody.innerHTML = '';
                templates.forEach(template => {
                    const templateRow = `
                        <div class="form-group radio">
                            <input type="radio" id="template-${template.id}" name="template" value="${template.id}">
                            <label for="template-${template.id}">${template.name}</label>
                        </div>
                    `;
                    templateModalBody.insertAdjacentHTML('beforeend', templateRow);
                });
            }
        });

    </script>

</x-layouts.base>
