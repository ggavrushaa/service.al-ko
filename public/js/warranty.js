//Дизейбл для селекта при невыбранном сервис-центре
const serviceCenterSelect = document.querySelector('#service-center');
const searchInputWorks = document.querySelector('#product-group');
const searchInputParts = document.querySelector('#search-articul');

function toggleProductGroupSelect() {
    console.log(serviceCenterSelect.value);
    
    if (serviceCenterSelect.value === '-1') {
        searchInputWorks.disabled = true;
        searchInputParts.disabled = true;
    } else {
        searchInputWorks.disabled = false;
        searchInputParts.disabled = false;
    }
}
toggleProductGroupSelect();

serviceCenterSelect.addEventListener('change', toggleProductGroupSelect);
searchInputParts.addEventListener('change', toggleProductGroupSelect);


/////// Роботи

// Прослуховувач для виробу робіт
if (searchInputWorks) {
    searchInputWorks.addEventListener('change', () => {
        const value = searchInputWorks.value.trim();

        if (value === '-1' || value === '') return false;


        loadServiceWorks(value);
    })
}


function loadServiceWorks(groupId) {
    // saveCheckboxStates(productGroupSelect.value);
    const contractPrice = +document.querySelector('input[name="contract_price"]').value;
    const serviceWorksContainer = document.querySelector('#service-works-container');

    fetch(`/service/${groupId}`)
        .then(response => response.json())
        .then(data => {

            console.log(data);
            let totalDuration = 0;
            let nonCkeckedElements = serviceWorksContainer.querySelectorAll('input[name="service_works[]"]:not(:checked)');

            nonCkeckedElements.forEach(nonCkeckedElement => {
                nonCkeckedElement.closest('.row').remove();
            })


            data.forEach(work => {
                let { id, name, duration_decimal } = work;

                duration_decimal = parseFloat(duration_decimal);
                const totalPrice = duration_decimal * contractPrice;

                if (!serviceWorksContainer.querySelector(`#service-${id}`)) {
                    let row = `
                        <div class="row">
                            <div class="cell">
                                <div class="form-group checkbox">
                                    <input type="checkbox" id="service-${id}" name="service_works[]" value="${id}" onchange="calcPrice();">
                                    <label for="service-${id}"></label>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="text" value="${name}" readonly>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="text" value="${contractPrice.toFixed(2)}" class='work-price' readonly>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="number" step="0.01" name="hours[]" value="${duration_decimal.toFixed(2)}" class="work-hours" 
                                        oninput="workCounter(event)"
                                        onkeyup="workCounterHandler(event)"
                                    >
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="text" value="${totalPrice.toFixed(2)}" class="total-price" readonly>
                                </div>
                            </div>
                        </div>
                    `;

                    serviceWorksContainer.insertAdjacentHTML('beforeend', row);
                }
            });

        })
    // .catch(error => console.error('Error fetching service works:', error));
}


// Лічильник для робіт
const workCounterHandler = debounce((event) => workCounter(event), 500);

function workCounter(event) {
    const regex = /^(?!0)\d+(\.\d+)?$/;
    const input = event.target;

    if (event.type === 'keyup' && !regex.test(input.value)) input.value = 1;

    const value = +input.value,
        row = input.closest('.row'),
        price = parseFloat(row.querySelector('.work-price').value),
        total = (price * value).toFixed(2);

    row.querySelector('.total-price').value = total;

    calcPrice();
}

/////// Запчастини

// Прослуховувач використаних запчастин
if (searchInputParts) {
    searchInputParts.addEventListener('input', debounce(() => searchPartsHandler(searchInputParts)))

}

function searchPartsHandler(input) {
    const articul = input.value.trim();

    if (articul.length < 3) return;


    fetch(`/parts/${articul}`)
        .then(response => response.json())
        .then(data => {
            const parts = data.data;
            if (parts.length === 0) return drawEmptyParts();

            drawFoundParts(parts);
        })


}


// Відобразити знайдені запчастини
function drawFoundParts(parts) {
    const partsContainer = document.querySelector('#parts-container');

    if (partsContainer.querySelector('.title-only')) {
        partsContainer.querySelector('.title-only').remove();
    }
    // Paste title
    const titleRow = '<div class="row title-only"><p>Результати пошуку</p></div>';
    partsContainer.insertAdjacentHTML('beforeend', titleRow);


    parts.forEach((part) => {
        const { id, articul, name, checked, product_prices } = part,
            discount = 10,
            recommendedPrice = parseFloat(product_prices.recommended_price),
            priceWithDiscount = (recommendedPrice * (1 - discount / 100)).toFixed(2);


        const row = `
            <div class="row" data-articul="${articul}" data-id="${id}">
                <div class="cell">
                    <div class="form-group _bg-white">
                        <input type="text" name="spare_parts_temp[${id}][spare_parts]" value="${articul}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input type="text" name="spare_parts_temp[${id}][name]" value="${name}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input type="text" name="spare_parts_temp[${id}][price]" value="${recommendedPrice}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input type="text" name="spare_parts_temp[${id}][discount]" value="${discount}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group _bg-white">
                        <input type="number" name="spare_parts_temp[${id}][qty]" value="1" class="part-quantity" min='1' oninput="partCounter(event)" onkeyup="partCounterHandler(event)">
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input  type="text" name="spare_parts_temp[${id}][sum]" value="${priceWithDiscount}" readonly class="part-total">
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group checkbox">
                        <input type="checkbox" id="parts-${id}" ${checked ? 'checked' : ''} name="spare_parts_temp[${id}][order]">
                        <label for="parts-${id}"></label>
                    </div>
                </div>
                <div class="cell">
                    <button type="button" class="btn-primary btn-blue btn-action add-part-btn" onclick="addPartHandler(this)">
                        <span class="icon-plus"></span>
                    </button>
                </div>
            </div>
        `;

        partsContainer.insertAdjacentHTML('beforeend', row);

    });

}

// Не знайдено запчастин
function drawEmptyParts() {
    const partsContainer = document.querySelector('#parts-container');

    // Paste title
    const titleRow = '<div class="row title-only _empty"><p>Нічого не знайдено</p></div>';

    partsContainer.innerHTML = titleRow;
    return false;
}


// Відобразити додану запчастину
function addPartHandler(btn) {
    const row = btn.closest('.row');
    const partsContainer = document.querySelector('#added-parts-container');


    if (row) {
        const articul = row.dataset.articul,
            id = row.dataset.id,
            name = row.querySelector('input[name*="[name]"]').value,
            price = parseFloat(row.querySelector('input[name*="[price]"]').value),
            discount = parseFloat(row.querySelector('input[name*="[discount]"]').value),
            quantity = parseInt(row.querySelector('.part-quantity').value),
            total = parseFloat(row.querySelector('.part-total').value),
            checked = row.querySelector(`input[name*="[order]"]`).checked;


        // Set disabled field
        btn.disabled = true;
        row.querySelector('.part-quantity').readOnly = true;
        row.querySelector('.part-quantity').closest('.form-group').classList.remove('_bg-white');
        row.querySelector('input[name*="[order]"').disabled = true;

        const rowAdded = `
            <div class="row" data-articul="${articul}" data-id="${id}">
                <div class="cell">
                    <div class="form-group _bg-white">
                        <input type="text" name="spare_parts[${id}][spare_parts]" value="${articul}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input type="text" name="spare_parts[${id}][name]" value="${name}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input type="text" name="spare_parts[${id}][price]" value="${price.toFixed(2)}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input type="text" name="spare_parts_temp[${id}]discount]" value="${discount}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group _bg-white">
                        <input type="text" name="spare_parts[${id}][qty]" value="${quantity}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input class="part-total"  type="text" name="spare_parts[${id}][sum]" value="${total.toFixed(2)}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group checkbox">
                        <input type="checkbox" id="parts-${id}" ${checked ? 'checked' : ''} disabled>
                        <label for="parts-${id}"></label>
                    </div>
                </div>
                <div class="cell">
                    <button type="button" class="btn-border btn-red btn-action remove-part-btn" onclick="removePartHandler(this)">
                        <span class="icon-minus"></span>
                    </button>
                </div>
            </div>
        `;


        partsContainer.insertAdjacentHTML('beforeend', rowAdded);

        calcPrice();
    }
}

// Видалити запчастину
function removePartHandler(btn) {
    const row = btn.closest('.row'),
        action = btn.dataset.action;


    if (row) {
        // Якщо є action видаляємо із сервера
        if (action !== '' && action !== undefined && action !== null) {
            fetch(action, {
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
                .then(response => response.json())
                .then(function (response) {
                    if (response.success) {
                        row.remove();
                    }
                    calcPrice();
                });
        } else {
            // Видалити атрибути disabled + readonly для запчастини
            const id = row.dataset.id;
            const rowPart = document.querySelector(`#parts-container .row[data-id="${id}"]`);


            rowPart.querySelector('.add-part-btn').disabled = false;
            rowPart.querySelector('.part-quantity').readOnly = false;
            rowPart.querySelector('.part-quantity').closest('.form-group').classList.add('_bg-white');
            rowPart.querySelector('input[name*="[order]"').disabled = false;


            row.remove();
            calcPrice();
        }
    }

}

// Лічильник для запчастин
const partCounterHandler = debounce((event) => partCounter(event), 500);

function partCounter(event) {
    const regex = /^[1-9]\d*$/;
    const input = event.target;

    if (event.type === 'keyup' && !regex.test(input.value)) input.value = 1;

    const value = +input.value,
        row = input.closest('.row'),
        discount = parseFloat(row.querySelector('input[name*="[discount]"]').value),
        recommendedPrice = parseFloat(row.querySelector('input[name*="[price]"]').value),
        priceWithDiscount = ((recommendedPrice * (1 - discount / 100)) * value).toFixed(2);


    row.querySelector('input[name*="[sum]"]').value = priceWithDiscount;
}

function calcPrice() {
    const priceParts__html = document.querySelector('#total-parts-sum span'),
        priceParts__input = document.querySelector('#total-parts-sum input'),
        priceWork__html = document.querySelector('#total-sum span'),
        priceWork__input = document.querySelector('#total-sum input');
    priceTotal__html = document.querySelector('#total-sum-final span'),
        priceTotal__input = document.querySelector('#total-sum-final input');


    const totalPriceParts = getTotalPriceParts(),
        totalPriceWorks = getTotalPriceWorks(),
        totalPrice = (+totalPriceParts + +totalPriceWorks).toFixed(2);

    // Set price parts
    priceParts__html.innerHTML = totalPriceParts;
    priceParts__input.value = totalPriceParts;

    // Set price work
    priceWork__html.innerHTML = totalPriceWorks;
    priceWork__input.value = totalPriceWorks;


    // Set price total
    priceTotal__html.innerHTML = totalPrice;
    priceTotal__input.value = totalPrice;
}
calcPrice();

// Вартість запчастин
function getTotalPriceParts() {
    const addedPartsRows = document.querySelectorAll('#added-parts-container .row');
    let totalPrice = 0;

    if (addedPartsRows.length > 0) {
        addedPartsRows.forEach(row => {
            totalPrice += +row.querySelector('.part-total').value;
        })
    }

    return totalPrice.toFixed(2);
}

// Вартість робіт
function getTotalPriceWorks() {
    const addedWorkssRows = document.querySelectorAll('#service-works-container input[name="service_works[]"]:checked');
    let totalPrice = 0;
    let totalHours = 0;

    if (addedWorkssRows.length > 0) {
        addedWorkssRows.forEach(input => {
            const row = input.closest('.row'),
                price = +row.querySelector('.total-price').value,
                hours = +row.querySelector('.work-hours').value;

            totalPrice += price;
            totalHours += hours;
        })
    }

    document.querySelector('#total-duration span').innerHTML = totalHours.toFixed(2);
    return totalPrice.toFixed(2);
}





function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}