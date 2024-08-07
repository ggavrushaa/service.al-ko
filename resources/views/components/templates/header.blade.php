<header data-lp>
    <div class="search-block show-results">
        <div class="search-block__result">
            <span class="icon icon-search"></span>
            <div class="placeholder">
          
                    <div class="placeholder-item" id="barcode-placeholder">
                        {{ $barcode ?? '000000000' }}<span class="icon-close-fill"></span>
                    </div>
               
                    <div class="placeholder-item" id="factoryNumber-placeholder">
                        {{ $barcode ?? '000000000' }}<span class="icon-close-fill"></span>
                    </div>

            </div>
            @if($barcode || $factoryNumber)
                <div class="clear-all icon-close-fill"></div>
            @endif
            <div class="arrow"></div>
        </div>
        <form class="search-form" action="{{ route('app.search') }}" method="GET">
            <div class="form-group horizontal">
                <label for="barcode">Штрихкод гарантійного талона</label>
                <div class="input-wrapper">
                    <input type="text" name="barcode" id="barcode" placeholder="Вкажіть Штрихкод" value="{{ $barcode ?? '' }}">
                    <div class="help-block">Required field</div>
                    <button type="button" class="clear-input icon-close-fill"></button>
                </div>
            </div>
            <div class="form-group horizontal _mb0">
                <label for="number">Заводський номер гарантійного товару</label>
                <div class="input-wrapper">
                    <input type="text" name="factory_number" id="number" placeholder="Вкажіть Заводський номер" value="{{ $factoryNumber ?? '' }}">
                    <div class="help-block">Required field</div>
                    <button type="button" class="clear-input icon-close-fill"></button>
                </div>
            </div>
            <div class="btns">
                <button class="btn-border btn-blue" type="reset">Очистити</button>
                <button class="btn-primary btn-blue" type="submit">Пошук</button>
            </div>
        </form>
    </div>
    <div class="user-header">
        <div class="user-info">
            <img src="{{asset('img/components/user-undefined.svg')}}" alt="">
            @if($user)
                <div class="user-name">{{$user->first_name_ru}}</div>
                <div class="user-role">{{$user->role->name ?? 'Дилер'}}</div>
            @else
                <div class="user-name">Неавторизован</div>
                <div class="user-role">Гость</div>
            @endif
            <button type="button" class="icon-arrow-dropdown"></button>
        </div>
        <div class="user-header__dropdown">
            <div class="user-header__dropdown-content">
                <div class="dropdown-top">
                @if($user)
                    <div class="user-name">{{$user->first_name_ru}}</div>
                    <div class="user-role">{{$user->role->name ?? 'Дилер'}}</div>
                @endif
                </div>
                <div class="dropdown-footer">
                    <form action="{{ route('logout') }}" method="POST" id='logout-form'>
                        @csrf
                        <a onclick="document.getElementById('logout-form').submit()" class="btn-primary btn-blue" type="button">Вийти</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
