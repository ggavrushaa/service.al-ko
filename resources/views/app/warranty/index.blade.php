<x-layouts.base>
<div class="main" id="main">
    <div class="page-warranty">
        <div class="page-name">
            <h1>Гарантійні заяви</h1>
            <div class="btns">
                <button type="button" class="btn-primary btn-blue icon-filters _js-show-filters">Фільтри</button>
            </div>
        </div>

        @component('components.filters', ['filterRoute' => route('warranty-claims.filter'), 'authors' => $authors])
        @endcomponent

        <div class="card-content card-table">
            <div class="table-wrapper">
                <div class="table table-actions layout-fixed add-scroll">
                    <div class="thead">
                        <div class="tr">
                            <div class="th">№ документу <span class="icon-switch" data-column="number" data-order="asc"></span></div>
                            <div class="th">Дата документу <span class="icon-switch" data-column="date" data-order="asc"></span></div>
                            <div class="th">Артикул <span class="icon-switch" data-column="product_article" data-order="asc"></span></div>
                            <div class="th">Назва товару <span class="icon-switch" data-column="product_name" data-order="asc"></span></div>
                            <div class="th">Вартість запчастин, грн <span class="icon-switch" data-column="amount_vat" data-order="desc"></span></div>
                            <div class="th">Вартість робіт, грн <span class="icon-switch" data-column="works_cost" data-order="asc"></span></div>
                            <div class="th">Поточний статус <span class="icon-switch" data-column="status" data-order="asc"></span></div>
                            <div class="th">Автор документу <span class="icon-switch" data-column="autor" data-order="asc"></span></div>
                            <div class="th">Менеджер <a href="" data-column="manager_id" class="icon-switch"></a></div>
                            <div class="th _empty"></div>
                            <div class="th">Дії</div>
                        </div>
                    </div>
                    <div class="tbody">
                        {{-- {{dd($warrantyClaims)}} --}}
                        @foreach ($warrantyClaims as $claim)
                             <div class="tr" data-url="{{ route('app.warranty.edit', $claim->id) }}" onclick="window.location.href=this.dataset.url">
                                <div class="td">
                                    <a href="{{route('app.warranty.edit', $claim->id)}}" class="table-link">{{$claim->number}}</a>
                                </div>
                                <div class="td">{{$claim->date}}</div>
                                <div class="td">{{$claim->product_article}}</div>
                                <div class="td">{{$claim->product_name}}</div>
                                <div class="td">{{ $claim->spare_parts_sum ?? 'Не вказано' }}</div>
                                <div class="td">{{ $claim->service_works_sum ?? 'Не вказано' }}</div>
                                <div class="td">
                                    <button type="button" class="btn-label {{ $claim->status->labelClass() }}">
                                        {{ $claim->status }}
                                    </button>
                                </div>
                                <div class="td">{{$claim->user->first_name_ru ?? 'Не вказано'}}</div>
                                <div class="td">{{$claim->manager->first_name_ru ?? 'Не вказано'}}</div>
                                <div class="td _empty"></div>
                                <div class="td">
                                    @if(auth()->check() && auth()->user()->role_id === 2 OR auth()->user()->role_id === 3)
                                        <a href="#" class="btn-action icon-user _js-btn-show-modal" data-claim-id="{{ $claim->id }}" data-modal="switch-manager"></a>
                                    @endif
                                    {{-- <a href="" class="btn-action icon-pdf"></a> --}}
                                    <a href="#" class="btn-action icon-message js-btn-open-chat" data-modal="chat" data-claim-id="{{ $claim->id }}"></a>
                                </div>
                        </div>
                        @endforeach
                    </ш>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="pagination">
    <div class="pagination-total">
        Показано документів <strong>{{ $warrantyClaims->firstItem() }}-{{ $warrantyClaims->lastItem() }}</strong> з <strong>{{ $warrantyClaims->total() }}</strong>
    </div>
    {{-- <div class="pagination-next">
      {{ $warrantyClaims->links() }}
    </div> --}}
    <div class="pagination-select-wrapper">
        <p>Сторінка</p>
        <div class="form-group">
            <select name="page" id="pagin" onchange="window.location.href=this.value">
                @for ($i = 1; $i <= $warrantyClaims->lastPage(); $i++)
                    <option value="{{ $warrantyClaims->url($i) }}" {{ $warrantyClaims->currentPage() == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <p>з <strong>{{ $warrantyClaims->lastPage() }}</strong></p>
    </div>
</div>
<div class="modal-overlay"></div>

<!--         modal switch manager -->

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
        <div class="manager-body custom-scrollbar"></div>
        <div class="manager-footer">
            <button type="button" class="btn-primary btn-blue change-manager-btn">Переназначити менеджера</button>
        </div>
    </div>
</div>

<!--         modal chat -->

<div class="modal modal-chat js-modal js-modal-chat">
    <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
    <div class="modal-content">
        <div class="chat-header">
            <p class="modal-title">
                Коментарі до документу
            </p>
            <div class="chat-desc">
                <p><strong>Документ:</strong> №</p>
                <p><strong>Ваш менеджер/дилер:</strong></p>
            </div>
        </div>

        <div class="chat-main custom-scrollbar">
            <div class="chat-main__wrapper _empty">
            </div>
        </div>
        <form id="chat-form" class="chat-footer">
            <div class="form-group">
                <input type="text" name="chat-text" placeholder="Ваш текст">
                <div class="help-block">Введіть повідомлення</div>
            </div>
            <button type="submit" class="btn-primary btn-blue" id="send-comment" data-text="Редагувати">Надіслати</button>
        </form>
    </div>
</div>


<input type="hidden" name="auth-user-id" value="{{ Auth::id() }}">
<div id="datepicker-container"></div>


<!-- <style>

.modal {
    display: none;
    transition: opacity 0.4s;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    /* z-index: 10; */
    transition: opacity 0.4s;
}

.modal.open {
    display: block;
    opacity: 1;
}

.modal.fade-in {
    display: block;
    opacity: 0;
    transition: opacity 0.4s;
}

.modal.fade-out {
    opacity: 0;
    transition: opacity 0.4s;
    display: none;
}

.modal-overlay.show {
    display: block;
    opacity: 1;
}

.th {
    pointer-events: none; 
    position: relative;
}

.icon-switch {
    pointer-events: auto;
}

</style> -->

<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script src="/cdn/js/swiper-bundle.min.js" ></script>
<script src="/cdn/js/popper.min.js" ></script>
<script src="/cdn/js/tippy-bundle.umd.min.js"></script>
<script src="/cdn/js/maskinput.js" id="maskinput-script" defer></script>
<!--<script src="/cdn/js/custom-select.js"></script>-->
<script src="/js/components.js?v=002"></script>

<script src="/js/main.js?v=002"></script>

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


<!-- Сортування -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const authUserRole = {{ auth()->user()->role_id }}; // Определение переменной authUserRole
    let isSorting = false; // Флаг для предотвращения повторных запросов

    function initTableEvents() {
        document.querySelectorAll('.tbody').forEach(tbody => {
            tbody.addEventListener('click', function(event) {
                const target = event.target.closest('.tr');
                if (target && !event.target.closest('a') && !event.target.closest('button')) {
                    window.location.href = target.dataset.url;
                }
            });
        });

        document.querySelectorAll('.icon-switch').forEach(el => {
            // Удаляем существующие обработчики событий, если они есть
            el.removeEventListener('click', handleSortClick);
            el.addEventListener('click', handleSortClick);
        });
    }

    function handleSortClick(event) {
        event.preventDefault();
        if (isSorting) return; // Предотвращаем множественные запросы
        isSorting = true; // Устанавливаем флаг, чтобы предотвратить повторный вызов до завершения текущего

        const column = event.target.getAttribute('data-column');
        const order = event.target.getAttribute('data-order');
        const newOrder = order === 'asc' ? 'desc' : 'asc';

        fetch(`/warranty-claims/sort?column=${column}&order=${newOrder}`)
            .then(response => response.json())
            .then(data => {
                console.log('Sorted data:', data); // Отладка данных
                if (data && data.data && data.data.length > 0) {
                    updateTableRows(data.data, newOrder, column, authUserRole); // Передаем authUserRole
                } else {
                    console.error('No valid data returned:', data);
                }
                isSorting = false; // Сбрасываем флаг после завершения
            })
            .catch(error => {
                console.error('Error fetching sorted data:', error);
                isSorting = false; // Сбрасываем флаг при ошибке
            });
    }

    function updateTableRows(data, newOrder, column, authUserRole) {
        const tbody = document.querySelector('.tbody');
        tbody.innerHTML = '';

        data.forEach(claim => {
            const totalVat = claim.spare_parts.reduce((sum, part) => sum + parseFloat(part.amount_vat), 0);
            
            const tr = document.createElement('div');
            tr.classList.add('tr');
            tr.dataset.url = `/warranty/edit/${claim.id}`;
            tr.innerHTML = `
                <div class="td"><a href="/warranty/edit/${claim.id}" class="table-link">${claim.number}</a></div>
                <div class="td">${claim.date}</div>
                <div class="td">${claim.product_article}</div>
                <div class="td">${claim.product_name}</div>
                <div class="td">${totalVat.toFixed(2)}</div>
                <div class="td">${claim.works_cost || 'Не вказано'}</div>
                <div class="td"><button type="button" class="btn-label blue">${claim.status}</button></div>
                <div class="td">${claim.user ? claim.user.first_name_ru : 'Не вказано'}</div>
                <div class="td">${claim.manager ? claim.manager.first_name_ru : 'Не вказано'}</div>
                <div class="td _empty"></div>
                <div class="td">
                    ${authUserRole === 2 ? '<a href="#" class="btn-action icon-user _js-btn-show-modal" data-claim-id="' + claim.id + '" data-modal="switch-manager"></a>' : ''}
                    <a href="#" class="btn-action icon-message _js-btn-show-modal" data-claim-id="${claim.id}" data-modal="chat"></a>
                </div>
            `;
            tbody.appendChild(tr);
        });

        document.querySelectorAll('.icon-switch').forEach(el => {
            el.setAttribute('data-order', 'asc');
        });
        document.querySelector(`[data-column="${column}"]`).setAttribute('data-order', newOrder);
        initTableEvents(); 
    }

    initTableEvents(); 
});
</script>


    
<!-- Switch manager -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showModalButtons = document.querySelectorAll('.btn-action.icon-user._js-btn-show-modal');
        const modal = document.querySelector('.js-modal-switch-manager');
        const modalBody = modal ? modal.querySelector('.manager-body') : null;
        const closeModalButton = modal ? modal.querySelector('.btn-close') : null;
        const searchInput = modal ? modal.querySelector('input[name="manager-search"]') : null;
        const reassignButton = modal ? modal.querySelector('.change-manager-btn') : null;
        const modalOverlay = document.querySelector('.modal-overlay');
        let currentClaimId = null;
        let managersList = [];

        if (!showModalButtons.length || !modal || !modalBody || !closeModalButton || !searchInput || !reassignButton) {
            // console.error('One or more elements are missing:', { showModalButtons, modal, modalBody, closeModalButton, searchInput, reassignButton });
            return;
        }

        function fadeIn(element) {
            element.style.display = 'block';
            element.style.opacity = 0;
            let last = +new Date();
            const tick = function () {
                element.style.opacity = +element.style.opacity + (new Date() - last) / 400;
                last = +new Date();
                if (+element.style.opacity < 1) {
                    (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
                }
            };
            tick();
        }

        function fadeOut(element, callback) {
            element.style.opacity = 1;
            let last = +new Date();
            const tick = function () {
                element.style.opacity = +element.style.opacity - (new Date() - last) / 400;
                last = +new Date();
                if (+element.style.opacity > 0) {
                    (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
                } else {
                    element.style.display = 'none';
                    if (callback) callback();
                }
            };
            tick();
        }

        showModalButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                currentClaimId = this.getAttribute('data-claim-id');
                console.log('Current Claim ID:', currentClaimId);

                fetch('/managers')
                    .then(response => response.json())
                    .then(data => {
                        managersList = data;
                        displayManagers(managersList);
                        fadeIn(modal);
                        fadeIn(modalOverlay);
                    })
                    .catch(error => console.error('Error fetching managers:', error));
            });
        });

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const filteredManagers = managersList.filter(manager => 
                manager.first_name_ru.toLowerCase().includes(query)
            );
            displayManagers(filteredManagers);
        });

        closeModalButton.addEventListener('click', function () {
            fadeOut(modal, function () {
                modal.classList.remove('open');
            });
            fadeOut(modalOverlay);
        });

        modalOverlay.addEventListener('click', function () {
            fadeOut(modal, function () {
                modal.classList.remove('open');
            });
            fadeOut(modalOverlay);
        });

        reassignButton.addEventListener('click', function () {
            const selectedRadio = modalBody.querySelector('input[type="radio"][name="manager"]:checked');
            if (!selectedRadio) {
                alert('Виберіть менеджера');
                return;
            }
            const selectedManagerId = selectedRadio.value;
            console.log('Selected Manager ID:', selectedManagerId);

            fetch(`/warranty-claims/${currentClaimId}/update-manager`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ manager_id: selectedManagerId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const managerName = selectedRadio.nextElementSibling.textContent;
                    const tr = document.querySelector(`.tr[data-claim-id="${currentClaimId}"]`);
                    if (tr) {
                        const managerCell = tr.querySelector('.td:nth-child(9)');
                        if (managerCell) {
                            managerCell.textContent = managerName;
                        }
                    }
                    fadeOut(modal, function () {
                        modal.classList.remove('open');
                    });
                    fadeOut(modalOverlay);
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error updating manager:', error));
        });

        function displayManagers(managers) {
            modalBody.innerHTML = '';
            managers.forEach(manager => {
                const managerRow = `
                    <div class="form-group radio">
                        <input type="radio" id="manager-${manager.id}" name="manager" value="${manager.id}">
                        <label for="manager-${manager.id}">${manager.first_name_ru}</label>
                    </div>
                `;
                modalBody.insertAdjacentHTML('beforeend', managerRow);
            });
        }
    });
</script>


</x-layouts.base>