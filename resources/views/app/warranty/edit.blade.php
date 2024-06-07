<x-layouts.base>

    <div class="main" id="main">
        <div class="page-warranty-create">
            <div class="page-name sticky">
                <h1>Гарантійна заява</h1>
                <div class="btns">
                    <button type="button" class="btn-primary btn-blue">Відправити</button>
                    <button type="button" class="btn-border btn-blue">Зберегти</button>
                    <button type="button" class="btn-border btn-red">Видалити</button>
                </div>
            </div>
    
            <div class="card-lists">
                <div class="card-content card-form">
                    <p class="card-title">Загальна інформація</p>
                    <div class="inputs-group one-row">
                        <div class="form-group">
                            <label for="number-doc">Номер документу</label>
                            <input type="text" id="number-doc" value="{{$claim->number}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="date-doc">Дата документу</label>
                            <input type="text" id="date-doc" value="{{$claim->created_at->format('d.m.Y')}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="author">Відповідальний</label>
                            <input type="text" id="author" value="{{$claim->author ?? 'Не вказано'}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="display-grid col-2 gap-8">
                    <div class="card-content card-form">
                        <p class="card-title">Дані покупця</p>
                        <div class="inputs-group one-row">
                            <div class="form-group">
                                <label for="buyer-name">ПІБ покупця</label>
                                <input type="text" id="buyer-name" value="{{$claim->client_name}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="buyer-phone">Контактний телефон</label>
                                <input type="text" id="buyer-phone" value="{{$claim->client_phone}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-content card-form">
                        <p class="card-title">Дані того Хто звернувся</p>
                        <button type="button" class="btn-link btn-copy btn-blue"> Копіювати данні покупця</button>
    
                        <div class="inputs-group one-row">
                            <div class="form-group required" data-valid="empty">
                                <label for="sender-name">ПІБ</label>
                                <input type="text" id="sender-name" value="Прізвище Ім'я По батькові">
                                <div class="help-block" data-empty="Required field"></div>
                            </div>
                            <div class="form-group required" data-valid="empty">
                                <label for="sender-phone">Контактний телефон</label>
                                <input type="text" id="sender-phone" value="+380501234567">
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
                            <input type="text" id="article" value="{{$claim->product_article}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="prod-name">Назва виробу</label>
                            <input type="text" id="prod-name" value="{{$claim->product_name}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="factory-number">Заводський номер</label>
                            <input type="text" id="factory-number" value="{{$claim->factory_number}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="barcode">Штрихкод гарантійного талону</label>
                            <input type="text" id="barcode" value="{{$claim->barcode}}" readonly>
                        </div>
                    </div>
                    <div class="inputs-group one-row">
                        <div class="form-group">
                            <label for="place-sale">Місце продажу</label>
                            <input type="text" id="place-sale" value="{{$claim->point_of_sale}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="date-sale">Дата продажу</label>
                            <input type="text" id="date-sale" value="27.05.1999" readonly>
                        </div>
                        <div class="form-group required" data-valid="empty">
                            <label for="date-start">Дата звернення в сервісний центр</label>
                            <div class="input-wrapper">
                                <input type="text" id="date-start" placeholder="00.00.0000" class="_js-datepicker">
                                <span class="icon-calendar"></span>
                            </div>
                            <div class="help-block" data-empty="Required field"></div>
                        </div>
                        <div class="form-group">
                            <label for="receipt-number">Номер квитанції сервісного центру</label>
                            <input type="text" id="receipt-number" value="0000000000">
                        </div>
                    </div>
                </div>
                <div class="card-content card-form">
                    <p class="card-title">Опис дефекту</p>
                    <div class="inputs-group one-row">
                        <div class="form-group required" data-valid="empty">
                            <label for="desc">Точний опис дефекту</label>
                            <textarea id="desc" placeholder="Точний опис дефекту" rows="3"></textarea>
                            <div class="help-block" data-empty="Required field"></div>
                        </div>
                        <div class="form-group required" data-valid="empty">
                            <label for="reason">Причина дефекту</label>
                            <textarea id="reason" placeholder="Причина дефекту" rows="3"></textarea>
                            <div class="help-block" data-empty="Required field"></div>
                        </div>
                    </div>
                </div>
                <div class="card-content card-form">
                    <p class="card-title">Підтверджуючі фото та інше</p>
                    <div class="inputs-group one-row">
                        <div class="form-group">
                            <label for="comment">Коментар</label>
                            <textarea id="comment" placeholder="Коментар до заяви" rows="3"></textarea>
                        </div>
                        <div class="form-group file required" data-valid="file">
                            <input type="file" id="file" multiple>
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
                                <label for="comment">Група товару</label>
                                <select name="" id="">
                                    <option value="-1">Якась група товару</option>
                                    <option value="1">Група товару - 1</option>
                                    <option value="2">Група товару - 2</option>
                                    <option value="3">Група товару - 3</option>
                                    <option value="4">Група товару - 4</option>
                                    <option value="5">Група товару - 5</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment-2">Коментар</label>
                                <textarea id="comment-2" placeholder="Коментар" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="inputs-group">
                            <div class="fake-label"></div>
                            <div class="form-group">
                                <div class="form-group checkbox">
                                    <input type="checkbox" id="status-1" name="status[]" value="status-1" checked disabled>
                                    <label for="status-1">Можливий сервіс, характерних обраній групі товару</label>
                                </div>
                                <div class="form-group checkbox">
                                    <input type="checkbox" id="status-2" name="status[]" value="status-2">
                                    <label for="status-2">Lorem ipsum dolor sit amet consectetur. Dictumst feugiat mauris
                                        auctor sit. Vulputate aenean amet eget id ultricies. E</label>
                                </div>
                                <div class="form-group checkbox _mb0">
                                    <input type="checkbox" id="status-3" name="status[]" value="status-3">
                                    <label for="status-3">Lorem ipsum dolor sit amet consectetur </label>
                                </div>
                                <div class="form-group checkbox _mb0">
                                    <input type="checkbox" id="status-4" name="status[]" value="status-3">
                                    <label for="status-4">Lorem ipsum dolor sit amet consectetur </label>
                                </div>
                                <div class="form-group checkbox _mb0">
                                    <input type="checkbox" id="status-5" name="status[]" value="status-3">
                                    <label for="status-5">Lorem ipsum dolor sit amet consectetur </label>
                                </div>
                                <div class="form-group checkbox _mb0">
                                    <input type="checkbox" id="status-6" name="status[]" value="status-3">
                                    <label for="status-6">Lorem ipsum dolor sit amet consectetur </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-content card-form">
                    <p class="card-title">Використані запчастини</p>
    
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
                                            <input type="text" placeholder="XXXXXX-XXX">
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" placeholder="Назва" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" placeholder="Ціна" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group _bg-white">
                                            <input type="text" placeholder="Кількість" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" placeholder="Всього, грн" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group checkbox">
                                            <input type="checkbox" id="parts-0">
                                            <label for="parts-0"></label>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <button type="button" class="btn-primary btn-blue btn-action">
                                            <span class="icon-plus"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell">
                                        <div class="form-group _bg-white">
                                            <input type="text" value="000000-000" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" value="Назва - посилання на запчастину" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" value="10 0000" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group _bg-white">
                                            <input type="text" value="2" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" value="20 000" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group checkbox">
                                            <input type="checkbox" id="parts-1">
                                            <label for="parts-1"></label>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <button type="button" class="btn-border btn-red btn-action">
                                            <span class="icon-minus"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell">
                                        <div class="form-group _bg-white">
                                            <input type="text" value="000000-000" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" value="Назва - посилання на запчастину" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" value="10 0000" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group _bg-white">
                                            <input type="text" value="2" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group">
                                            <input type="text" value="20 000" readonly>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="form-group checkbox">
                                            <input type="checkbox" id="parts-2">
                                            <label for="parts-2"></label>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <button type="button" class="btn-border btn-red btn-action">
                                            <span class="icon-minus"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-footer">
                                <div class="row">
                                    <div class="cell">Підсумок</div>
                                    <div class="cell"></div>
                                    <div class="cell"></div>
                                    <div class="cell"></div>
                                    <div class="cell">80 000</div>
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
                                <textarea id="comment-3" placeholder="Не знайшли потрібні запчастини? Опишіть вашу проблему" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
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




<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js" ></script>
<script src="/cdn/js/popper.min.js" ></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<!--<script src="/cdn/js/custom-select.js"></script>-->
<script src="/js/components.js?v=002"></script>

<script src="/js/main.js?v=002"></script>

</x-layouts.base>