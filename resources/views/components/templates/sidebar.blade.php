  <aside class="sidebar">
            <button class="btn-size-holder"></button>
            <div class="sidebar-content custom-scrollbar">
                <a href="/home.html" class="logo">
                    <img src="./img/components/logo.svg" alt="">
                </a>
                <div class="lists">
                    <div class="list-group">
                        <ul>
                            <li class="{{Route::currentRouteName() == 'app.home.index' ? 'active' : ''}}">
                                <a href="{{route('app.home.index')}}" class="link">
                                    <span class="icon icon-search-active"></span>
                                    <span class="text">Пошук</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="list-group">
                        <p class="list-group__title">Журнали</p>
                        <ul>
                            <li class="{{Route::currentRouteName() == 'app.warranty.index' ? 'active' : ''}}">
                                <a href="{{route('app.warranty.index')}}" class="link">
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