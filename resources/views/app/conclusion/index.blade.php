<x-layouts.base>
    <div class="main" id="main">
        <div class="page-warranty">
            <div class="page-name">
                <h1>Журнал АТЕ</h1>
                <div class="btns">
                    <button type="button" class="btn-primary btn-blue icon-filters _js-show-filters">Фільтри</button>
                </div>
            </div>

            @component('components.filters', ['filterRoute' => route('technical-conclusions.filter'), 'authors' => $authors])
        @endcomponent

            <div class="card-content card-table">
                <div class="table-wrapper">
                    <div class="table table-actions layout-fixed add-scroll">
                        <div class="thead">
                            <div class="tr">
                                <div class="th">№ документу <a href="" class="icon-switch"></a></div>
                                <div class="th">Дата документу <a href="" class="icon-switch"></a></div>
                                <div class="th">Артикул  <a href="" class="icon-switch"></a></div>
                                <div class="th">Назва товару <a href="" class="icon-switch"></a></div>
                                <div class="th">Тип звернення <a href="" class="icon-switch"></a></div>
                                <div class="th">Поточний статус <a href="" class="icon-switch"></a></div>
                                <div class="th">Автор документу <a href="" class="icon-switch"></a></div>
                                <div class="th">Менеджер <a href="" class="icon-switch"></a></div>
                                <div class="th _empty"></div>
                                <div class="th">Дії</div>
                            </div>
                        </div>
                        <div class="tbody">
                            @foreach ($conclusions as $conclusion)
                            <div class="tr" data-url="{{ route('technical-conclusions.create', $conclusion->warranty_claim_id) }}" onclick="window.location.href=this.dataset.url">
                                <div class="td">
                                    <a href="{{route('technical-conclusions.create', $conclusion->warranty_claim_id)}}" class="table-link">{{$conclusion->warrantyClaim->number}}</a>
                                </div>
                                <div class="td">{{ $conclusion->date }}</div>
                                <div class="td">{{ $conclusion->warrantyClaim->product_article }}</div>
                                <div class="td">{{ $conclusion->warrantyClaim->product_name }}</div>
                                <div class="td">
                                    <button type="button" class="btn-label blue">{{ $conclusion->warrantyClaim->type_of_claim  }}</button>
                                </div>
                                <div class="td">{{ $conclusion->warrantyClaim->status ?? 'Новий' }}</div>
                                <div class="td">{{ auth()->check() ? auth()->user()->first_name_ru : 'Не вказано' }}</div>
                                <div class="td">{{ $conclusion->warrantyClaim->manager->first_name_ru ?? 'Не вказано' }}</div>
                                <div class="td _empty"></div>
                                <div class="td">
                                    @if(auth()->check() && auth()->user()->role_id === 2)
                                        {{-- <a href="" class="btn-action icon-user"></a> --}}
                                    @endif
                                    <a href="{{ route('generate.pdf', ['id' => $conclusion->id] ) }}" class="btn-action icon-pdf"></a>
                                    {{-- <a href="" class="btn-action icon-message"></a> --}}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagination">
                <div class="pagination-total">
                    Показано документів <strong>1-{{ $conclusions->count() }}</strong> з <strong>{{ $conclusions->total() }}</strong>
                </div>
                <div class="pagination-next">
                    {{ $conclusions->links() }}
                </div>
                <div class="pagination-select-wrapper">
                    <p>Сторінка</p>
                    <div class="form-group">
                        <select name="page" id="pagin">
                            @for ($i = 1; $i <= $conclusions->lastPage(); $i++)
                                <option value="{{ $i }}" @if($conclusions->currentPage() == $i) selected @endif>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <p>з <strong>{{ $conclusions->lastPage() }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <style>
    .table-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .tr {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .tr:hover {
        background-color: grey; 
    }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.tr');
    
            rows.forEach(row => {
                row.addEventListener('click', function(event) {
                    // Проверка, что клик был не по кнопке
                    if (!event.target.closest('a') && !event.target.closest('button')) {
                        window.location.href = this.dataset.url;
                    }
                });
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
  
