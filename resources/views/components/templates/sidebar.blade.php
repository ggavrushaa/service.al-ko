  <aside class="sidebar">
            <button class="btn-size-holder"></button>
            <div class="sidebar-content custom-scrollbar">
                <a href="{{route('app.home.index')}}" class="logo">
                    <img src="{{asset('img/components/logo.svg')}}" alt="">
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
                            <li class="{{Route::currentRouteName() == 'app.warranty.index' || Route::currentRouteName() == 'app.warranty.edit' ? 'active' : ''}}">
                                <a href="{{route('app.warranty.index')}}" class="link">
                                    <span class="icon icon-docs-in-folders"></span>
                                    <span class="text">Гарантійні заяви</span>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'app.conclusion.index' || Route::currentRouteName() == 'app.conclusion.edit' ? 'active' : ''}}">
                                <a href="{{route('app.conclusion.index')}}" class="link">
                                    <span class="icon icon-docs"></span>
                                    <span class="text">Акти технічної експертизи</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="list-group">
                        <p class="list-group__title">Інше</p>
                        <ul>
                            @if(auth()->check() && auth()->user()->role_id == 3)
                            <li class="{{Route::currentRouteName() == 'documentations.fees' || Route::currentRouteName() == 'documentations.fees' ? 'active' : ''}}">
                                <a href="{{route('documentations.fees')}}" class="link">
                                    <span class="icon icon-docs"></span>
                                    <span class="text">Звірка компенсацій</span>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'documentations.index' || Route::currentRouteName() == 'documentations.index' ? 'active' : ''}}">
                                <a href="{{route('documentations.index')}}" class="link">
                                    <span class="icon icon-docs"></span>
                                    <span class="text">Документація</span>
                                </a>
                            </li>
                            @endif
                            <li class="have-sublist js-accordion ">
                                <button type="button" class="link js-accordion-btn">
                                    <span class="icon icon-book"></span>
                                    <span class="text">Довідники</span>
                                    <span class="btn-open"></span>
                                </button>
                                <ul class="sublist js-accordion-content">
                                    <li class="{{Route::currentRouteName() == 'app.defect.index' ? 'active' : ''}}">
                                        <a href="{{route('app.defect.index')}}" class="link">Коди дефектів</a>
                                    </li>
                                    <li class="{{Route::currentRouteName() == 'app.symptom.index' ? 'active' : ''}}">
                                        <a href="{{route('app.symptom.index')}}" class="link">Коди симптомів</a>
                                    </li>
                                    <li class="{{Route::currentRouteName() == 'app.service.index' ? 'active' : ''}}">
                                        <a href="{{route('app.service.index')}}" class="link">Сервісні роботи</a>
                                    </li>
                                    @if(auth()->check() && auth()->user()->role_id == 3)
                                        <li class="{{Route::currentRouteName() == 'users.index' ? 'active' : ''}}">
                                            <a href="{{route('users.index')}}" class="link">Користувачі</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="sidebar-footer">
                    <p>AL-KO Copyright © 2024 </p>
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