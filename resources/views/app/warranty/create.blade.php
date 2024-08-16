<x-layouts.base>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="main" id="main">
        <div class="page-warranty-create">
            <div class="page-name sticky">
                <h1>Гарантійна заява</h1>
                <div class="btns">
                    @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::new)
                        <button type="submit" class="btn-primary btn-blue" form="send-to-save">Створити</button>
                    @endif
                </div>
            </div>

            <div class="modal-overlay"></div>

            <!--         modal switch manager -->
            <div class="modal modal-manager js-modal js-modal-switch-manager">
                <button type="button" class="icon-close-fill btn-close _js-btn-close-modal" id="modal-close"></button>
                <div class="modal-content">
                    <div class="manager-header">
                        <p class="modal-title">Оберіть менеджера</p>
                        <div class="form-group">
                            <span class="icon-search"></span>
                            <input type="text" placeholder="пошук" name="manager-search">
                        </div>
                    </div>
                    <div class="manager-body custom-scrollbar">

                    </div>
                    <div class="manager-footer">
                        <button type="button" class="btn-primary btn-blue change-manager-btn">Переназначити менеджера
                        </button>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $isDisabled = session('status') ? 'disabled' : '';
                $technicalConcluison = session('conclusion');
            @endphp

            <form action="{{ route('warranty-claims.save') }}" id="send-to-save" method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="card-lists">
                    <div class="card-content card-form">
                        <p class="card-title">Загальна інформація</p>
                        <div class="inputs-group one-row">
                            <div class="form-group wider-width">
                                <label for="number-doc">Номер</label>
                                <input type="text" readonly>
                            </div>
                            <input type="hidden" name="number" id="number-doc" value="{{ $documentNumber }}">
                            <div class="form-group widest-width">
                                <label for="date-doc">Дата</label>
                                <input type="text" readonly>
                            </div>
                            <input type="hidden" name="date" id="date-doc"
                                   value="{{ $currentClaim->date ?? date('Y-m-d')}}">
                            <input type="hidden" name="id" id="claim-id" value="{{ $currentClaim->id }}">
                            <input type="hidden" name="autor" id="autor-id" value="{{ auth()->user()->id }}">
                            <div class="form-group">
                                <label for="autor-name">Відповідальний</label>
                                <input type="text" id="autor-name"
                                       value="{{ $currentClaim->manager->first_name_ru ?? 'Не вказано' }}" readonly>
                            </div>

                            <div class="form-group required default-select show-placeholder"
                                 data-valid="vanilla-select">
                                <label for="service-center">Сервісний центр</label>
                                <select name="service_partner" id="service-center" required
                                        @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                                    <option value="-1">Виберіть сервісний центр</option>
                                    @foreach($serviceCenters as $center)
                                        <option
                                            value="{{ $center->id }}" {{ old('service_partner', $currentClaim['service_partner'] ?? $currentClaim->service_partner) == $center->id ? 'selected' : '' }}>
                                            {{ $center->full_name_ru }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group small-width">
                                <label for="service-contract">Договір сервісу</label>
                                <select name="service_contract" id="service-contract" class="form-control"
                                        @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                                    <option value="{{ old('service_contract', $defaultContract->id ?? '') }}">
                                        {{ old('service_contract', $defaultContract->number ?? 'Виберіть договір сервісу') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="display-grid col-2 gap-8">
                        <div class="card-content card-form">
                            <p class="card-title">Дані покупця</p>
                            <div class="inputs-group one-row">
                                <div class="form-group">
                                    <label for="buyer-name">ПІБ покупця</label>
                                    <input type="text" name="client_name" id="buyer-name"
                                           value="{{$currentClaim->client_name}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="buyer-phone">Контактний телефон</label>
                                    <input type="text" name="client_phone" id="buyer-phone"
                                           value="{{$talon->phone ?? 'Не вказано'}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card-content card-form">
                            <p class="card-title">Дані того Хто звернувся</p>
                            <button type="button" class="btn-link btn-copy btn-blue" onclick="copyToClipboard()"
                                    @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                                Копіювати данні покупця
                            </button>

                            <div class="inputs-group one-row">
                                <div class="form-group required" data-valid="empty">
                                    <label for="sender-name">ПІБ</label>
                                    <input type="text" name="sender_name" id="sender-name"
                                           value="{{ old('sender_name', $currentClaim->sender_name ?? '') }}"
                                           placeholder="Прізвище Ім'я По батькові"
                                           @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) readonly @endif>
                                    <div class="help-block" data-empty="Обов'язкове поле"></div>
                                </div>
                                <div class="form-group required" data-valid="empty">
                                    <label for="sender-phone">Контактний телефон</label>
                                    <input type="text" name="sender_phone" id="sender-phone"
                                           value="{{ old('sender_phone', $currentClaim->sender_phone ?? $product->phone ?? '') }}"
                                           placeholder="+380501234567"
                                           @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) readonly @endif>
                                    <div class="help-block" data-empty="Обов'язкове поле"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-content card-form">
                        <p class="card-title">Дані про товар</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="article">Артикул</label>
                                <input type="text" name="product_article" id="article"
                                       value="{{$currentClaim->product_article}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="prod-name">Назва виробу</label>
                                <input type="text" name="product_name" id="prod-name"
                                       value="{{$currentClaim->product_name}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="factory-number">Заводський номер</label>
                                <input type="text" name="factory_number" id="factory-number"
                                       value="{{$currentClaim->factory_number}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="barcode">Штрихкод гарантійного талону</label>
                                <input type="text" name="barcode" id="barcode" value="{{$currentClaim->barcode}}"
                                       readonly>
                            </div>
                        </div>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="place-sale">Місце продажу</label>
                                <input type="text" name="point_of_sale" id="place-sale"
                                       value="{{$currentClaim->point_of_sale}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="date-sale">Дата продажу</label>
                                <input type="text" name="date_of_sale" id="date-sale"
                                       value="{{$currentClaim->date_of_sale}}" readonly>
                            </div>
                            <div class="form-group required" data-valid="empty">
                                <label for="date-start">Дата звернення в сервісний центр</label>
                                <div class="input-wrapper">
                                    <input type="text" name="date_of_claim" id="date-start"
                                           value="{{now()->format('Y-m-d')}}" class="_js-datepicker"
                                           @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                                    <span class="icon-calendar"></span>
                                </div>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group">
                                <label for="receipt-number">Номер квитанції сервісного центру</label>
                                <input type="text" name="receipt_number" id="receipt-number"
                                       value="{{ old('receipt_number', $currentClaim->receipt_number ?? '') }}"
                                       placeholder="0000000000"
                                       @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) readonly @endif>
                            </div>
                        </div>
                    </div>

                    <div class="card-content card-form">
                        <p class="card-title">Опис дефекту</p>
                        <div class="inputs-group one-row">
                            <div class="form-group required" data-valid="empty">
                                <label for="desc">Точний опис дефекту</label>
                                <textarea name="details" id="desc" placeholder="Точний опис дефекту" rows="3"
                                          @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) readonly @endif>{{ old('details', $currentClaim->details ?? '') }}</textarea>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group required" data-valid="empty">
                                <label for="reason">Причина дефекту</label>
                                <textarea name="deteails_reason" id="reason" placeholder="Причина дефекту" rows="3"
                                          @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) readonly @endif>{{ old('deteails_reason', $currentClaim->deteails_reason ?? '') }}</textarea>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-content card-form">
                        <p class="card-title">Підтверджуючі фото та інше</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="comment_photo">Коментар</label>
                                <textarea name="comment" id="comment" placeholder="Коментар до заяви" rows="3"
                                          @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) readonly @endif>{{ old('comment', $currentClaim->comment ?? '') }}</textarea>
                            </div>
                            <div class="form-group file">
                                <input type="file" name="file[]" id="file" multiple
                                       @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                                <label for="file">
                                    <span class="icon-upload"></span>
                                    <span class="help-block">Обов'язково до заповнення</span>
                                    <p>Перетягніть файли сюди або натисніть
                                        <span class="_blue">додати файли (jpg,jpeg,png)</span>
                                        <span class="_red"> *</span>
                                    </p>
                                    <span class="_grey _lil">Максимальний розмір одного файлу 5Мб. Максимальний обсяг завантажених файлів 50 Мб</span>
                                </label>
                            </div>
                        </div>
                        <div class="inputs-group one-row">
                            <div class="image-preview">
                                @foreach ($currentClaim->files as $file)
                                    <img src="{{ url($file->path) }}" alt="{{ $file->filename }}">
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card-content card-form service-work">
                        <div class="card-title__wrapper">
                            <p class="card-title">Сервісні роботи</p>

                            <div class="form-group default-select">
                                <select name="product_group" id="product-group"
                                        @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                                    <option value="-1">Виберіть групу товару</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="display-grid">
                            <div class="inputs-group one-column">
                                <div class="table-parts">
                                    <div class="table-header">
                                        <div class="row">
                                            <div class="cell">
                                                <div class="form-group checkbox">
                                                    <input type="checkbox" id="works-select-all">
                                                    <label for="works-select-all"></label>
                                                </div>
                                            </div>
                                            <div class="cell">Назва робіт</div>
                                            <div class="cell">Ціна, грн</div>
                                            <div class="cell">Нормогодин</div>
                                            <div class="cell">Вартість, грн</div>
                                        </div>
                                    </div>
                                    <div class="table-body" id="service-works-container">
                                        @foreach($serviceWorks as $work)
                                            <div class="row">
                                                <div class="cell">
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" id="service-{{ $work->id }}"
                                                               name="service_works[{{ $work->id }}][checkbox]"
                                                               value="{{ $work->id }}"
                                                               {{ $serviceWorks->contains($work->id) ? 'checked' : '' }} @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                                                        <label for="service-{{ $work->id }}"></label>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="service_works[{{ $work->id }}][name]"
                                                               readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="service_works[{{ $work->id }}][price]"
                                                               value="{{ number_format($work->price, 2) }}"
                                                               class='work-price' readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="number" step="0,1"
                                                               name="service_works[{{ $work->id }}][hours]"
                                                               value="{{ number_format($work->duration_decimal, 2) }}"
                                                               class="work-hours"
                                                               oninput="workCounter(event)"
                                                               onkeyup="workCounterHandler(event)">
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text"
                                                               name="service_works[{{ $work->id }}][total-price]"
                                                               value="{{ $work->duration_decimal * $work->price }}"
                                                               class="total-price" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="table-footer">
                                        <div class="row">
                                            <div class="cell">Загальна вартість робіт</div>
                                            <div class="cell"></div>
                                            <div class="cell"></div>
                                            <div class="cell" id="total-duration">
                                                <span>0.00</span>
                                                <input type="hidden" name="total-duration">
                                            </div>
                                            <div class="cell" id="total-works-sum">
                                                <span>0.00</span>
                                                <input type="hidden" name="total-works-sum">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="display-grid col-2">
                                    <div class="form-group">
                                        <label for="comment_service">Опис додаткових робіт</label>
                                        <textarea name="comment_service" id="comment_service"
                                                  placeholder="Якщо виконувалися додаткові роботи, які не відображені в списку до вибору, опишіть їх в цьому полі"
                                                  rows="3"
                                                  @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>{{ old('comment_service', $currentClaim->comment_service) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-content card-form used-parts">
                        <div class="card-title__wrapper">
                            <p class="card-title">Використані запчастини</p>
                            <div class="form-group have-icon">
                                <span class="icon icon-search-active"></span>
                                <input type="text" id="search-articul" placeholder="XXXXXX-XXX"
                                       @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>
                            </div>
                        </div>

                        <div class="card-group">
                            <div class="table-parts">
                                <div class="table-header">
                                    <div class="row">
                                        <div class="cell">Артикул</div>
                                        <div class="cell">Назва</div>
                                        <div class="cell">Ціна</div>
                                        <div class="cell">Кількість</div>
                                        <div class="cell">Знижка, %</div>
                                        <div class="cell">Всього зі знижкою, грн</div>
                                        <div class="cell">Замовити</div>
                                        <div class="cell">Дія</div>
                                    </div>
                                </div>
                                <div class="table-body">
                                    <!-- Запчастини для пошуку -->

                                    <div class="row-group" id="parts-container">
                                        <!-- Запчастини для пошуку -->
                                    </div>

                                    <div class="row title-only">
                                        <p>Додані запчастини</p>
                                    </div>

                                    <di class="row-group" id="added-parts-container">
                                        @foreach($spareParts as $index => $part)
                                            <div class="row" data-articul="{{ $part->spare_parts }}">
                                                <div class="cell">
                                                    <div class="form-group _bg-white">
                                                        <input type="text" name="spare_parts[{{ $index }}][spare_parts]"
                                                               value="{{ $part->spare_parts }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts[{{ $index }}][name]"
                                                               value="{{ $part->name }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts[{{ $index }}][price]"
                                                               value="{{ $part->price }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts[{{ $index }}][discount]"
                                                               value="{{ $part->discount }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group _bg-white">
                                                        <input type="text" class="part-quantity"
                                                               name="spare_parts[{{ $index }}][qty]"
                                                               value="{{ $part->qty }}" min='1' readonly
                                                               oninput="partCounter(event)"
                                                               onkeyup="partCounterHandler(event)">
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" class="part-total"
                                                               name="spare_parts[{{ $index }}][sum]"
                                                               value="{{ $part->sum }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" id="parts-{{ $part->id }}" checked
                                                               disabled>
                                                        <label for="parts-{{ $part->id }}"></label>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <button type="button"
                                                            class="btn-border btn-red btn-action remove-part-btn">
                                                        <span class="icon-minus"></span>
                                                    </button>
                                                </div>
                                            </div>
                                    @endforeach
                                </div>

                                <div class="table-footer">
                                    <div class="row">
                                        <div class="cell">Підсумок</div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell" id="total-parts-sum">
                                            <span>0</span>
                                            <input type="hidden" name="total-parts-sum" value="0">
                                        </div>
                                        <div class="cell"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-parts only-footer">
                                <div class="table-footer">
                                    <div class="row">
                                        <div class="cell">Загальна вартість по документу</div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell" id="total-sum-final">
                                            <span>0</span>
                                            <input type="hidden" name="total-sum-final" value="0">
                                        </div>
                                        <div class="cell"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-group">
                            <p class="sub-title">Для пошуку потрібних запчастин перейдіть за посиланням</p>
                            <div class="display-grid col-2 gap-8">
                                <div class="card-content card-text">
                                    <h2 class="text-underline text-blue">AL-KO</h2>
                                    <p>Після відкриття, у лівому верхньому куті виберіть директорію: <span
                                            class="text-red fw-600">ERSATZTEILSUCHE.</span></p>
                                    <p>Після переходу на іншу сторінку, в правому кутку в порожнє поле внесіть артикульний
                                        номер виробу, що Вас цікавить (артикульний номер виробу можна подивитися в
                                        прайс-листі або на заводській наклейці).</p>
                                    <p>Щоб дізнатися ціну на деталь, відкрийте каталог зап.частин (додаток №3 до договору з
                                        сервісного обслуговування). Комбінація Ctrl - F відкриває пошукове вікно, куди
                                        вноситься артикул зап.частини.</p>
                                    <p>За необхідності можна зберігати і друкувати деталі з інтернет бази. Для цього
                                        необхідно зліва внизу натиснути кнопку <span class="text-red fw-600">Drucken</span>,
                                        після чого вибрати потрібну вам сторінку.</p>
                                </div>
                                <div class="card-content card-text">
                                    <h2 class="text-underline text-blue">B&S</h2>
                                    <p>Дотримуючись наведених інструкцій, знайдіть необхідну деталь для вашого продукту
                                        Briggs & Stratton</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-group _mb0">
                            <div class="display-grid col-2 gap-8">
                                <div class="form-group _mb0">
                                    <label for="comment_part">Коментар</label>
                                    <textarea id="comment_part" name="comment_part"
                                              placeholder="Не знайшли потрібні запчастини? Опишіть вашу проблему" rows="3"
                                              @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review) disabled @endif>{{ old('comment_part', $currentClaim->comment_part) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <input type="hidden" name="contract_price"
                       value="{{ $defaultContract ? $defaultContract->service_works_price : 0 }}">
                <input type="hidden" name="contract_discount" value="{{ $defaultDiscount ?? 0 }}">

                <input type="hidden" name="button">
            </form>
        </div>
    </div>


    <div id="datepicker-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
    <script src="/cdn/js/swiper-bundle.min.js"></script>
    <script src="/cdn/js/popper.min.js"></script>
    <script src="/cdn/js/tippy-bundle.umd.min.js"></script>
    <script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
    <script src="/cdn/js/custom-select.js"></script>
    <script src="/js/components.js?v=002"></script>
    <script src="/js/main.js?v=003"></script>
    <script src="/js/warranty.js?v=003"></script>
</x-layouts.base>
