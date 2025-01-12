<x-layouts.base>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="main" id="main">
        <div class="page-warranty-create">
            <div class="page-name sticky">
                {{-- <h1>Гарантійна заява</h1> --}}
                <ul class="fake-breadcrumb">
                    <li>
                        <h1>Гарантійна заява</h1>
                    </li>
                    @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved)
                        <li>
                                <h1><a href="{{ route('technical-conclusions.create', $currentClaim->id) }}">Акт технічної експертизи</a></h1>
                        </li>
                    @endif
                </ul>
                <div class="btns">
                    @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::new)
                    <button type="submit" class="btn-primary btn-blue" value="send_to_save" form="send-to-save">Зберегти</button>
                    <button type="submit" class="btn-primary btn-blue" value="send_to_review" form="send-to-save">Відправити</button>
                    @elseif ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent && (auth()->user()->role_id === 2 || auth()->user()->role_id === 3))
                    @if ($technicalConclusion)
                        <button type="submit" class="btn-primary btn-blue" value="take_to_work" form="send-to-save">Взяти в роботу</button>
                    @else
                        <span class="btn-link btn-green text-only" style="font-size: 14px; text:left">Дані відправлено в 1С, синхронізація відбудеться орієнтовно за 5 хвилин і буде автоматично створено Акт технічної експертизи для подальшої роботи над заявкою</span>
                    @endif
                    @elseif ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review && (auth()->user()->role_id === 2 || auth()->user()->role_id === 3))
                        <a href="{{ route('technical-conclusions.create', $currentClaim->id) }}" class="btn-primary btn-blue">Перейти в акт</a>
                    @endif 
                    @if ($currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved)
                        <span class="btn-link btn-green text-only">Затверджено</span>
                    @endif
                    @if($currentClaim->autor AND $currentClaim->autor !== $currentClaim->manager_id AND $currentClaim->status !== \App\Enums\WarrantyClaimStatusEnum::approved)
                        <button type="button" class="btn-border btn-blue btn-only-icon _js-btn-show-modal" data-modal="chat">
                            <span class="icon-message"></span>
                        </button>
                    @endif
                    @if($currentClaim->status !== \App\Enums\WarrantyClaimStatusEnum::approved AND auth()->user()->role_id === 3)
                        <button type="button" class="btn-border btn-red _js-btn-show-modal" data-modal="switch-manager" data-claim-id="{{ $currentClaim->id }}">Змінити менеджера</button>
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
                    <button type="button" class="btn-primary btn-blue change-manager-btn">Переназначити менеджера</button>
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

            <form action="{{ route('warranty-claims.save') }}" id="send-to-save" method="POST" enctype="multipart/form-data" class="js-form-validation">
                @csrf

                <div class="card-lists">
                    <div class="card-content card-form">
                        <p class="card-title">Загальна інформація</p>
                        <div class="inputs-group one-row">
                            <div class="form-group wider-width">
                                <label for="number-doc">Номер</label>
                                <input type="text" name="number" id="number-doc" value="{{ $documentNumber }}" readonly>
                            </div>
                            <div class="form-group widest-width">
                                <label for="date-doc">Дата</label>
                                <input type="text" name="date" id="date-doc" value="{{ $currentClaim->date ?? date('Y-m-d')}}" readonly>
                            </div>
                            <input type="hidden" name="id" id="claim-id" value="{{ $currentClaim->id }}">
                            <input type="hidden" name="autor" id="autor-id" value="{{ auth()->user()->id }}">
                            <div class="form-group">
                                <label for="autor-name">Відповідальний</label>
                                <input type="text" id="autor-name" value="{{ $currentClaim->manager->first_name_ru ?? 'Не вказано' }}" readonly>
                            </div>

                            <!-- Давати класс "show-placeholder" тільки тоді, коли немає обраного пункту. Тобто ми хочемо показати placeholder.  -->
                            <div class="form-group required default-select" data-valid="vanilla-select">
                                <label for="service-center">Сервісний центр</label>
                                <select class="_js-select-2" name="service_partner" id="service-center" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>
                                    <option value="-1">Виберіть сервісний центр</option>
                                    @foreach($serviceCenters as $center)
                                        <option value="{{ $center->id }}" {{ old('service_partner', $currentClaim['service_partner'] ?? $currentClaim->service_partner) == $center->id ? 'selected' : '' }}>
                                            {{ $center->full_name_ru }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group required small-width default-select" data-valid="vanilla-select">
                                <label for="service-contract">Договір сервісу</label>
                                <select name="service_contract" id="service-contract" class="form-control" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>
                                    <option value="-1">Виберіть договір сервісу</option>
                                        @foreach($serviceContracts as $contract)
                                            <option value="{{ $contract->id }}" 
                                                    {{ old('service_contract', $currentClaim['service_contract'] ?? $defaultContract->id) == $contract->id ? 'selected' : '' }}>
                                                {{ $contract->name }}
                                            </option>
                                        @endforeach
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
                                    <input type="text" name="client_name" id="buyer-name" value="{{$currentClaim->client_name}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="buyer-phone">Контактний телефон</label>
                                    <input type="text" name="client_phone" id="buyer-phone" value="{{$talon->phone ?? 'Не вказано'}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card-content card-form">
                            <p class="card-title">Дані того Хто звернувся</p>
                            <button type="button" class="btn-link btn-copy btn-blue" onclick="copyToClipboard()" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif> Копіювати данні покупця</button>
      
                            <div class="inputs-group one-row">
                                <div class="form-group required" data-valid="empty">
                                    <label for="sender-name">ПІБ</label>
                                    <input type="text" name="sender_name" id="sender-name" value="{{ old('sender_name', $currentClaim->sender_name ?? '') }}"  placeholder="Прізвище Ім'я По батькові" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) readonly @endif>
                                    <div class="help-block" data-empty="Обов'язкове поле"></div>
                                </div>
                                <div class="form-group required" data-valid="empty">
                                    <label for="sender-phone">Контактний телефон</label>
                                    <input type="text" name="sender_phone" id="sender-phone" value="{{ old('sender_phone', $currentClaim->sender_phone ?? $product->phone ?? '') }}" placeholder="+380501234567" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) readonly @endif>
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
                                <input type="text" name="product_article" id="article" value="{{$currentClaim->product_article}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="prod-name">Назва виробу</label>
                                <input type="text" name="product_name" id="prod-name" value="{{$currentClaim->product_name}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="factory-number">Заводський номер</label>
                                <input type="text" name="factory_number" id="factory-number" value="{{$currentClaim->factory_number}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="barcode">Штрихкод гарантійного талону</label>
                                <input type="text" name="barcode" id="barcode" value="{{$currentClaim->barcode}}" readonly>
                            </div>
                        </div>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="place-sale">Місце продажу</label>
                                <input type="text" name="point_of_sale" id="place-sale" value="{{$currentClaim->point_of_sale}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="date-sale">Дата продажу</label>
                                <input type="text" name="date_of_sale" id="date-sale" value="{{$currentClaim->date_of_sale}}" readonly>
                            </div>
                            <div class="form-group required" data-valid="empty">
                                <label for="date-start">Дата звернення в сервісний центр</label>
                                <div class="input-wrapper">
                                    <input type="text" name="date_of_claim" id="date-start" value="{{now()->format('Y-m-d')}}" class="_js-datepicker" @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>
                                    <span class="icon-calendar"></span>
                                </div>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group">
                                <label for="receipt-number">Номер квитанції сервісного центру</label>
                                <input type="text" name="receipt_number" id="receipt-number" value="{{ old('receipt_number', $currentClaim->receipt_number ?? '') }}" placeholder="0000000000" @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) readonly @endif>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Опис дефекту</p>
                        <div class="inputs-group one-row">
                            <div class="form-group required" data-valid="empty">
                                <label for="desc">Точний опис дефекту</label>
                                <textarea name="details" id="desc" placeholder="Точний опис дефекту" rows="3" @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) readonly @endif>{{ old('details', $currentClaim->details ?? '') }}</textarea>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                            <div class="form-group required" data-valid="empty">
                                <label for="reason">Причина дефекту</label>
                                <textarea name="deteails_reason" id="reason" placeholder="Причина дефекту" rows="3" @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) readonly @endif>{{ old('deteails_reason', $currentClaim->deteails_reason ?? '') }}</textarea>
                                <div class="help-block" data-empty="Обов'язкове поле"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Підтверджуючі фото та інше</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="comment_photo">Коментар</label>
                                <textarea name="comment" id="comment" placeholder="Коментар до заяви" rows="3" @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) readonly @endif>{{ old('comment', $currentClaim->comment ?? '') }}</textarea>
                            </div>
                            <div class="form-group file">
                                <input type="file" name="file[]" id="file" multiple @if($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>
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
                                <div class="img">
                                    @if(env('APP_ENV') !== 'local')
                                        <img src="{{ asset('public/warranty_claims_files/' . basename($file->path)) }}" alt="{{ $file->filename }}">
                                    @else
                                        <img src="{{ asset('warranty_claims_files/' . basename($file->path)) }}" alt="{{ $file->filename }}">
                                    @endif
                                    <button type="button" class="icon-trash js-remove-image" data-action="{{ route('warranty-image.remove', ['id' => $file->id]) }}"></button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form service-work">
                        <div class="card-title__wrapper">
                            <p class="card-title">Сервісні роботи</p>
                            
                            <div class="form-group uyu8-select">
                                <select name="product_group" id="product-group" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>
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
                                                        <input type="checkbox" id="service-{{ $work->id }}" name="service_works[{{ $work->id }}][checkbox]" onchange="calcPrice();" {{ $serviceWorks->contains($work->id) ? 'checked' : '' }} @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>
                                                        <label for="service-{{ $work->id }}"></label>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="service_works[{{ $work->id }}][name]" value="{{ $work->name }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="service_works[{{ $work->id }}][price]" value="{{ number_format($work->price, 2) }}" class='work-price' readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="number" step="0.01" name="service_works[{{ $work->id }}][hours]" value="{{ number_format($work->qty, 2) }}" class="work-hours"
                                                            oninput="workCounter(event)"
                                                            onkeyup="workCounterHandler(event)"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="service_works[{{ $work->id }}][total-price]" value="{{ $work->duration_decimal * $work->price }}" class="total-price" readonly>
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
                                        <textarea name="comment_service" id="comment_service" placeholder="Якщо виконувалися додаткові роботи, які не відображені в списку до вибору, опишіть їх в цьому полі" rows="3" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>{{ old('comment_service', $currentClaim->comment_service) }}</textarea>
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
                                <input type="text" id="search-articul" placeholder="XXXXXX-XXX" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>
                            </div>
                        </div>
                    
                        <div class="card-group">
                            <div class="table-parts">
                                <div class="table-header">
                                    <div class="row">
                                        <div class="cell">Артикул</div>
                                        <div class="cell">Назва</div>
                                        <div class="cell">Ціна</div>
                                        <div class="cell">Знижка, %</div>
                                        <div class="cell">Кількість</div>
                                        <div class="cell">Всього зі знижкою, грн</div>
                                        <div class="cell">Замовити</div>
                                        <div class="cell">Дія</div>
                                    </div>
                                </div>
                                <div class="table-body">
                                    <div class="row-group" id="parts-container">
                                        <!-- Запчастини для пошуку -->
                                    </div>
                                    

                                    <div class="row title-only">
                                        <p>Додані запчастини</p>
                                    </div>

                                    <div class="row-group" id="added-parts-container">
                                        @foreach($spareParts as $index => $part)
                                        <div class="row" data-articul="{{ $part->spare_parts }}" data-id="{{ $part->id }}">
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input type="text" name="spare_parts[{{ $index }}][spare_parts]" value="{{ $part->spare_parts }}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts[{{ $index }}][name]" value="{{ $part->name }}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts[{{ $index }}][price]" value="{{ $part->price }}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts[{{ $index }}][discount]" value="{{ $part->discount }}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input class="part-quantity" type="text" name="spare_parts[{{ $index }}][qty]" value="{{ $part->qty }}" min='1' readonly
                                                        oninput="partCounter(event)" 
                                                        onkeyup="partCounterHandler(event)"
                                                    >
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input class="part-total" type="text" name="spare_parts[{{ $index }}][sum]" value="{{ $part->sum }}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group checkbox">
                                                    <input type="checkbox" id="parts-{{ $part->id }}" disabled>
                                                    <label for="parts-{{ $part->id }}"></label>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <button type="button" class="btn-border btn-red btn-action remove-part-btn" data-action="{{route('parts.destroy', $part->id)}}" onclick="removePartHandler(this)">
                                                    <span class="icon-minus"></span>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>


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
                                        <div class="cell">
                                        </div>
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
                            <p class="sub-title">Для пошуку потрібних запчастин  перейдіть за посиланням</p>
                            <div class="display-grid col-2 gap-8">
                                <div class="card-content card-text">
                                    <h2 class="text-underline text-blue"><a target="_blank" style="text-decoration: none; color: #0561b6;" href="https://parts.al-ko.com/shop/parts/index.php?tc_sid=2&tc_cid=1&tc_lid=1">AL-KO</a></h2>
                                    <p>Після відкриття, у лівому верхньому куті виберіть директорію: <span class="text-red fw-600">ERSATZTEILSUCHE.</span></p>
                                    <p>Після переходу на іншу сторінку, в правому кутку в порожнє поле внесіть артикульний номер виробу, що Вас цікавить (артикульний номер виробу можна подивитися в прайс-листі або на заводській наклейці).</p>
                                </div>
                                <div class="card-content card-text">
                                    <h2 class="text-underline text-blue"><a target="_blank" style="text-decoration: none; color: #0561b6;" href="http://www.briggsandstratton.com">B&S</a></h2>
                                    <p>Дотримуючись наведених інструкцій, знайдіть необхідну деталь для вашого продукту Briggs & Stratton</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-group _mb0">
                            <div class="display-grid col-2 gap-8">
                                <div class="form-group _mb0">
                                    <label for="comment_part">Коментар</label>
                                    <textarea id="comment_part" name="comment_part" placeholder="Не знайшли потрібні запчастини? Опишіть вашу проблему" rows="3" @if ($currentClaim && $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::approved OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::review OR $currentClaim->status === \App\Enums\WarrantyClaimStatusEnum::sent) disabled @endif>{{ old('comment_part', $currentClaim->comment_part) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <input type="hidden" name="contract_price" value="{{ $defaultContract ? $defaultContract->service_works_price : 0 }}">
                <input type="hidden" name="contract_discount" value="{{ $defaultDiscount ?? 0 }}">
                
                <input type="hidden" name="button">
            </form>
        </div>
    </div>

    <div id="datepicker-container"></div>




<!-- Код для пошуку і збереження запчастини для сейв форми -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-articul');
        const partsContainer = document.getElementById('parts-container');
        const addedPartsContainer = document.getElementById('added-parts-container');
        const totalPartsSumElement = document.getElementById('total-parts-sum');
        const discount = {{ $defaultDiscount ?? 0 }};
        let addedParts = [];
    
        function calculatePartsTotal() {
            let totalPartsSum = 0;
            const partRows = addedPartsContainer.querySelectorAll('.row');
            partRows.forEach(row => {
                const sumInput = row.querySelector('input[name*="[sum]"]');
                const sum = parseFloat(sumInput.value) || 0;
                totalPartsSum += sum;
            });
            console.log("Calculated Total Sum:", totalPartsSum);
            return totalPartsSum;
        }
    
        function updatePartsTotal() {
            const partsTotal = calculatePartsTotal();
            console.log("Updating Total Parts Sum:", partsTotal);
            totalPartsSumElement.textContent = partsTotal.toFixed(2);
            document.querySelector('input[name="total-parts-sum"]').value = partsTotal.toFixed(2);
        }
    
        searchInput.addEventListener('input', function() {
            const articul = this.value.trim();
            
            if (articul.length >= 3) {
                fetch(`/parts/${articul}`)
                    .then(response => response.json())
                    .then(data => {
                        partsContainer.innerHTML = '';
                        if (data.data.length > 0) {                            
                            data.data.forEach((part, index) => {
                                if (part.product_prices && part.product_prices.recommended_price) {
                                    const recommendedPrice = parseFloat(part.product_prices.recommended_price);
                                    const priceWithDiscountAndVat = (recommendedPrice * (1 - discount / 100)).toFixed(2);
                                    
                                    const newRow = `
                                        <div class="row" data-articul="${part.articul}">
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input type="text" name="spare_parts_temp[${index}][spare_parts]" value="${part.articul}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts_temp[${index}][name]" value="${part.name}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts_temp[${index}][price]" value="${priceWithDiscountAndVat}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts_temp[${index}][discount]" value="${discount}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input type="number" name="spare_parts_temp[${index}][qty]" value="1" class="part-quantity">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input  type="text" name="spare_parts_temp[${index}][sum]" value="${priceWithDiscountAndVat}" readonly class="part-total">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group checkbox">
                                                    <input type="checkbox" id="parts-${part.id}" ${part.checked ? 'checked' : ''}>
                                                    <label for="parts-${part.id}"></label>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <button type="button" class="btn-primary btn-blue btn-action add-part-btn">
                                                    <span class="icon-plus"></span>
                                                </button>
                                            </div>
                                        </div>
                                    `;
                                                                    
    
                                    partsContainer.insertAdjacentHTML('beforeend', newRow);
    
                                    const currentRow = partsContainer.lastElementChild;
                                    
                                    currentRow.querySelector('.part-quantity').addEventListener('input', function() {
                                        const quantity = parseInt(this.value) || 0;
                                        const total = (priceWithDiscountAndVat * quantity).toFixed(2);
                                        currentRow.querySelector('.part-total').value = total;
                                    });
    
                                    currentRow.querySelector('.add-part-btn').addEventListener('click', function() {
                                        const articul = currentRow.querySelector('input[name*="[spare_parts]"]').value;
                                        if (addedParts.some(part => part.articul === articul)) {
                                            alert('Запчастина вже добавлена');
                                            return;
                                        }
    
                                        const name = currentRow.querySelector('input[name*="[name]"]').value;
                                        const price = parseFloat(currentRow.querySelector('input[name*="[price]"]').value);
                                        const quantity = parseInt(currentRow.querySelector('.part-quantity').value);
                                        const total = parseFloat(currentRow.querySelector('.part-total').value);
                                        const checked = currentRow.querySelector(`#parts-${part.id}`).checked;
    
                                        addedParts.push({ articul, name, price, quantity, total });
    
                                        const addedRow = `
                                            <div class="row" data-articul="${articul}">
                                                <div class="cell">
                                                    <div class="form-group _bg-white">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][spare_parts]" value="${articul}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][name]" value="${name}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][price]" value="${price.toFixed(2)}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts_temp[${index}][discount]" value="${discount}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group _bg-white">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][qty]" value="${quantity}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input  type="text" name="spare_parts[${addedParts.length - 1}][sum]" value="${total.toFixed(2)}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" id="parts-${part.id}" ${checked ? 'checked' : ''} disabled>
                                                        <label for="parts-${part.id}"></label>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <button type="button" class="btn-border btn-red btn-action remove-part-btn">
                                                        <span class="icon-minus"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        `;
    
                                        addedPartsContainer.insertAdjacentHTML('beforeend', addedRow);
                                        updatePartsTotal(); // Обновляем сумму после добавления части
    
                                        // Добавляем прослушиватель события удаления запчасти
                                        const removeButton = addedPartsContainer.querySelector('.row:last-child .remove-part-btn');
                                        removeButton.addEventListener('click', function() {
                                            const row = this.closest('.row');
                                            const articul = row.getAttribute('data-articul');
                                            addedParts = addedParts.filter(part => part.articul !== articul);
                                            row.remove();
                                            updatePartsTotal(); // Обновляем сумму после удаления части
                                        });
                                    });
                                }
                            });
                        } else {
                            partsContainer.innerHTML = '<div>Запчастина не найдена</div>';
                        }
                    })
                    .catch(error => console.error('Error fetching parts:', error));
            } else {
                partsContainer.innerHTML = '';
            }
        });
    
        updatePartsTotal(); 
    });    
</script> -->


<!-- Дизейбл для селекта при невыбранном сервис-центре -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
    const serviceCenterSelect = document.getElementById('service-center');
    const productGroupSelect = document.getElementById('product-group');
    const partGroupSelect = document.getElementById('search-articul');

    function toggleProductGroupSelect() {
        if (serviceCenterSelect.value === '') {
            productGroupSelect.disabled = true;
            partGroupSelect.disabled = true;
        } else {
            productGroupSelect.disabled = false;
            partGroupSelect.disabled = false;   
        }
    }

    toggleProductGroupSelect();

    serviceCenterSelect.addEventListener('change', toggleProductGroupSelect);
    partGroupSelect.addEventListener('change', toggleProductGroupSelect);
});
</script> -->

<!-- Общая сумма по документу -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Получаем элементы по их ID
    const totalPartsSumElement = document.getElementById('total-parts-sum');
    const totalSumElement = document.getElementById('total-sum');
    const totalSumFinalElement = document.getElementById('total-sum-final');

    // Функция для расчета и обновления общей суммы
    function updateFinalSum() {
        // Преобразуем текстовое содержание в числа
        const totalPartsSum = parseFloat(totalPartsSumElement.textContent) || 0;
        const totalSum = parseFloat(totalSumElement.textContent) || 0;
        
        // Рассчитываем общую сумму
        const finalSum = totalPartsSum + totalSum;

        // Обновляем элемент с общей суммой
        totalSumFinalElement.textContent = finalSum.toFixed(2);

        // Записати загальну вартість запчастин у прихований інпут
        document.querySelector('input[name="total-parts-sum"]').value = totalPartsSum;

        console.log('mutation');
        
    }

    // Обновляем итоговую сумму сразу после загрузки страницы
    updateFinalSum();

    // Используем MutationObserver для отслеживания изменений в DOM
    const observer = new MutationObserver(updateFinalSum);

    // Настраиваем observer для наблюдения за изменениями текста в элементах
    observer.observe(totalPartsSumElement, { childList: true, subtree: true, characterData: true });
    observer.observe(totalSumElement, { childList: true, subtree: true, characterData: true });
});

</script> -->

<!-- Загальний підсумок сервісних робіт -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceWorksContainer = document.getElementById('service-works-container');
    const totalDurationElement = document.getElementById('total-duration');
    const totalSumElement = document.getElementById('total-sum');

    console.log('serviceWorksContainer:', serviceWorksContainer);
    console.log('totalDurationElement:', totalDurationElement);
    console.log('totalSumElement:', totalSumElement);

    function calculateTotals() {
        let totalDuration = 0;
        let totalSum = 0;

        const rows = serviceWorksContainer.querySelectorAll('.row');

        rows.forEach(row => {
            const hoursInput = row.querySelector('.work-hours');
            const totalInput = row.querySelector('.work-total-price');

            const hours = parseFloat(hoursInput.value) || 0;
            const price = parseFloat(hoursInput.dataset.price) || 0;
            const total = hours * price;
            totalInput.value = total.toFixed(2);

            totalDuration += hours;
            totalSum += total;
        });

        totalDurationElement.textContent = totalDuration.toFixed(2);
        totalSumElement.textContent = totalSum.toFixed(2);
    }

    serviceWorksContainer.addEventListener('input', function(event) {
        if (event.target.classList.contains('work-hours')) {
            console.log('Input event detected on work-hours');
            calculateTotals();
        }
    });

    calculateTotals(); // Initial calculation
});
</script> -->

<!-- Відправка сервісних работ при кнопці Відправити -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('send-to-review-form');
    const serviceWorksHiddenContainer = document.getElementById('service-works-hidden');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Собрать данные для сервисных работ
        const serviceWorks = @json($currentClaim->serviceWorks->pluck('id')->toArray());
        serviceWorksHiddenContainer.innerHTML = '';

        console.log('Service Works:', serviceWorks);

        serviceWorks.forEach((work) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'service_works[]';
            input.value = work;
            serviceWorksHiddenContainer.appendChild(input);
        });

        console.log('Hidden Inputs for Service Works:', serviceWorksHiddenContainer.innerHTML);

        // Отправить форму
        form.submit();
    });
});
</script> -->
    
<!-- Відображення менеджерів в модалці -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {

    const showModalButtons = document.querySelectorAll('._js-btn-show-modal[data-modal="switch-manager"]');
    const modal = document.querySelector('.js-modal-switch-manager');
    const modalBody = modal ? modal.querySelector('.manager-body') : null;
    const closeModalButton = modal ? modal.querySelector('.btn-close') : null;
    const searchInput = modal ? modal.querySelector('input[name="manager-search"]') : null;
    const reassignButton = modal ? modal.querySelector('.change-manager-btn') : null;
    const modalOverlay = document.querySelector('.modal-overlay');
    let selectedManagerId = null;

    if (!showModalButtons || !modal || !modalBody || !closeModalButton || !searchInput || !reassignButton) {
        console.error('One or more elements are missing:', { showModalButtons, modal, modalBody, closeModalButton, searchInput, reassignButton });
        return;
    }

    let managersList = [];

    showModalButtons.forEach(button => {
        button.addEventListener('click', function () {
            console.log('Show modal button clicked');
            fetch('/managers')
                .then(response => response.json())
                .then(data => {
                    managersList = data;
                    displayManagers(managersList);
                    modal.classList.add('open');
                    fadeIn(modal);
                    modalOverlay.classList.add('show');
                    modalOverlay.classList.remove('hide');
                })
                .catch(error => {
                    console.error('Error fetching managers:', error);
                });
        });
    });

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const filteredManagers = managersList.filter(manager => 
            manager.first_name_ru.toLowerCase().includes(query)
        );
        displayManagers(filteredManagers);
    });

    closeModalButton.addEventListener('click', function () {
        console.log('Close modal button clicked');
        fadeOut(modal);
        modal.classList.remove('open');
        modalOverlay.classList.add('hide'); // добавлено
        modalOverlay.classList.remove('show');
    });

    window.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            fadeOut(modal, () => {
                modal.classList.remove('open');
            });
            modalOverlay.classList.add('hide'); 
            modalOverlay.classList.remove('show'); 
        }
    });

    reassignButton.addEventListener('click', function () {
        const selectedRadio = modalBody.querySelector('input[type="radio"][name="manager"]:checked');
        if (!selectedRadio) {
            alert('Виберіть менеджера');
            return;
        }
        selectedManagerId = selectedRadio.value;

        const claimId = document.getElementById('claim-id').value;

        fetch(`/warranty-claims/${claimId}/update-manager`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ manager_id: selectedManagerId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const managerName = selectedRadio.nextElementSibling.textContent;
                document.getElementById('autor-id').value = selectedManagerId;
                document.getElementById('autor-name').value = managerName;
                modal.classList.remove('open');
                modal.style.display = 'none';
                modalOverlay.style.display = 'none';
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error updating manager:', error));
    });

    function displayManagers(managers) {
        modalBody.innerHTML = ''; 
        managers.forEach(manager => {
            const managerRow = `
                <div class="form-group radio">
                    <input type="radio" id="manager-${manager.id}" name="manager" value="${manager.id}">
                    <label for="manager-${manager.id}">${manager.first_name_ru}</label>
                </div>
            `;
            modalBody.insertAdjacentHTML('beforeend', managerRow);
        });
    }

    function fadeIn(element) {
        if (!element) {
            console.error('fadeIn: element is null');
            return;
        }

        let opacity = 0;
        element.style.opacity = opacity;
        element.style.display = 'block';

        const last = +new Date();
        const tick = function () {
            opacity += (new Date() - last) / 400;
            element.style.opacity = opacity;
            if (opacity < 1) {
                (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
            }
        };

        tick();
    }

    function fadeOut(element, callback) {
        if (!element) {
            console.error('fadeOut: element is null');
            return;
        }

        let opacity = 1;
        const last = +new Date();
        const tick = function () {
            opacity -= (new Date() - last) / 400;
            element.style.opacity = opacity;
            if (opacity > 0) {
                (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
            } else {
                element.style.display = 'none';
                if (typeof callback === 'function') {
                    callback();
                }
            }
        };

        tick();
    }
});
</script> -->

    <!-- Форматування дати -->
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateStartInput = document.getElementById('date-start');
            const dateSaleInput = document.getElementById('date-sale');

            function formatDate(input) {
                const dateValue = input.value;
                const [month, day, year] = dateValue.split('/');
                if (day && month && year) {
                    const formattedDate = `${year}-${month}-${day}`;
                    input.value = formattedDate;
                }
            }

            dateStartInput.addEventListener('change', function () {
                formatDate(dateStartInput);
            });

            dateSaleInput.addEventListener('change', function () {
                formatDate(dateSaleInput);
            });
        });
    </script> -->

    <!-- Збереження заяви-->
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-create');
            const submitButton = document.getElementById('save-claim-btn');

        //    submitButton.addEventListener('click', function(event) {
         //       event.preventDefault();
          //      form.submit();
           // });
        });
    </script> -->

    <!-- Код для генерації сервісних робот для певної групи товарів -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const productGroupSelect = document.getElementById('product-group');
    const serviceWorksContainer = document.getElementById('service-works-container');
    const totalDurationElement = document.getElementById('total-duration');
    const totalSumElement = document.getElementById('total-sum');
    const currentClaimServiceWorks = @json($currentClaim ? $currentClaim->serviceWorks->pluck('id')->toArray() : []);
    const contractPrice = {{ $defaultContract ? $defaultContract->service_works_price : 0 }};
    
    let savedCheckboxStates = {};
    let totalSum = 0;

    function saveCheckboxStates(groupId) {
        const checkboxes = serviceWorksContainer.querySelectorAll('input[type="checkbox"]');
        if (!savedCheckboxStates[groupId]) {
            savedCheckboxStates[groupId] = {};
        }
        checkboxes.forEach(checkbox => {
            savedCheckboxStates[groupId][checkbox.value] = checkbox.checked;
        });
        console.log(`Saved states for group ${groupId}:`, savedCheckboxStates[groupId]);
    }

    function restoreCheckboxStates(groupId) {
        const checkboxes = serviceWorksContainer.querySelectorAll('input[type="checkbox"]');
        if (savedCheckboxStates[groupId]) {
            checkboxes.forEach(checkbox => {
                if (savedCheckboxStates[groupId][checkbox.value] !== undefined) {
                    checkbox.checked = savedCheckboxStates[groupId][checkbox.value];
                }
            });
        }
        console.log(`Restored states for group ${groupId}:`, savedCheckboxStates[groupId]);
    }

    function calculateTotalSum() {
        const checkboxes = serviceWorksContainer.querySelectorAll('input[type="checkbox"]:checked');
        totalSum = 0;
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('.row');
            const totalPriceElement = row.querySelector('.total-price');
            const totalPrice = parseFloat(totalPriceElement.value);
            totalSum += totalPrice;
        });
        totalSumElement.textContent = totalSum.toFixed(2);
    }

    function loadServiceWorks(groupId) {
        saveCheckboxStates(productGroupSelect.value);
        fetch(`/service/${groupId}`)
            .then(response => response.json())
            .then(data => {
                let totalDuration = 0;
                let nonCkeckedElements = serviceWorksContainer.querySelectorAll('input[name="service_works[]"]:not(:checked)');
                console.log(nonCkeckedElements);

                //serviceWorksContainer.innerHTML = ''; 

                nonCkeckedElements.forEach(nonCkeckedElement => {
                    nonCkeckedElement.closest('.row').remove();
                })

                data.forEach(work => {
                    const isChecked = currentClaimServiceWorks.includes(work.id) || (savedCheckboxStates[groupId] && savedCheckboxStates[groupId][work.id]);
                    const duration = parseFloat(work.duration_decimal);
                    const totalPrice = duration * contractPrice;

                    if (isChecked) {
                        totalDuration += duration;
                    }

                    const row = document.createElement('div');
                    row.classList.add('row');

                    row.innerHTML = `
                        <div class="cell">
                            <div class="form-group checkbox">
                                <input type="checkbox" id="service-${work.id}" name="service_works[]" value="${work.id}" ${isChecked ? 'checked' : ''}>
                                <label for="service-${work.id}"></label>
                            </div>
                        </div>
                        <div class="cell">
                            <div class="form-group">
                                <input type="text" value="${work.name}" readonly>
                            </div>
                        </div>
                        <div class="cell">
                            <div class="form-group">
                                <input type="text" value="${contractPrice.toFixed(2)}" readonly>
                            </div>
                        </div>
                        <div class="cell">
                            <div class="form-group">
                                <input type="number" step="0.01" name="hours[]" value="${duration.toFixed(2)}" class="work-hours" data-price="${contractPrice.toFixed(2)}">
                            </div>
                        </div>
                        <div class="cell">
                            <div class="form-group">
                                <input type="text" value="${totalPrice.toFixed(2)}" class="total-price" readonly>
                            </div>
                        </div>
                    `;

                    row.querySelector('input[type="checkbox"]').addEventListener('change', function() {
                        const durationElement = this.closest('.row').querySelector('.work-hours');
                        const durationValue = parseFloat(durationElement.value);
                        if (this.checked) {
                            totalDuration += durationValue;
                        } else {
                            totalDuration -= durationValue;
                        }
                        totalDurationElement.textContent = totalDuration.toFixed(2);
                        calculateTotalSum();
                    });

                    row.querySelector('.work-hours').addEventListener('input', function() {
                        const durationValue = parseFloat(this.value);
                        const price = parseFloat(this.dataset.price);
                        const totalPriceElement = this.closest('.row').querySelector('.total-price');
                        const totalPrice = durationValue * price;
                        totalPriceElement.value = totalPrice.toFixed(2);
                        calculateTotalSum();
                    });

                    // serviceWorksContainer.appendChild(row);

                    serviceWorksContainer.insertAdjacentElement('beforeend', row)
                });

                totalDurationElement.textContent = totalDuration.toFixed(2);
                calculateTotalSum();
                restoreCheckboxStates(groupId);
            })
            .catch(error => console.error('Error fetching service works:', error));
    }

    if (productGroupSelect.value !== '-1') {
        loadServiceWorks(productGroupSelect.value);
    }

    productGroupSelect.addEventListener('change', function() {
        const groupId = this.value;
        if (groupId !== '-1') {
            loadServiceWorks(groupId);
        } else {
            serviceWorksContainer.innerHTML = '';
            totalDurationElement.textContent = '0.00';
            totalSumElement.textContent = '0.00';
        }
    });
});

</script>     -->
    

<!-- Копіювання данних ПІБ та телефон -->
<!-- <script>
    function copyToClipboard() {
        // Get the input elements
    var buyerName = document.getElementById("buyer-name").value;
    var buyerPhone = document.getElementById("buyer-phone").value;

    // Get the input elements for the sender
    var senderName = document.getElementById("sender-name");
    var senderPhone = document.getElementById("sender-phone");

    // Set the values of the sender inputs to the buyer values
    senderName.value = buyerName;
    senderPhone.value = buyerPhone;
}
</script> -->


<!-- Код для автозаповнення контрактів по сервісним центрам -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCenterSelect = document.getElementById('service-center');
    const serviceContractSelect = document.getElementById('service-contract');

    let discount = {{ $defaultDiscount ?? 0 }};

    @if($defaultServicePartner)
        serviceCenterSelect.value = "{{ $defaultServicePartner->id }}";
    @endif

    function loadContractDetails(centerId) {
        fetch('/get-contract-details', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ service_center_id: centerId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.contract) {
                const option = document.createElement('option');
                option.value = data.contract.id;
                option.textContent = `${data.contract.number}`;
                serviceContractSelect.innerHTML = ''; 
                serviceContractSelect.appendChild(option);
                serviceContractSelect.value = data.contract.id;

                discount = data.discount;
            } else {
                console.error('Contract not found');
            }
        })
        .catch(error => {
            console.error('Error fetching contract details:', error);
        });
    }

    serviceCenterSelect.addEventListener('change', function() {
        const centerId = this.value;
        if (centerId) {
            loadContractDetails(centerId);
        }
    });

    if (serviceCenterSelect.value !== '-1') {
        loadContractDetails(serviceCenterSelect.value);
    }
});

</script> -->

<!-- Дата -->
<!-- <script>
 document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('._js-datepicker');
    dateInputs.forEach((input, index) => {
        const containerId = input.getAttribute('data-container-id') || `datepicker-container-${index}`;
        
        const currentDate = new Date();
        const formattedDate = currentDate.toISOString().split('T')[0];
        input.value = formattedDate;
        
        new Datepicker(input, {
            format: 'yyyy-mm-dd',
            autohide: true,
            container: `#${containerId}`
        });
    });
});
</script> -->

<!-- Збереження запчастин для Відправити -->
<!-- <script>



    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-articul');
        const partsContainer = document.getElementById('parts-container');
        const addedPartsContainer = document.getElementById('added-parts-container');
        const totalSumElement = document.getElementById('total-parts-sum');
        //const totalPartsSumElement = document.getElementById('total-parts-sum');
        const addedPartsHiddenContainer = document.getElementById('added-parts-hidden');
        const discount = {{ $defaultDiscount ?? 0 }};

        let totalSum = +document.querySelector('input[name="total-parts-sum"]').value;
        let addedParts = [];

        console.log(document.querySelector('input[name="total-parts-sum"]'));
        

        searchInput.addEventListener('input', function () {
            const articul = this.value.trim();

            if (articul.length >= 3) {
                fetch(`/parts/${articul}`)
                    .then(response => response.json())
                    .then(data => {
                        partsContainer.innerHTML = '';
                        if (data.data.length > 0) {
                            
                            const titleRow = '<div class="row title-only"><p>Результати пошуку</p></div>';
                            partsContainer.insertAdjacentHTML('beforeend', titleRow);

                            data.data.forEach((part, index) => {
                                if (part.product_prices && part.product_prices.recommended_price) {
                                    const recommendedPrice = parseFloat(part.product_prices.recommended_price);
                                    const priceWithDiscount = (recommendedPrice * (1 - discount / 100)).toFixed(2);

                                    const newRow = `
                                        <div class="row" data-articul="${part.articul}">
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input type="text" name="spare_parts_temp[${index}][spare_parts]" value="${part.articul}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts_temp[${index}][name]" value="${part.name}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts_temp[${index}][price]" value="${recommendedPrice}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" name="spare_parts_temp[${index}]discount]" value="${discount}" readonly>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input type="number" name="spare_parts_temp[${index}][qty]" value="1" class="part-quantity">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input  type="text" name="spare_parts_temp[${index}][sum]" value="${priceWithDiscount}" readonly class="part-total">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group checkbox">
                                                    <input type="checkbox" id="parts-${part.id}" ${part.checked ? 'checked' : ''}>
                                                    <label for="parts-${part.id}"></label>
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <button type="button" class="btn-primary btn-blue btn-action add-part-btn">
                                                    <span class="icon-plus"></span>
                                                </button>
                                            </div>
                                        </div>
                                    `;

                                    partsContainer.insertAdjacentHTML('beforeend', newRow);

                                    const currentRow = partsContainer.lastElementChild;
                                    currentRow.querySelector('.part-quantity').addEventListener('input', function () {
                                        const quantity = parseInt(this.value) || 0;
                                        const total = (priceWithDiscount * quantity).toFixed(2);
                                        currentRow.querySelector('.part-total').value = total;
                                    });

                                    currentRow.querySelector('.add-part-btn').addEventListener('click', function (e) {
                                        const articul = currentRow.querySelector('input[name*="[spare_parts]"]').value;
                                        const btn = e.target.closest('button');
                                        totalSum = +document.querySelector('input[name="total-parts-sum"]').value;

                                        if (addedParts.some(part => part.articul === articul)) {
                                            alert('Запчастина вже добавлена');
                                            return;
                                        } 
                                        
                                        // btn.disabled = false;

                                        const name = currentRow.querySelector('input[name*="[name]"]').value;
                                        const price = parseFloat(currentRow.querySelector('input[name*="[price]"]').value);
                                        const quantity = parseInt(currentRow.querySelector('.part-quantity').value);
                                        const total = parseFloat(currentRow.querySelector('.part-total').value);
                                        const checked = currentRow.querySelector(`#parts-${part.id}`).checked;



                                        addedParts.push({ articul, name, price, quantity, total });                                        

                                        const addedRow = `
                                            <div class="row" data-articul="${articul}">
                                                <div class="cell">
                                                    <div class="form-group _bg-white">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][spare_parts]" value="${articul}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][name]" value="${name}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][price]" value="${price.toFixed(2)}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" name="spare_parts_temp[${index}]discount]" value="${discount}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group _bg-white">
                                                        <input type="text" name="spare_parts[${addedParts.length - 1}][qty]" value="${quantity}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input class="part-total"  type="text" name="spare_parts[${addedParts.length - 1}][sum]" value="${total.toFixed(2)}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" id="parts-${part.id}" ${checked ? 'checked' : ''} disabled>
                                                        <label for="parts-${part.id}"></label>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <button type="button" class="btn-border btn-red btn-action remove-part-btn">
                                                        <span class="icon-minus"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        `;

                                        addedPartsContainer.insertAdjacentHTML('beforeend', addedRow);
                                        totalSum += total;
                                        totalSumElement.textContent = totalSum.toFixed(2);

                                        const removeButton = addedPartsContainer.querySelector('.row:last-child .remove-part-btn');
                                        removeButton.addEventListener('click', function () {
                                            totalSum = +document.querySelector('input[name="total-parts-sum"]').value;
                                            const row = this.closest('.row');
                                            const rowTotal = parseFloat(row.querySelector('.part-total').value);
                                            totalSum -= rowTotal;
                                            totalSumElement.textContent = totalSum.toFixed(2);
                                            const articul = row.getAttribute('data-articul');
                                            addedParts = addedParts.filter(part => part.articul !== articul);
                                            
                                            row.remove();
                                        });

                                        // Добавление скрытых полей
                                        const hiddenFields = `
                                            <input type="hidden" name="spare_parts[${addedParts.length - 1}][spare_parts]" value="${articul}">
                                            <input type="hidden" name="spare_parts[${addedParts.length - 1}][name]" value="${name}">
                                            <input type="hidden" name="spare_parts[${addedParts.length - 1}][price]" value="${price.toFixed(2)}">
                                            <input type="hidden" name="spare_parts[${addedParts.length - 1}][qty]" value="${quantity}">
                                            <input type="hidden" name="spare_parts[${addedParts.length - 1}][sum]" value="${total.toFixed(2)}">
                                        `;
                                        addedPartsHiddenContainer.insertAdjacentHTML('beforeend', hiddenFields);

                                        // Отладочные выводы
                                        console.log('Added part:', {
                                            articul,
                                            name,
                                            price,
                                            quantity,
                                            total
                                        });
                                        console.log('Hidden fields added:', hiddenFields);
                                    });
                                }
                            });
                        } else {
                            partsContainer.innerHTML = '<div>Запчастина не найдена</div>';
                        }
                    })
                    .catch(error => console.error('Error fetching parts:', error));
            } else {
                partsContainer.innerHTML = '';
            }
        });


        // Видалити запчистини із сервера
        const removePartFromServer = document.querySelectorAll('._js-remove-part-from-server');
        if(removePartFromServer.length > 0){
            removePartFromServer.forEach(btn => {
                btn.addEventListener('click', () => {

                    var action = btn.dataset.action;
                    fetch(action, {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                    })
                    .then(response => response.json())
                    .then(function (response) {
                        if (response.success) {
                            
                            const row = btn.closest('.row');
                            const rowTotal = parseFloat(row.querySelector('.part-total').value);
                            let totalSum = +document.querySelector('input[name="total-parts-sum"]').value;
                            const totalSumElement = document.getElementById('total-parts-sum');

                            totalSum -= rowTotal;
                            totalSumElement.textContent = totalSum.toFixed(2);
                            
                            row.remove();

                        }
                    });
                })
            })
        }
    });
</script> -->



    

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
    <script src="/cdn/js/swiper-bundle.min.js"></script>
    <script src="/cdn/js/popper.min.js"></script>
    <script src="/cdn/js/tippy-bundle.umd.min.js"></script>
    <script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
    <script src="/cdn/js/custom-select.js"></script>
    <script src="/js/components.js?v=003"></script>
    <script src="/js/main.js?v=003"></script>
    <script src="/js/warranty.js?v=003"></script>
</x-layouts.base>
