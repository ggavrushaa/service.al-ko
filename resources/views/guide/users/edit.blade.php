<x-layouts.base>
    <div class="main" id="main">
        <div class="page-warranty-create">
            <div class="page-name sticky">
                <ul class="fake-breadcrumb">
                    <li>
                        <h1><a href="{{ route('users.index') }}">Користувачі </a></h1>
                    </li>
                    <li>
                        Редагування користувача
                    </li>
                </ul>
                <div class="btns">
                    <button type="button" class="btn-primary btn-blue" onclick="document.getElementById('form-create').submit()">Оновити і вийти</button>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-red">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST" id="form-create">
                @csrf
                @method('PUT')
                <div class="card-lists">
                    <div class="card-content card-form">
                        <p class="card-title">Загальні дані</p>
                        <div class="inputs-group grid-layout">
                            <div class="form-group">
                                <label for="article">ФІО/Назва</label>
                                <input type="text" placeholder="Введіть ФІО" name="first_name_ru" value="{{ $user->first_name_ru }}">
                            </div>
                            <div class="form-group">
                                <label for="document_date">Пароль</label>
                                <input type="text" placeholder="Введіть пароль" name="password" value="{{ $user->password }}">
                            </div>
                            <div class="form-group">
                                <label for="document_date">Пошта</label>
                                <input type="text" placeholder="Введіть пошту" name="email" value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Активність</label>
                                <select name="status" id="status">
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Так</option>
                                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Ні</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="role_id">Роль</label>
                                <select name="role_id" id="role_id">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="service-centres-header">
                                <label>Сервісні центри</label>
                                <button type="button" class="btn-primary btn-blue _js-btn-show-modal" data-modal="select-service-centres">+ Додати сервісний центр</button>
                            </div>
                            <table class="table" id="selected-service-centres">
                                <thead>
                                    <tr>
                                        <th>Назва сервісного центру</th>
                                        <th class="text-center">Основний</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceCentres as $centre)
                                        <tr>
                                            <td>{{ $centre->full_name_ru }}</td>
                                            <td class="radio-cell">
                                                <input type="radio" id="default-centre-{{ $centre->user_partner_id }}" name="default_service_centre" value="{{ $centre->user_partner_id }}" {{ $defaultServiceCentre && $defaultServiceCentre->user_partner_id == $centre->user_partner_id ? 'checked' : '' }}>
                                                <label for="default-centre-{{ $centre->user_partner_id }}"></label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input type="hidden" name="service_centres[]" id="service-centres-input" value="{{ json_encode($serviceCentres->pluck('user_partner_id')->toArray()) }}">
                            <input type="hidden" name="default_service_centre" id="default-service-centre-input" value="{{ $defaultServiceCentre ? $defaultServiceCentre->user_partner_id : '' }}">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay"></div>

    <!-- Modal for selecting service centres -->
    <div class="modal modal-manager js-modal js-modal-select-service-centres">
        <button type="button" class="icon-close-fill btn-close _js-btn-close-modal"></button>
        <div class="modal-content">
            <div class="manager-header">
                <p class="modal-title">Оберіть сервісні центри</p>
                <div class="form-group">
                    <span class="icon-search"></span>
                    <input type="text" placeholder="пошук" name="service-centre-search">
                </div>
            </div>
            <div class="manager-body custom-scrollbar">
                @foreach($userPartners as $partner)
                    <div class="form-group checkbox">
                        <input type="checkbox" id="partner-{{ $partner->id }}" name="modal_service_centres[]" value="{{ $partner->id }}" {{ in_array($partner->id, $serviceCentres->pluck('user_partner_id')->toArray()) ? 'checked' : '' }}>
                        <label for="partner-{{ $partner->id }}">{{ $partner->full_name_ru }}</label>
                    </div>
                @endforeach
            </div>
            <div class="manager-footer">
                <button type="button" class="btn-primary btn-blue" id="save-service-centres">Зберегти</button>
            </div>
        </div>
    </div>

    <style>
        .grid-layout {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .service-centres-header {
            display: flex;
            align-items: center;
        }

        .service-centres-header label {
            margin-right: 20px;
        }

        .table td.radio-cell {
            text-align: center;
            vertical-align: middle;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table td.radio-cell input[type="radio"] {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .table th.text-center {
            width: 100px;
        }

        .table td.radio-cell label {
            display: block;
            text-align: center;
            padding-top: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

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

        .table td input[type="radio"] + label,
        .form-group.radio input[type="radio"] + label {
            cursor: pointer;
        }

        .table tr {
            cursor: pointer;
        }

        .table td.radio-cell {
            text-align: center;
            vertical-align: middle;
            position: relative;
        }

        .table td.radio-cell input[type="radio"] {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .table td.radio-cell label {
            display: block;
            text-align: center;
            padding-top: 20px;
        }
    </style>

<!-- Вибір сервісних центрів -->
<script>
 document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('save-service-centres');
    const modal = document.querySelector('.js-modal-select-service-centres');
    const modalOverlay = document.querySelector('.modal-overlay');
    const modalBody = modal.querySelector('.manager-body');
    const selectedServiceCentresTable = document.getElementById('selected-service-centres').querySelector('tbody');
    const searchInput = document.querySelector('input[name="service-centre-search"]');

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

    document.querySelectorAll('._js-btn-show-modal').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            fadeIn(modal);
            fadeIn(modalOverlay);
        });
    });

    document.querySelectorAll('.btn-close, .modal-overlay').forEach(element => {
        element.addEventListener('click', function () {
            fadeOut(modal);
            fadeOut(modalOverlay);
        });
    });

    saveButton.addEventListener('click', function () {
        const selectedCheckboxes = modalBody.querySelectorAll('input[type="checkbox"][name="modal_service_centres[]"]:checked');
        const selectedServiceCentres = Array.from(selectedCheckboxes).map(checkbox => {
            return {
                id: checkbox.value,
                name: checkbox.nextElementSibling.textContent
            };
        });

        selectedServiceCentresTable.innerHTML = ''; // Очистить таблицу перед добавлением новых строк
        document.querySelectorAll('input[name="service_centres[]"]').forEach(input => input.remove()); // Очистить скрытые поля

        selectedServiceCentres.forEach(centre => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${centre.name}</td>
                <td class="radio-cell">
                    <input type="radio" id="default-centre-${centre.id}" name="default_service_centre" value="${centre.id}" ${centre.id == document.getElementById('default-service-centre-input').value ? 'checked' : ''}>
                    <label for="default-centre-${centre.id}"></label>
                </td>
            `;
            selectedServiceCentresTable.appendChild(row);

            // Добавляем обработчик событий для активации радио-кнопки при клике на строку
            row.addEventListener('click', function () {
                row.querySelector('input[type="radio"]').checked = true;
                document.getElementById('default-service-centre-input').value = centre.id;
            });

            // Добавляем скрытые поля для отправки данных
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'service_centres[]';
            hiddenInput.value = centre.id;
            document.getElementById('form-create').appendChild(hiddenInput);
        });

        fadeOut(modal, function () {
            modal.classList.remove('open');
        });
        fadeOut(modalOverlay);
    });

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        const items = modalBody.querySelectorAll('.form-group.checkbox');
        items.forEach(item => {
            const label = item.querySelector('label').textContent.toLowerCase();
            if (label.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

</script>
</x-layouts.base>
