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
                    <button type="button" class="btn-primary btn-blue _js-btn-show-modal" data-modal="import-document">
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

<!-- модалка импорта документа -->
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

<!-- модалка редагування документа -->
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

      
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js" ></script>
<script src="/cdn/js/popper.min.js" ></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<!--<script src="/cdn/js/custom-select.js"></script>-->
<script src="/js/components.js?v=002"></script>

<script src="/js/main.js?v=002"></script>
</x-layouts.base>
