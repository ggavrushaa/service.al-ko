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
                                <div class="th">№ документу <a href="#" data-column="warranty_claims.number" data-order="asc" class="icon-switch"></a></div>
                                <div class="th">Дата документу <a href="" data-column="technical_conclusions.date" data-order="asc" class="icon-switch"></a></div>
                                <div class="th">Артикул  <a href="" data-column="warranty_claims.product_article" data-order="asc" class="icon-switch"></a></div>
                                <div class="th">Назва товару <a href="" data-column="warranty_claims.product_name" data-order="asc" class="icon-switch"></a></div>
                                <div class="th">Тип звернення <a href="" data-column="warranty_claims.type_of_claim" data-order="asc" class="icon-switch"></a></div>
                                <div class="th">Поточний статус <a href="" data-column="warranty_claims.status" data-order="asc" class="icon-switch"></a></div>
                                <div class="th">Автор документу <a href="" data-column="warranty_claims.autor" data-order="asc" class="icon-switch"></a></div>
                                <div class="th">Менеджер <a href="" data-column="manager.first_name_ru" data-order="asc" class="icon-switch"></a></div>
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
                <div class="pagination-select-wrapper">
                    <p>Сторінка</p>
                    <div class="form-group">
                        <select name="page" id="pagin" onchange="window.location.href=this.value">
                            @for ($i = 1; $i <= $conclusions->lastPage(); $i++)
                                <option value="{{ $conclusions->url($i) }}" {{ $conclusions->currentPage() == $i ? 'selected' : '' }}>{{ $i }}</option>
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
    
    <!-- клик по всей tr -->
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

    <!-- Сортування -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const authUserRole = {{ auth()->user()->role_id }}; 
            let isSorting = false; // Флаг для предотвращения повторных запросов
        
            function initConclusionTableEvents() {
                document.querySelectorAll('.tbody').forEach(tbody => {
                    tbody.addEventListener('click', function(event) {
                        const target = event.target.closest('.tr');
                        if (target && !event.target.closest('a') && !event.target.closest('button')) {
                            window.location.href = target.dataset.url;
                        }
                    });
                });
        
                document.querySelectorAll('.icon-switch').forEach(el => {
                    el.removeEventListener('click', handleConclusionSortClick);
                    el.addEventListener('click', handleConclusionSortClick);
                });
            }
        
            function handleConclusionSortClick(event) {
                console.log('Clicked!');
                event.preventDefault();
                if (isSorting) return;
                isSorting = true;
        
                const column = event.target.getAttribute('data-column');
                const order = event.target.getAttribute('data-order');
                const newOrder = order === 'asc' ? 'desc' : 'asc';
        
                fetch(`/technical-conclusions/sort?column=${column}&order=${newOrder}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Received data:', data);
                        if (data && data.data && data.data.length > 0) {
                            updateConclusionTableRows(data.data, newOrder, column, authUserRole);
                        } else {
                            console.error('No valid data returned:', data);
                        }
                        isSorting = false;
                    })
                    .catch(error => {
                        console.error('Error fetching sorted data:', error);
                        isSorting = false;
                    });
            }
        
            function updateConclusionTableRows(data, newOrder, column, authUserRole) {
                const tbody = document.querySelector('.tbody');
                tbody.innerHTML = '';
    
                data.forEach(conclusion => {
                    const tr = document.createElement('div');
                    tr.classList.add('tr');
                    tr.dataset.url = `/technical-conclusions/${conclusion.warranty_claim_id}/create-technical-conclusion`;
    
                    const warrantyNumber = conclusion.warranty_claim_id || 'Не указано';
                    const warrantyDate = conclusion.date || 'Не указана';
                    const productArticle = conclusion.warranty_claim?.product_article || 'Не указан';
                    const productName = conclusion.warranty_claim?.product_name || 'Не указано';
                    const typeOfClaim = conclusion.appeal_type || 'Не указано';
                    const warrantyStatus = conclusion.warranty_claim?.status ?? 'Новий';
                    const authorName = conclusion.warranty_claim?.user?.first_name_ru || 'Не вказано';
                    const managerName = conclusion.warranty_claim?.manager?.first_name_ru || 'Не вказано';
    
                    tr.innerHTML = `
                        <div class="td"><a href="/technical-conclusions/${conclusion.warranty_claim_id}/create-technical-conclusion" class="table-link">${warrantyNumber}</a></div>
                        <div class="td">${warrantyDate}</div>
                        <div class="td">${productArticle}</div>
                        <div class="td">${productName}</div>
                        <div class="td"><button type="button" class="btn-label blue">${typeOfClaim}</button></div>
                        <div class="td">${warrantyStatus}</div>
                        <div class="td">${authorName}</div>
                        <div class="td">${managerName}</div>
                        <div class="td _empty"></div>
                        <div class="td">
                            ${authUserRole === 2 ? '<a href="#" class="btn-action icon-user _js-btn-show-modal" data-claim-id="' + conclusion.id + '" data-modal="switch-manager"></a>' : ''}
                            <a href="/generate-pdf/${conclusion.id}" class="btn-action icon-pdf"></a>
                        </div>
                    `;
                    tbody.appendChild(tr);
                });
    
                document.querySelectorAll('.icon-switch').forEach(el => {
                    el.setAttribute('data-order', 'asc');
                });
                document.querySelector(`[data-column="${column}"]`).setAttribute('data-order', newOrder);
                initConclusionTableEvents(); 
            }
    
            initConclusionTableEvents();
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
  
