<div class="filters custom-scrollbar">
    <a href="{{ route('app.home.index') }}" class="logo">
        <img src="{{ asset('img/components/logo.svg') }}" alt="">
    </a>
    <div class="filters-title">
        <p>Фільтри таблиці</p>
        <button type="button" class="icon-close-fill _js-show-filters"></button>
    </div>
    <form id="filters-form" action="{{ $filterRoute }}" method="GET">
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
                                <input type="text" name="date_start" id="date-start" placeholder="мм/дд/рррр" class="_js-datepicker" value="{{request('date_start')}}">
                                <span class="icon-calendar"></span>
                            </div>
                        </div>
                        <div class="form-group horizontal _mb0">
                            <label for="date-end">по</label>
                            <div class="input-wrapper">
                                <input type="text" name="date_end" id="date-end" placeholder="мм/дд/рррр" class="_js-datepicker" value="{{request('date_end')}}">
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
                            <input type="text" name="article" id="article" placeholder="Артикул" value="{{request('article')}}">
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
                            <input type="checkbox" id="status-0" name="status[]" value="Новий" {{ in_array('Новий', request('status', [])) ? 'checked' : '' }}>
                            <label for="status-0">Новий</label>
                        </div>
                        <div class="form-group checkbox">
                            <input type="checkbox" id="status-1" name="status[]" value="Відправлений" {{ in_array('Відправлений', request('status', [])) ? 'checked' : '' }}>
                            <label for="status-1">Відправлений</label>
                        </div>
                        <div class="form-group checkbox">
                            <input type="checkbox" id="status-2" name="status[]" value="Розглядається" {{ in_array('Розглядається', request('status', [])) ? 'checked' : '' }}>
                            <label for="status-2">Розглядається</label>
                        </div>
                        <div class="form-group checkbox _mb0">
                            <input type="checkbox" id="status-3" name="status[]" value="Затверджено" {{ in_array('Затверджено', request('status', [])) ? 'checked' : '' }}>
                            <label for="status-3">Затверджено</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filter-group js-accordion">
                <div class="filter-group__head js-accordion-btn">
                    <p>Менеджер документу</p>
                    <button type="button" class="icon-arrow-dropdown"></button>
                </div>
                <div class="filter-group__content js-accordion-content">
                    <div class="filter-group__content__wrapper">
                        <div class="form-group default-select">
                            <select name="author" id="author">
                                <option selected value="-1">Менеджер документу</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>{{ $author->first_name_ru }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="filters-footer">
            <button style="width: 200px" type="submit" class="btn-primary btn-blue">Застосувати</button>
            <button style="width: 200px" type="button" id="clear-filters" class="btn-border btn-blue">Очистити</button>
        </div>
    </form>
</div>

<script>
     document.getElementById('clear-filters').addEventListener('click', function() {
        document.getElementById('date-start').value = '';
        document.getElementById('date-end').value = '';
        document.getElementById('article').value = '';
        document.getElementById('author').value = '-1';
        document.querySelectorAll('input[type=checkbox]').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        document.getElementById('filters-form').submit();
    });

    var filterGroups = document.querySelectorAll('.filter-group');
        filterGroups.forEach(function(group) {
            var inputs = group.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                if ((input.type === 'checkbox' && input.checked) || (input.type !== 'checkbox' && input.value && input.value !== '-1')) {
                    group.classList.add('active');
                }
            });
        });
</script>
