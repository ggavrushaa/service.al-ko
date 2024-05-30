<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="widdiv=device-widdiv, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Головна</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
      rel="stylesheet">


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
<link rel="stylesheet" href="/cdn/css/swiper-bundle.min.css">
<link rel="stylesheet" href="./css/components.css?v=002">
</head>
<body class="preload">


<header data-lp>
    <!-- show-results, показать результати пошуку -->
    <div class="search-block show-results">
        <div class="search-block__result">
            <span class="icon icon-search"></span>
            <div class="placeholder">
                <div class="placeholder-item">
                    00000000000000000
                    <span class="icon-close-fill"></span>
                </div>
                <div class="placeholder-item">
                    00000000000000000
                    <span class="icon-close-fill"></span>
                </div>
            </div>
            <div class="clear-all icon-close-fill"></div>
            <div class="arrow"></div>
        </div>
        <form class="search-form" action="{{ route('app.search') }}" method="GET">
            <div class="form-group horizontal">
                <label for="barcode">Штрихкод гарантійного талона</label>
                <div class="input-wrapper">
                    <input type="text" id="barcode" name="barcode" placeholder="Вкажіть Штрихкод">
                    <div class="help-block">Required field</div>
                    <button type="button" class="clear-input icon-close-fill"></button>
                </div>
            </div>
            <div class="form-group horizontal _mb0">
                <label for="number">Заводський номер гарантійного товару</label>
                <div class="input-wrapper">
                    <input type="text" id="number" name="number" placeholder="Вкажіть Заводський номер">
                    <div class="help-block">Required field</div>
                    <button type="button" class="clear-input icon-close-fill"></button>
                </div>
            </div>
            <div class="btns">
                <button class="btn-border btn-blue" type="button">Очистити</button>
                <button class="btn-primary btn-blue" type="button">Пошук123</button>
            </div>
        </form>
    </div>
    <div class="user-header">
        <div class="user-info">
            <img src="./img/components/user-undefined.svg" alt="">
            <div class="user-name">Іванов Іван Іванович</div>
            <div class="user-role">Роль</div>

            <button type="button" class="icon-arrow-dropdown"></button>
        </div>
        <div class="user-header__dropdown">
            <div class="user-header__dropdown-content">
                <div class="dropdown-top">
                    <div class="user-name">Іванов Іван Іванович</div>
                    <div class="user-role">Роль</div>
                </div>
                <div class="dropdown-footer">
                    <a href="" class="btn-primary btn-blue" type="button">Вийти</a>
                </div>
            </div>
        </div>
    </div>
</header>


<aside class="sidebar">
    <button class="btn-size-holder"></button>
    <div class="sidebar-content custom-scrollbar">
        <a href="/home.html" class="logo">
            <img src="./img/components/logo.svg" alt="">
        </a>
        <div class="lists">
            <div class="list-group">
                <ul>
                    <li class="active">
                        <a href="./home.html" class="link">
                            <span class="icon icon-search-active"></span>
                            <span class="text">Пошук</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="list-group">
                <p class="list-group__title">Журнали</p>
                <ul>
                    <li class="">
                        <a href="./warranty.html" class="link">
                            <span class="icon icon-docs-in-folders"></span>
                            <span class="text">Гарантійні заяви</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="" class="link">
                            <span class="icon icon-docs"></span>
                            <span class="text">Акти технічної експертизи</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="list-group">
                <p class="list-group__title">Інше</p>
                <ul>
                    <li class="">
                        <a href="" class="link">
                            <span class="icon icon-app"></span>
                            <span class="text">Звірка компенсацій</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="" class="link">
                            <span class="icon icon-folder"></span>
                            <span class="text">Документація</span>
                        </a>
                    </li>
                    <li class="have-sublist js-accordion ">
                        <button type="button" class="link js-accordion-btn">
                            <span class="icon icon-book"></span>
                            <span class="text">Довідники</span>
                            <span class="btn-open"></span>
                        </button>
                        <ul class="sublist js-accordion-content">
                            <li class="">
                                <a href="" class="link">Коди дефектів</a>
                            </li>
                            <li>
                                <a href="" class="link">Коди симптомів</a>
                            </li>
                            <li>
                                <a href="" class="link">Сервісні роботи</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sidebar-footer">
            <p>AL-KO Copyright © 2023 </p>
        </div>
    </div>
    <div class="smaller-version custom-scrollbar">
        <a href="/home.html" class="logo">
            <img src="./img/components/logo.svg" alt="">
        </a>
        <div class="lists">
            <div class="list-group">
                <ul>
                    <li class="active">
                        <a href="./home.html" class="link js-tooltip"
                           data-text="Пошук"
                           data-offset="0,16"
                           data-placement="right"
                        >
                            <span class="icon icon-search-active"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="list-group">
                <ul>
                    <li class="">
                        <a href="./warranty.html" class="link js-tooltip"
                           data-text="Гарантійні заяви"
                           data-offset="0,16"
                           data-placement="right"
                        >
                            <span class="icon icon-docs-in-folders"></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="" class="link js-tooltip"
                           data-text="Акти технічної експертизи"
                           data-offset="0,16"
                           data-placement="right"
                        >
                            <span class="icon icon-docs"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="list-group">
                <ul>
                    <li class="">
                        <a href="" class="link js-tooltip"
                           data-text="Звірка компенсацій"
                           data-offset="0,16"
                           data-placement="right"
                        >
                            <span class="icon icon-app"></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="" class="link js-tooltip"
                           data-text="Документація"
                           data-offset="0,16"
                           data-placement="right"
                        >
                            <span class="icon icon-folder"></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="" class="link js-tooltip"
                           data-text="Довідники"
                           data-offset="0,16"
                           data-placement="right"
                        >
                            <span class="icon icon-book"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>

<div class="main" id="main">
    <div class="page-search-result">
        <div class="page-name">
            <h1>Пошук</h1>
            <p class="total-found">
                Знайдено документів: <strong>2</strong>
            </p>
        </div>
        <div class="card-content card-table">
            <div class="table-wrapper">
                <div class="table table-actions layout-fixed">
                    <div class="thead">
                        <div class="tr">
                            <div class="th">Назва товару</div>
                            <div class="th">Покупець</div>
                            <div class="th">Штрихкод талона</div>
                            <div class="th">Заводський номер</div>
                            <div class="th">Телефон</div>
                            <div class="th">Вартість товару, грн</div>
                            <div class="th _empty"></div>
                            <div class="th">Дії</div>
                        </div>
                    </div>
                    <div class="tbody">
                        {{-- <div class="tr">
                            <div class="td">
                                Lorem ipsum dolor sit amet consectetur.
                            </div>
                            <div class="td">Веретенніков Костянтин Петрович</div>
                            <div class="td">00000000000000000</div>
                            <div class="td">00000000000000000</div>
                            <div class="td">+38050-123-45-67</div>
                            <div class="td">000 000</div>
                            <div class="td _empty"></div>
                            <div class="td">
                                <a href="" class="btn-action icon-info"></a>
                            </div>
                        </div> --}}
                        @foreach ($claims as $claim)
                        <tr>
                            <td>{{ $claim->code_1C }}</td>
                            <td>{{ $claim->number }}</td>
                            <td>{{ $claim->barcode }}</td>
                            <td>{{ $claim->factory_number }}</td>
                            <td>{{ $claim->autorUser->first_name_ru }}</td>
                            <!-- Добавьте другие поля по мере необходимости -->
                        </tr>
                    @endforeach
                    </div>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js" ></script>
<script src="/cdn/js/popper.min.js" ></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<!--<script src="/cdn/js/custom-select.js"></script>-->
<script src="/js/components.js?v=002"></script>

<script src="/js/main.js?v=002"></script>
</body>
</html>
