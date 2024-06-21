<x-layouts.base>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="main" id="main">
        <div class="page-warranty-create">
            <div class="page-name sticky">
                    <h1>Гарантійна заява</h1>
                <div class="btns">
                    @if(session('status'))
                    <button type="button" class="btn-link btn-blue text-only">{{ session('status') }}</button>
                        <button type="button" class="btn-border btn-blue btn-only-icon _js-btn-show-modal" data-modal="chat">
                            <span class="icon-message"></span>
                        </button>
                        <button type="button" class="btn-border btn-red _js-btn-show-modal" data-modal="switch-manager">Змінити менеджера</button>
                    @else
                        <button type="button" class="btn-primary btn-blue" onclick="document.getElementById('form-create').submit()">Відправити</button>
                        <button type="button" class="btn-border btn-blue">Зберегти</button>
                        <button type="button" class="btn-border btn-red">Видалити</button>
                    @endif
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

            <form action="{{route('app.conclusion.store')}}" id="form-create" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="warranty_claim_id" name="warranty_claim_id" value="{{ $claim->id }}">
                <input type="hidden" name="code_1C" id="code_1C" value="{{ $claim->code_1C }}" required>
                <input type="hidden" name="parent_doc" value="{{ $claim->id }}" required>

                <div class="card-lists">
                    <div class="card-content card-form">
                        <p class="card-title">Загальна інформація</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="number-doc">Номер документу</label>
                                <input type="text" name="number" id="number-doc" value="{{$claim->number}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="date-doc">Дата документу</label>
                                <input type="text" name="date" id="date-doc" value="{{$claim->created_at->format('d.m.Y')}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="autor-name">Відповідальний</label>
                                <input type="text" id="autor-name" value="{{$claim->user->first_name_ru ?? 'Не вказано'}}" readonly>
                            </div>
                            <input type="hidden" name="autor" id="autor-id" value="{{$claim->user->id}}" readonly>
                        </div>
                    </div>
                    <div class="display-grid col-2 gap-8">
                        <div class="card-content card-form">
                            <p class="card-title">Дані покупця</p>
                            <div class="inputs-group one-row">
                                <div class="form-group">
                                    <label for="buyer-name">ПІБ покупця</label>
                                    <input type="text" name="client_name" id="buyer-name" value="{{$claim->client_name}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="buyer-phone">Контактний телефон</label>
                                    <input type="text" name="client_phone" id="buyer-phone" value="{{$claim->client_phone}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card-content card-form">
                            <p class="card-title">Дані того Хто звернувся</p>
                            <button type="button" class="btn-link btn-copy btn-blue"> Копіювати данні покупця</button>
        
                            <div class="inputs-group one-row">
                                <div class="form-group required" data-valid="empty">
                                    <label for="sender-name">ПІБ</label>
                                    <input type="text" name="sender_name" id="sender-name" value="{{$technicalConcluison->sender_name ?? 'Не вказано'}}" {{ $isDisabled }}>
                                    <div class="help-block" data-empty="Required field"></div>
                                </div>
                                <div class="form-group required" data-valid="empty">
                                    <label for="sender-phone">Контактний телефон</label>
                                    <input type="text" name="sender_phone" id="sender-phone" value="{{$technicalConcluison->sender_phone ?? 'Не вказано'}}" {{ $isDisabled }}>
                                    <div class="help-block" data-empty="Required field"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Дані про товар</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="article">Артикул</label>
                                <input type="text" name="product_article" id="article" value="{{$claim->product_article}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="prod-name">Назва виробу</label>
                                <input type="text" name="product_name" id="prod-name" value="{{$claim->product_name}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="factory-number">Заводський номер</label>
                                <input type="text" name="factory_number" id="factory-number" value="{{$claim->factory_number}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="barcode">Штрихкод гарантійного талону</label>
                                <input type="text" name="barcode" id="barcode" value="{{$claim->barcode}}" readonly>
                            </div>
                        </div>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="place-sale">Місце продажу</label>
                                <input type="text" name="point_of_sale" id="place-sale" value="{{$claim->point_of_sale}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="date-sale">Дата продажу</label>
                                <input type="text" name="date_of_sale" id="date-sale" value="27.05.1999" readonly>
                            </div>
                            <div class="form-group required" data-valid="empty">
                                <label for="date-start">Дата звернення в сервісний центр</label>
                                <div class="input-wrapper">
                                    <input type="text" name="date_of_claim" id="date-start" value="{{$technicalConcluison->date_of_claim ?? ''}}" placeholder="00.00.0000" class="_js-datepicker" {{ $isDisabled }}>
                                    <span class="icon-calendar"></span>
                                </div>
                                <div class="help-block" data-empty="Required field"></div>
                            </div>
                            <div class="form-group">
                                <label for="receipt-number">Номер квитанції сервісного центру</label>
                                <input type="text" name="receipt_number" id="receipt-number" value="{{$technicalConcluison->receipt_number ?? ''}}" {{ $isDisabled }}>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Опис дефекту</p>
                        <div class="inputs-group one-row">
                            <div class="form-group required" data-valid="empty">
                                <label for="desc">Точний опис дефекту</label>
                                <textarea name="details" id="desc" placeholder="Точний опис дефекту" rows="3" {{ $isDisabled }}>{{$technicalConcluison->details ?? ''}}</textarea>
                                <div class="help-block" data-empty="Required field"></div>
                            </div>
                            <div class="form-group required" data-valid="empty">
                                <label for="reason">Причина дефекту</label>
                                <textarea name="deteails_reason" id="reason" placeholder="Причина дефекту" rows="3" {{ $isDisabled }}>{{$technicalConcluison->deteails_reason ?? ''}}</textarea>
                                <div class="help-block" data-empty="Required field"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Підтверджуючі фото та інше</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="comment_photo">Коментар</label>
                                <textarea name="comment" id="comment" placeholder="Коментар до заяви" rows="3" {{ $isDisabled }}>{{$technicalConcluison->comment ?? ''}}</textarea>
                            </div>
                            <div class="form-group file required" data-valid="file">
                                <input type="file" name="file[]" id="file" multiple {{ $isDisabled }}>
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
                            <div class="image-preview"></div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Сервісні роботи</p>
                        <div class="display-grid col-2 gap-20">
                            <div class="inputs-group one-column">
                                <div class="form-group default-select">
                                    <label for="product-group">Група товару</label>
                                    <select name="product_group_id" id="product-group" {{ $isDisabled }}>
                                        <option value="-1">Оберіть групу товару</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ ($technicalConcluison->product_group_id ?? '') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="comment-2">Коментар</label>
                                    <textarea name="comment_service" id="comment-2" placeholder="Коментар" rows="3" {{ $isDisabled }}>{{ $technicalConcluison->comment_service ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="inputs-group">
                                <div class="fake-label"></div>
                                <div class="form-group" id="service-works-container">
                                    @foreach($works as $work)
                                        <div class="form-group checkbox">
                                                <input type="checkbox" id="status-{{ $work->id }}" name="status[]" value="{{ $work->id }}" {{$isDisabled}}>
                                                <label for="status-{{ $work->id }}">{{ $work->name }}</label>
                                            </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Використані запчастини</p>
                        <div class="card-group">
                            <div class="table-parts without-action">
                                <div class="table-header">
                            @if($claim->spareParts->isNotEmpty())
                                    <div class="row">
                                        <div class="cell">Артикул</div>
                                        <div class="cell">Назва</div>
                                        <div class="cell">Ціна</div>
                                        <div class="cell">Кількість</div>
                                        <div class="cell">Всього, грн</div>
                                        <div class="cell">Замовити</div>
                                    </div>
                                </div>
                            @endif
    
                                <div class="table-body">
                                    <!-- Existing parts -->
                                    @php
                                        $total = 0;
                                    @endphp
                                        @foreach ($claim->spareParts as $part)
                                            @php
                                                $total += $part->amount_with_vat;
                                            @endphp
                                            <div class="row">
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="hidden" name="spare_parts[{{ $loop->index }}][id]" value="{{ $part->id }}">
                                                        <input type="text" value="{{ $part->product->articul }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" value="{{ $part->product->name }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" value="{{ $part->price_without_vat }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" value="{{ $part->qty }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group">
                                                        <input type="text" value="{{ $part->amount_with_vat }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="cell">
                                                    <div class="form-group checkbox">
                                                        <input type="checkbox" id="parts-{{ $part->id }}" checked disabled>
                                                        <label for="parts-{{ $part->id }}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                </div>
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
                                        <div class="cell">Всього, грн</div>
                                        <div class="cell">Замовити</div>
                                        <div class="cell">Дія</div>
                                    </div>
                                </div>
    
                                <div class="table-body">
                                    <div class="row add-new">
                                        <div class="cell">
                                            <div class="form-group have-icon">
                                                <span class="icon icon-search-active"></span>
                                                <input type="text" id="search-articul" placeholder="XXXXXX-XXX" {{ $isDisabled }}>
                                            </div>
                                        </div>
                                        <div class="cell">
                                            <div class="form-group">
                                                <input type="text" id="part-name" placeholder="Назва" readonly>
                                            </div>
                                        </div>
                                        <div class="cell">
                                            <div class="form-group">
                                                <input type="text" id="part-price" placeholder="Ціна" readonly>
                                            </div>
                                        </div>
                                        <div class="cell">
                                            <div class="form-group _bg-white">
                                                <input type="number" id="part-quantity" placeholder="Кількість" min="1" {{ $isDisabled }}>
                                            </div>
                                        </div>
                                        <div class="cell">
                                            <div class="form-group">
                                                <input type="text" id="part-total" placeholder="Всього, грн" readonly>
                                            </div>
                                        </div>
                                        <div class="cell">
                                            <div class="form-group checkbox">
                                                <input type="checkbox" id="order-part" {{ $isDisabled }}>
                                                <label for="order-part"></label>
                                            </div>
                                        </div>
                                        <div class="cell">
                                            <button type="button" class="btn-primary btn-blue btn-action" id="add-part-btn">
                                                <span class="icon-plus"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="parts-container"></div>
                                </div>
                                <div class="table-footer">
                                    <div class="row">
                                        <div class="cell">Підсумок</div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell"></div>
                                        <div class="cell" id="total-sum">{{ number_format($total, 2, '.', ' ') }}</div>
                                        <div class="cell"></div>
                                    </div>
                                </div>
        
                            </div>
                        </div>
        
                        <div class="card-group">
                            <p class="sub-title">Для пошуку потрібних запчастин  перейдіть за посиланням</p>
                            <div class="display-grid col-2 gap-8">
                                <div class="card-content card-text">
                                    <h2 class="text-underline text-blue">AL-KO</h2>
        
                                    <p>Після відкриття, у лівому верхньому куті виберіть директорію: <span class="text-red fw-600">ERSATZTEILSUCHE.</span>
                                    </p>
                                    <p>
                                        Після переходу на іншу сторінку, в правому кутку в порожнє поле внесіть артикульний номер
                                        виробу, що Вас цікавить (артикульний номер виробу можна подивитися в прайс-листі або на
                                        заводській наклейці).
                                    </p>
                                    <p>
                                        Щоб дізнатися ціну на деталь, відкрийте каталог зап.частин (додаток №3 до договору з
                                        сервісного
                                        обслуговування). Комбінація Ctrl - F відкриває пошукове вікно, куди вноситься артикул
                                        зап.частини.
                                    </p>
                                    <p>
                                        За необхідності можна зберігати і друкувати деталі з інтернет бази. Для цього необхідно
                                        зліва
                                        внизу натиснути кнопку <span class="text-red fw-600">Drucken</span>, після чого вибрати
                                        потрібну вам сторінку.
                                    </p>
                                </div>
                                <div class="card-content card-text">
                                    <h2 class="text-underline text-blue">B&S</h2>
        
                                    <p>
                                        Дотримуючись наведених інструкцій, знайдіть необхідну деталь для вашого продукту Briggs &
                                        Stratton
                                    </p>
        
                                </div>
                            </div>
                        </div>
        
                        <div class="card-group _mb0">
                            <div class="display-grid col-2 gap-8">
                                <div class="form-group _mb0">
                                    <label for="comment-3">Коментар</label>
                                    <textarea name="comment_part" id="comment-3" placeholder="Не знайшли потрібні запчастини? Опишіть вашу проблему" rows="3" {{$isDisabled}}>{{$technicalConcluison->comment_part ?? ''}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    
        </div>
    </div>


    <div class="modal modal-gallery js-modal js-modal-gallery custom-scrollbar">
        <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
        <div class="modal-content ">
            <button type="button" class="btn-border btn-blue btn-only-icon gallery-btn gallery-prev">
                <span class="icon-arrow-left"></span>
            </button>
            <button type="button" class="btn-border btn-blue btn-only-icon gallery-btn gallery-next">
                <span class="icon-arrow-left"></span>
            </button>
            <div class="swiper swiper-gallery">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <picture>
                            <source srcset="./img/delete/gallery-img.webp" type="image/webp">
                            <img src="./img/delete/gallery-img.jpg" loading="lazy" alt="" title="">
                        </picture>
                    </div>
                    <div class="swiper-slide">
                        <picture>
                            <source srcset="./img/delete/gallery-img.webp" type="image/webp">
                            <img src="./img/delete/gallery-img.jpg" loading="lazy" alt="" title="">
                        </picture>
                    </div>
                    <div class="swiper-slide">
                        <picture>
                            <source srcset="./img/delete/gallery-img.webp" type="image/webp">
                            <img src="./img/delete/gallery-img.jpg" loading="lazy" alt="" title="">
                        </picture>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
    
        </div>
    </div>
    
    
    <div id="datepicker-container"></div>


    <!-- Код для генерації сервісних робот для певної групи товарів -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productGroupSelect = document.getElementById('product-group');
        const serviceWorksContainer = document.getElementById('service-works-container');

        productGroupSelect.addEventListener('change', function() {
            const groupId = this.value;

            // Очистка контейнера, якщо не вибрана група
            if (groupId === '-1') {
                serviceWorksContainer.innerHTML = '';
                return;
            }

            // ajax запит
            fetch(`/service/${groupId}`)
                .then(response => response.json())
                .then(data => {
                    serviceWorksContainer.innerHTML = ''; // Очистка контейнера перед новим відображенням

                    // Додавання нових елементів 
                    data.forEach(work => {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.id = `status-${work.id}`;
                        checkbox.name = 'status[]';
                        checkbox.value = work.id;

                        const label = document.createElement('label');
                        label.htmlFor = `status-${work.id}`;
                        label.textContent = work.name;

                        const div = document.createElement('div');
                        div.classList.add('form-group', 'checkbox');
                        div.appendChild(checkbox);
                        div.appendChild(label);

                        serviceWorksContainer.appendChild(div);
                    });
                })
                .catch(error => console.error('Error fetching service works:', error));
        });
    });

</script>

<!-- Код для пошуку запчастини -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.table-body .add-new .form-group.have-icon input');
        const partsContainer = document.getElementById('parts-container');
        const totalSumElement = document.getElementById('total-sum');

        let totalSum = {{ $total }};

        searchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const articul = this.value;

                if (articul.length >= 3) {
                    fetch(`/parts/${articul}`)
                        .then(response => response.json())
                        .then(data => {
                            let rows = '';
                            data.data.forEach(part => {
                                if (part.product_prices) {
                                    const priceWithoutVat = part.product_prices.recommended_price / 1.2;
                                    const amountWithoutVat = priceWithoutVat * 1; // начальное количество
                                    const amountVat = amountWithoutVat * 0.2;
                                    const amountWithVat = amountWithoutVat + amountVat;

                                    rows += `
                                        <div class="row">
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input type="text" value="${part.articul}" readonly class="part-articul">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" value="${part.name}" readonly class="part-name">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" value="${part.product_prices.recommended_price}" readonly class="part-price">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group _bg-white">
                                                    <input type="number" value="1" class="part-quantity">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group">
                                                    <input type="text" value="${amountWithVat.toFixed(2)}" readonly class="part-total">
                                                </div>
                                            </div>
                                            <div class="cell">
                                                <div class="form-group checkbox">
                                                    <input type="checkbox" id="parts-${part.id}">
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
                                }
                            });

                            partsContainer.innerHTML = rows;

                            document.querySelectorAll('.part-quantity').forEach(input => {
                                input.addEventListener('input', function() {
                                    const quantity = parseFloat(this.value) || 0;
                                    const price = parseFloat(this.closest('.row').querySelector('.part-price').value) || 0;
                                    const total = quantity * price;
                                    this.closest('.row').querySelector('.part-total').value = total.toFixed(2);
                                });
                            });

                            document.querySelectorAll('.add-part-btn').forEach(button => {
                                button.addEventListener('click', function() {
                                    const row = this.closest('.row');
                                    const articul = row.querySelector('.part-articul').value;
                                    const name = row.querySelector('.part-name').value;
                                    const price = parseFloat(row.querySelector('.part-price').value);
                                    const quantity = parseInt(row.querySelector('.part-quantity').value);
                                    const total = parseFloat(row.querySelector('.part-total').value);

                                    const warrantyClaimsId = {{ $claim->id }};
                                    const lineNumber = partsContainer.children.length + 1;
                                    const spareParts = articul;
                                    const priceWithoutVat = price / 1.2;
                                    const amountWithoutVat = priceWithoutVat * quantity;
                                    const amountVat = amountWithoutVat * 0.2;
                                    const amountWithVat = amountWithoutVat + amountVat;

                                    fetch('/warranty-claim-spareparts', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({
                                            warranty_claim_id: warrantyClaimsId,
                                            line_number: lineNumber,
                                            spare_parts: spareParts,
                                            qty: quantity,
                                            price_without_vat: priceWithoutVat.toFixed(2),
                                            amount_without_vat: amountWithoutVat.toFixed(2),
                                            amount_vat: amountVat.toFixed(2),
                                            amount_with_vat: amountWithVat.toFixed(2)
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.message) {
                                            totalSum += amountWithVat;
                                            totalSumElement.textContent = totalSum.toFixed(2);

                                            const newRow = `
                                                <div class="row">
                                                    <div class="cell">
                                                        <div class="form-group">
                                                            <input type="text" value="${articul}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="cell">
                                                        <div class="form-group">
                                                            <input type="text" value="${name}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="cell">
                                                        <div class="form-group">
                                                            <input type="text" value="${priceWithoutVat.toFixed(2)}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="cell">
                                                        <div class="form-group">
                                                            <input type="text" value="${quantity}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="cell">
                                                        <div class="form-group">
                                                            <input type="text" value="${amountWithVat.toFixed(2)}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="cell">
                                                        <div class="form-group checkbox">
                                                            <input type="checkbox" checked disabled>
                                                            <label></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                            partsContainer.insertAdjacentHTML('beforeend', newRow);
                                            row.remove();
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                                });
                            });
                        })
                        .catch(error => console.error('Error fetching parts:', error));
                }
            }
        });
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js"></script>
<script src="/cdn/js/popper.min.js"></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<script src="/cdn/js/custom-select.js"></script>
<script src="/js/components.js?v=002"></script>
<script src="/js/main.js?v=002"></script>
</x-layouts.base>