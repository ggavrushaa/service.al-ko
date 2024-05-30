<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="widdiv=device-widdiv, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Журнал гарантійних заяв</title>

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
        <form class="search-form">
            <div class="form-group horizontal">
                <label for="barcode">Штрихкод гарантійного талона</label>
                <div class="input-wrapper">
                    <input type="text" id="barcode" placeholder="Вкажіть Штрихкод">
                    <div class="help-block">Required field</div>
                    <button type="button" class="clear-input icon-close-fill"></button>
                </div>
            </div>
            <div class="form-group horizontal _mb0">
                <label for="number">Заводський номер гарантійного товару</label>
                <div class="input-wrapper">
                    <input type="text" id="number" placeholder="Вкажіть Заводський номер">
                    <div class="help-block">Required field</div>
                    <button type="button" class="clear-input icon-close-fill"></button>
                </div>
            </div>
            <div class="btns">
                <button class="btn-border btn-blue" type="button">Очистити</button>
                <button class="btn-primary btn-blue" type="button">Пошук</button>
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
                    <li class="">
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
                    <li class="active">
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
                    <li class="">
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
                    <li class="active">
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
                    <li class="active">
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
    <div class="page-warranty">
        <div class="page-name">
            <h1>Гарантійні заяви</h1>
            <div class="btns">
                <button type="button" class="btn-primary btn-blue icon-filters _js-show-filters">Фільтри</button>
            </div>
        </div>

    <div class="filters custom-scrollbar">
        <a href="./home.html" class="logo">
            <img src="./img/components/logo.svg" alt="">
        </a>
    <div class="filters-title">
        <p>Фільтри таблиці</p>
        <button type="button" class="icon-close-fill _js-show-filters"></button>
    </div>

    <div class="filters-main">
        <div class="filter-group js-accordion">
            <div class="filter-group__head js-accordion-btn">
                <p>Заяви за період</p>
                <button type="button" class="icon-arrow-dropdown"></button>
            </div>
            <div class="filter-group__content js-accordion-content">
                <div class="filter-group__content__wrapper">
                    <div class="form-group horizontal">
                        <label for="date-start">З</label>
                        <div class="input-wrapper">
                            <input type="text" id="date-start" placeholder="дд.мм.рррр" class="_js-datepicker">
                            <span class="icon-calendar"></span>
                        </div>
                    </div>
                    <div class="form-group horizontal _mb0">
                        <label for="date-end">по</label>
                        <div class="input-wrapper">
                            <input type="text" id="date-end" placeholder="дд.мм.рррр" class="_js-datepicker">
                            <span class="icon-calendar"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-group js-accordion">
            <div class="filter-group__head js-accordion-btn">
                <p>Артикул </p>
                <button type="button" class="icon-arrow-dropdown"></button>
            </div>
            <div class="filter-group__content js-accordion-content">
                <div class="filter-group__content__wrapper">
                    <div class="form-group _mb0">
                        <input type="text" id="article" placeholder="Артикул">
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-group js-accordion">
            <div class="filter-group__head js-accordion-btn">
                <p>Статус</p>
                <button type="button" class="icon-arrow-dropdown"></button>
            </div>
            <div class="filter-group__content js-accordion-content">
                <div class="filter-group__content__wrapper">
                    <div class="form-group checkbox">
                        <input type="checkbox" id="status-1" name="status[]" value="status-1">
                        <label for="status-1">Відправлений</label>
                    </div>
                    <div class="form-group checkbox">
                        <input type="checkbox" id="status-2" name="status[]" value="status-2">
                        <label for="status-2">Розглядається</label>
                    </div>
                    <div class="form-group checkbox _mb0">
                        <input type="checkbox" id="status-3" name="status[]" value="status-3">
                        <label for="status-3">Затверджений</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="filter-group js-accordion">
            <div class="filter-group__head js-accordion-btn">
                <p>Автор документу</p>
                <button type="button" class="icon-arrow-dropdown"></button>
            </div>
            <div class="filter-group__content js-accordion-content">
                <div class="filter-group__content__wrapper">
                    <div class="form-group default-select">
                        <select name="" id="author">
                            <option selected value="-1">Автор документу</option>
                            <option value="1">Author #1</option>
                            <option value="2">Author #2</option>
                            <option value="3">Author #3</option>
                            <option value="4">Author #4</option>
                            <option value="5">Author #5</option>
                            <option value="6">Author #6</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filters-footer">
        <p>AL-KO Copyright © 2023 </p>
    </div>
</div>

        <div class="card-content card-table">
            <div class="table-wrapper">
                <div class="table table-actions layout-fixed add-scroll">
                    <div class="thead">
                        <div class="tr">
                            <div class="th">№ документу <a href="" class="icon-switch"></a></div>
                            <div class="th">Дата документу <a href="" class="icon-switch"></a></div>
                            <div class="th">Артикул  <a href="" class="icon-switch"></a></div>
                            <div class="th">Назва товару <a href="" class="icon-switch"></a></div>
                            <div class="th">Вартість запчастин, грн <a href="" class="icon-switch"></a></div>
                            <div class="th">Вартість робіт, грн <a href="" class="icon-switch"></a></div>
                            <div class="th">Поточний статус <a href="" class="icon-switch"></a></div>
                            <div class="th">Автор документу <a href="" class="icon-switch"></a></div>
                            <div class="th">Менеджер <a href="" class="icon-switch"></a></div>
                            <div class="th _empty"></div>
                            <div class="th">Дії</div>
                        </div>
                    </div>
                    <div class="tbody">
                        @foreach ($warrantyClaims as $claim)
                        <div class="tr">
                            <div class="td">{{$claim->number}}</div>
                            <div class="td">{{$claim->date}}</div>
                            <div class="td">{{$claim->product_article}}</div>
                            <div class="td">{{$claim->product_name}}</div>
                            <div class="td">000 000</div>
                            <div class="td">000 000</div>
                            <div class="td">
                                <button type="button" class="btn-label blue">{{$claim->type_of_claim}}</button>
                            </div>
                            <div class="td">{{$claim->user->first_name_ru}}</div>
                            <div class="td">Менеджер Імя Прізвище</div>
                            <div class="td _empty"></div>
                            <div class="td">
                                <a href="" class="btn-action icon-user"></a>
                                <a href="" class="btn-action icon-pdf"></a>
                                <a href="" class="btn-action icon-message _inactive"></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="pagination">
            <div class="pagination-total">
                Показано документів <strong>1-20</strong> з <strong>2 000</strong>
            </div>
            <div class="pagination-next">
                <a href="" class="btn-primary btn-blue">
                    Наступна сторінка
                    <span class="icon-arrow-dropdown"></span>
                </a>
            </div>
            <div class="pagination-select-wrapper">
                <p>Сторінка</p>
                <div class="form-group">
                    <select name="" id="pagin">
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </div>
                <p>з <strong>100</strong></p>
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
