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
                                    <div class="btn-action icon-edit _js-btn-show-modal" 
                                        data-modal="edit-document"
                                        data-id="{{ $documentation->id }}"
                                        data-name="{{ $documentation->name }}"
                                        data-doc-type="{{ $documentation->doc_type_id }}"
                                        data-category="{{ $documentation->category_id }}"
                                    ></div>
                                    <a href="{{ Storage::url($documentation->file_path) }}" class="btn-action icon-download"></a>
                                    <form action="{{ route('documentations.delete', ['id' => $documentation->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action icon-trash" onclick="return confirm('Ви впевнені що хочете видалити цей документ?');"></button>
                                    </form>
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

        <form action="{{ route('documentations.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group required" data-valid="empty">
                <label for="doc-name">Назва документа</label>
                <input type="text" id="doc-name" name="name" placeholder="Назва">
                <div class="help-block" data-empty="Required field"></div>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="prod-cat">Група товару</label>
                <select name="category_id" id="prod-cat">
                    <option value="-1">Оберіть групу товару</option>
                    @foreach ($productGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="doc-type">Тип документу</label>
                <select name="doc_type_id" id="doc-type">
                    <option value="-1">Оберіть тип документу</option>
                    @foreach ($documentTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group file">
                <label for="doc-file" class="btn-border btn-blue">Обрати файл ( Word / PDF / Excel) </label>
                <input type="file" name="file" id="doc-file">
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

        <form action="" method="POST" enctype="multipart/form-data" id="edit-document-form">
            @csrf
            @method('PUT')
            <div class="form-group required" data-valid="empty">
                <label for="doc-name-1">Назва документа</label>
                <input type="text" id="doc-name-1" name="doc-name" value="Назва">
                <div class="help-block" data-empty="Required field"></div>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="prod-cat">Група товару</label>
                <select name="category_id" id="prod-cat">
                    <option value="-1">Оберіть групу товару</option>
                    @foreach ($productGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group required default-select" data-valid="default-select">
                <label for="doc-type">Тип документу</label>
                <select name="doc_type_id" id="doc-type">
                    <option value="-1">Оберіть тип документу</option>
                    @foreach ($documentTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group file">
                <label for="doc-file-1" class="btn-border btn-blue">Обрати файл ( Word / PDF / Excel) </label>
                <input type="file" name="file" id="doc-file-1">
            </div>

            <div class="file-name-preview">
                Lorem ipsum dolor sit amet consectetur. pdf
            </div>

            <button type="submit" class="btn-primary btn-red">Зберегти зміни</button>

        </form>
    </div>
</div>

<div class="modal-overlay"></div>

<!-- Редагування файлу модалка -->
<script>
document.querySelectorAll('._js-btn-show-modal').forEach(function (button) {
    button.addEventListener('click', function () {
        var modalId = this.getAttribute('data-modal');
        var modal = document.querySelector('.js-modal-' + modalId);
        
        // Получаем данные из атрибутов
        var docId = this.getAttribute('data-id');
        var docName = this.getAttribute('data-name');
        var docTypeId = this.getAttribute('data-doc-type');
        var categoryId = this.getAttribute('data-category');

        // Заполняем поля модального окна
        modal.querySelector('input[name="doc-name"]').value = docName;
        
        // Устанавливаем выбранное значение для doc_type_id
        var docTypeSelect = modal.querySelector('select[id="doc-type"]');
        docTypeSelect.value = docTypeId;

        // Устанавливаем выбранное значение для category_id
        var categorySelect = modal.querySelector('select[id="prod-cat"]');
        categorySelect.value = categoryId;

        // Обновляем action формы на основе ID документа
        var form = modal.querySelector('form');
        form.action = "/documentations/update/" + docId; // Используем прямой путь к маршруту

        modal.classList.add('is-active');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js" ></script>
<script src="/cdn/js/popper.min.js" ></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<!--<script src="/cdn/js/custom-select.js"></script>-->
<script src="/js/components.js?v=002"></script>

<script src="/js/main.js?v=002"></script>
</x-layouts.base>
