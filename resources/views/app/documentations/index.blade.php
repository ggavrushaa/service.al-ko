<x-layouts.base>
    <div class="main" id="main">
        <div class="page-doc">
            <div class="page-name sticky">
                <h1>Документація</h1>

                <div class="filters-doc">
                    <div class="form-group default-select">
                        <select name="type-doc" id="type-doc">
                            <option value="-1">Оберіть тип документу</option>
                            @foreach ($documentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group default-select">
                        <select name="cat" id="cat">
                            <option value="-1">Оберіть товарну категорію</option>
                            @foreach ($productGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" class="btn-primary btn-blue">
                        Імпортувати документ
                        <span>Формати: Word / PDF / Excel</span>
                    </button>
                </div>
            </div>

            <div class="card-content card-table">
                <div class="table-wrapper">
                    <div class="table table-actions layout-fixed">
                        <div class="thead">
                            <div class="tr">
                                <div class="th">Назва документу <a href="" class="icon-switch"></a></div>
                                <div class="th">Тип документу <a href="" class="icon-switch"></a></div>
                                <div class="th">Категорія товару  <a href="" class="icon-switch"></a></div>
                                <div class="th">Дата публікації <a href="" class="icon-switch"></a></div>
                                <div class="th _empty"></div>
                                <div class="th">Дії</div>
                            </div>
                        </div>
                        <div class="tbody">
                            @foreach ($documentations as $documentation)
                            <div class="tr">
                                <div style="text-align: center" class="td">{{ $documentation->name }}</div>
                                <div class="td">{{ $documentation->documentType->name ?? 'Не вказано' }}</div>
                                <div class="td">{{ $documentation->productGroup->name ?? 'Не вказано' }}</div>
                                <div class="td">{{ $documentation->added }}</div>
                                <div class="td _empty"></div>
                                <div class="td">
                                    <a href="" class="btn-action icon-edit"></a>
                                    <a href="" class="btn-action icon-download"></a>
                                    <a href="" class="btn-action icon-trash"></a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="pagination">
                <div class="pagination-total">
                    Показано документів <strong>{{ $documentations->count() }}</strong> з <strong>{{ $documentations->total() }}</strong>
                </div>
                <div class="pagination-next">
                    {{ $documentations->links() }}
                </div>
                <div class="pagination-select-wrapper">
                    <p>Сторінка</p>
                    <div class="form-group">
                        <select name="page" id="pagin">
                            @for ($i = 1; $i <= $documentations->lastPage(); $i++)
                                <option value="{{ $i }}" @if($documentations->currentPage() == $i) selected @endif>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <p>з <strong>{{ $documentations->lastPage() }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <style>
    .td {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    </style>

      
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js" ></script>
<script src="/cdn/js/popper.min.js" ></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<!--<script src="/cdn/js/custom-select.js"></script>-->
<script src="/js/components.js?v=002"></script>

<script src="/js/main.js?v=002"></script>
</x-layouts.base>
