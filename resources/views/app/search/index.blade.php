<x-layouts.base>
    <div class="main" id="main">
        <div class="page-search-result">
            <div class="page-name">
                <h1>Пошук</h1>
                <p class="total-found">
                    Знайдено документів: <strong>{{ $talons->count() }}</strong>
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-content card-table">
                <div class="table-wrapper">
                    <table class="table table-actions layout-fixed">
                        <thead class="thead">
                            <tr class="tr"> 
                                <th class="th">Назва товару</th>
                                <th class="th">Покупець</th>
                                <th class="th">Штрихкод талона</th>
                                <th class="th">Заводський номер</th>
                                <th class="th">Телефон</th>
                                <th class="th">Вартість товару, грн</th>
                                <th class="th _empty"></th>
                                <th class="th">Дії</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach ($talons as $talon)
                            <tr class="tr">
                                <td class="td">{{ $talon->product->name ?? 'Не вказано' }}</td>
                                <td class="td">{{ $talon->customer ?? 'Не вказано' }}</td>
                                <td class="td">{{ $talon->barcode ?? 'Не вказано' }}</td>
                                <td class="td">{{ $talon->factory_number ?? 'Не вказано' }}</td>
                                <td class="td">{{ $talon->phone ?? 'Не вказано' }}</td>
                                <td class="td">{{ $talon->product->productPrices->recommended_price ?? 'Не вказано' }}</td>
                                <td class="td _empty"></td>
                                <td class="td">
                                    <a href="{{ route('app.warranty.create', ['barcode' => $talon->barcode ?? '', 'factory_number' => $talon->factory_number ?? '']) }}" class="btn-action icon-info"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>

