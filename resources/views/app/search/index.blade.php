<x-layouts.base>
    
    @if($talons->count() > 0)
    @if($talons->count() == 1)
        @php
            $talon = $talons->first();
            header("Location: " . route('app.warranty.edit', ['id' => $talon->id]));
            exit();
        @endphp
    @endif

<div class="main" id="main">
    <div class="page-search-result">
        <div class="page-name">
            <h1>Пошук</h1>
            <p class="total-found">
                Знайдено документів: <strong>{{ $talons->count() }}</strong>
            </p>
        </div>

       <div class="card-content card-table">
    <div class="table-wrapper">
        <table class="table table-actions layout-fixed">
            <thead class="thead">
                <tr class="tr">
                    <th class="th">Назва товару</th>
                    <th class="th">Покупець</th>
                    <th class="th">Штрихкод талона</th>
                    <th class="th">Заводський номер</th>
                    <th class="th">Телефон</th>
                    <th class="th">Вартість товару, грн</th>
                    <th class="th _empty"></th>
                    <th class="th">Дії</th>
                </tr>
            </thead>
            <tbody class="tbody">
                @foreach ($talons as $talon)
                <tr class="tr">
                    <td class="td">{{ empty($talon->product->name) ? 'Не вказано' : $talon->product->name }}</td>
                    <td class="td">{{ empty($talon->customer) ? 'Не вказано' : $talon->customer }}</td>
                    <td class="td">{{ empty($talon->barcode) ? 'Не вказано' : $talon->barcode }}</td>
                    <td class="td">{{ empty($talon->factory_number) ? 'Не вказано' : $talon->factory_number }}</td>
                    <td class="td">{{ empty($talon->phone) ? 'Не вказано' : $talon->phone }}</td>
                    <td class="td">{{ empty($talon->product->productPrices->recommended_price) ? 'Не вказано' : $talon->product->productPrices->recommended_price }}</td>
                    <td class="td _empty"></td>
                    <td class="td" style="">
                        <a href="{{ route('app.warranty.edit', ['id' => $talon->id]) }}" class="btn-action icon-info"></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    </div>
</div>

<div class="modal-overlay"></div>

<div class="modal modal-chat js-modal js-modal-chat">
    <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
    <div class="modal-content">
        <div class="chat-header">
            <p class="modal-title">
                Коментарі до документу
            </p>
            <div class="chat-desc">
                <p><strong>Документ:</strong> Назва документу</p>
                <p><strong>Ваш менеджер/дилер:</strong> Іванов Іван Іванович </p>
            </div>
        </div>

        <!-- if empty, add class "_empty"-->
        <div class="chat-main custom-scrollbar">
            <div class="chat-main__wrapper ">
                <!--                <div class="chat-empty">-->
                <!--                    <p>До цього документу поки що немає коментарів</p>-->
                <!--                </div>-->

                <div class="message sender">
                    <div class="message-controls">
                        <button type="button" class="btn-delete"></button>
                        <ul class="controls-list">
                            <li>
                                <button type="button" class="icon-edit">Редагувати</button>
                            </li>
                            <li>
                                <button type="button" class="icon-trash">Видалити</button>
                            </li>
                        </ul>
                    </div>
                    <p class="message-author">Петров Петр Петрович (Ви)</p>
                    <div class="message-text">
                        Lorem ipsum dolor sit amet consectetur. Sit sed pretium donec aliquet viverra proin. Metus quam
                        integer commodo massa fringilla nunc sit montes.
                    </div>
                    <div class="message-date">12:00, 01.01.2024</div>
                </div>
                <div class="message">
                    <p class="message-author">Іванов Іван Іванович (Менеджер)</p>
                    <div class="message-text">
                        Lorem ipsum dolor sit amet consectetur. Sit sed pretium donec aliquet viverra proin. Metus quam
                        integer commodo massa fringilla nunc sit montes.
                    </div>
                    <div class="message-date">12:00, 01.01.2024</div>
                </div>
            </div>
        </div>
        <div class="chat-footer">
            <div class="form-group">
                <input type="text" name="chat-text" placeholder="Ваш текст">
            </div>
            <button type="button" class="btn-primary btn-blue">Надіслати</button>
        </div>
    </div>
</div>

<div class="modal modal-manager js-modal js-modal-switch-manager">
    <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
    <div class="modal-content ">
        <div class="manager-header">
            <p class="modal-title">Оберіть менеджера</p>

            <div class="form-group">
                <span class="icon-search"></span>
                <input type="text" placeholder="пошук" name="manager-search">
            </div>
        </div>
        <div class="manager-body custom-scrollbar">
            <div class="form-group radio">
                <input type="radio" id="manager-1" name="manager">
                <label for="manager-1">Прізвище Ім'я По батькові</label>
            </div>
            <div class="form-group radio">
                <input type="radio" id="manager-2" name="manager">
                <label for="manager-2">Прізвище Ім'я По батькові</label>
            </div>
            <div class="form-group radio">
                <input type="radio" id="manager-3" name="manager">
                <label for="manager-3">Прізвище Ім'я По батькові</label>
            </div>
            <div class="form-group radio">
                <input type="radio" id="manager-4" name="manager">
                <label for="manager-4">Прізвище Ім'я По батькові</label>
            </div>
            <div class="form-group radio">
                <input type="radio" id="manager-5" name="manager">
                <label for="manager-5">Прізвище Ім'я По батькові</label>
            </div>
            <div class="form-group radio">
                <input type="radio" id="manager-6" name="manager">
                <label for="manager-6">Прізвище Ім'я По батькові</label>
            </div>
            <div class="form-group radio">
                <input type="radio" id="manager-7" name="manager">
                <label for="manager-7">Прізвище Ім'я По батькові</label>
            </div>
        </div>
        <div class="manager-footer">
            <button type="button" class="btn-primary btn-blue">Переназначити менеджера</button>
        </div>
    </div>
</div>


<div class="modal modal-alert js-modal js-modal-switch-status">
    <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
    <div class="modal-content ">
        <p class="modal-title">Змінити статус заяви на помилковий?</p>
        <div class="modal-desc">
            <p>Ви впевнені, що хочете Змінити статус документу “Гарантійна заява” на помилковий?</p>
        </div>
        <div class="btns">
            <button type="button" class="btn-border btn-blue">Заява вірна</button>
            <button type="button" class="btn-primary btn-red">Заява помилкова</button>
        </div>
    </div>
</div>

<div class="modal modal-alert modal-alert-delete js-modal js-modal-delete">
    <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
    <div class="modal-content ">
        <p class="modal-title">Видалити Гарантійну заяву?</p>
        <div class="btns">
            <button type="button" class="btn-border btn-blue">Зберегти</button>
            <button type="button" class="btn-primary btn-red">Видалити</button>
        </div>
    </div>
</div>

<div class="modal modal-document js-modal js-modal-import-document custom-scrollbar">
    <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
    <div class="modal-content ">
        <p class="modal-title">Імпорт документу</p>

        <form action="">
            <div class="form-group required" data-valid="empty">
                <label for="doc-name">Назва документа</label>
                <input type="text" id="doc-name" name="doc-name" placeholder="Назва">
                <div class="help-block" data-empty="Required field"></div>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="doc-type">Група товару</label>
                <select name="" id="doc-type">
                    <option value="-1">Оберіть варіант</option>
                    <option value="1">Варіант - 1</option>
                    <option value="2">Варіант - 2</option>
                    <option value="3">Варіант - 3</option>
                    <option value="4">Варіант - 4</option>
                    <option value="5">Варіант - 5</option>
                </select>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="prod-cat">Група товару</label>
                <select name="" id="prod-cat">
                    <option value="-1">Оберіть варіант</option>
                    <option value="1">Варіант - 1</option>
                    <option value="2">Варіант - 2</option>
                    <option value="3">Варіант - 3</option>
                    <option value="4">Варіант - 4</option>
                    <option value="5">Варіант - 5</option>
                </select>
            </div>

            <div class="form-group file">
                <label for="doc-file" class="btn-border btn-blue">Обрати файл ( Word / PDF / Excel) </label>
                <input type="file" id="doc-file">
            </div>

            <div class="file-name-preview">
                Lorem ipsum dolor sit amet consectetur. pdf
            </div>

            <button type="submit" class="btn-primary btn-blue">Завантажити документ</button>

        </form>
    </div>
</div>

<div class="modal modal-document js-modal js-modal-edit-document custom-scrollbar">
    <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
    <div class="modal-content ">
        <p class="modal-title">Редагування документа</p>

        <form action="">
            <div class="form-group required" data-valid="empty">
                <label for="doc-name-1">Назва документа</label>
                <input type="text" id="doc-name-1" name="doc-name" value="Назва">
                <div class="help-block" data-empty="Required field"></div>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="doc-type-1">Група товару</label>
                <select name="" id="doc-type-1">
                    <option value="-1">Оберіть варіант</option>
                    <option value="1" selected>Варіант - 1</option>
                    <option value="2">Варіант - 2</option>
                    <option value="3">Варіант - 3</option>
                    <option value="4">Варіант - 4</option>
                    <option value="5">Варіант - 5</option>
                </select>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="prod-cat-1">Група товару</label>
                <select name="" id="prod-cat-1">
                    <option value="-1">Оберіть варіант</option>
                    <option value="1">Варіант - 1</option>
                    <option value="2">Варіант - 2</option>
                    <option value="3" selected>Варіант - 3</option>
                    <option value="4">Варіант - 4</option>
                    <option value="5">Варіант - 5</option>
                </select>
            </div>

            <div class="form-group file">
                <label for="doc-file-1" class="btn-border btn-blue">Обрати файл ( Word / PDF / Excel) </label>
                <input type="file" id="doc-file-1">
            </div>

            <div class="file-name-preview">
                Lorem ipsum dolor sit amet consectetur. pdf
            </div>

            <button type="submit" class="btn-primary btn-red">Зберегти зміни</button>

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

@else
<div class="search-result">
    <div class="text">
        <p class="h1">Гарантійного талона за вказаними параметрами не знайдено.</p>
        <p>
            Перевірте правильність введених даних, якщо введено вірні дані, але гарантійний талон не знайдено -
            покупцеві необхідно звернутися за місцем продажу товару
        </p>
        <a style="color: white" href="{{route('app.home.index')}}">
            <button type="button" class="btn-primary btn-blue">
                Зрозуміло
            </button>
        </a>
    </div>
</div>


@endif


<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js" ></script>
<script src="/cdn/js/popper.min.js" ></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<!--<script src="/cdn/js/custom-select.js"></script>-->
<script src="/js/components.js?v=002"></script>

<script src="/js/main.js?v=002"></script>
</x-layouts.base>
