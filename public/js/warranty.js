//Дизейбл для селекта при невыбранном сервис-центре
const serviceCenterSelect = document.querySelector('#service-center'),
    contractSelect = document.querySelector('#service-contract'),
    searchInputWorks = document.querySelector('#product-group'),
    searchInputParts = document.querySelector('#search-articul');

let contractPriceInput = document.querySelector('input[name="contract_price"]'),
    contractDicountInput = document.querySelector('input[name="contract_discount"]'),
    contractPrice = +contractPriceInput.value,
    contractDicount = +contractDicountInput.value;

contractPriceInput.addEventListener('change', () => {
    contractPrice = +contractPriceInput.value;

    const rowWorks = document.querySelectorAll('#service-works-container .row');

    if (rowWorks.length > 0) {
        rowWorks.forEach(row => {
            row.querySelector('.work-price').value = contractPrice;

            row.querySelector('.work-hours').dispatchEvent(new Event('input'));
        })

        calcPrice();
    }
})

contractDicountInput.addEventListener('change', () => {
    contractDicount = +contractDicountInput.value;

    const rowParts = document.querySelectorAll('#parts-container .row[data-articul], #added-parts-container .row[data-articul]');


    if (rowParts.length > 0) {
        rowParts.forEach(row => {
            row.querySelector('input[name*="[discount]"]').value = contractDicount;
            row.querySelector('.part-quantity').dispatchEvent(new Event('input'));
        })
        calcPrice();
    }
})


serviceCenterSelect.addEventListener('change', serviceCenterHandler);

// function updatePrice(option){
//     if (serviceCenterValue === "-1" || serviceCenterValue === "") {
        
//         contractPriceInput.value = 0;
//         contractDicountInput.value = 0;

//         contractPriceInput.dispatchEvent(new Event('change'));
//         contractDicountInput.dispatchEvent(new Event('change'));

//         return false;
//     }


// }

function serviceCenterHandler() {
    const serviceCenterValue = serviceCenterSelect.value.trim();

    if (serviceCenterValue === "-1" || serviceCenterValue === "") {
        searchInputWorks.disabled = true;
        searchInputParts.disabled = true;
        contractSelect.disabled = true;
        //contractSelect.value = '-1';

        const option = document.createElement('option');
        option.value = '-1';
        option.textContent = 'Оберіть договір сервісу';

        contractSelect.innerHTML = '';
        contractSelect.appendChild(option);
        contractSelect.value = '-1';
        contractSelect.dispatchEvent(new Event('change'));

        contractPriceInput.value = 0;
        contractDicountInput.value = 0;


        contractPriceInput.dispatchEvent(new Event('change'));
        contractDicountInput.dispatchEvent(new Event('change'));


        return false;
    }

    searchInputWorks.disabled = false;
    searchInputParts.disabled = false;
    // contractSelect.disabled = false;


    fetch('/get-contract-details', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ service_center_id: serviceCenterValue })
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);

            if (data.length > 0) {
                contractSelect.disabled = false;

                contractSelect.innerHTML = '';

                data.forEach(contract => {
                    const { id, number, name, service_works_price, discount } = contract;

                    const option = document.createElement('option');
                    option.value = id;
                    option.textContent = `${name}`;
                    option.dataset.price = (service_works_price !== null) ? service_works_price : 0;
                    option.dataset.discount = (discount !== null) ? discount : 0;

                    contractSelect.insertAdjacentElement('afterend', option);
                    contractSelect.appendChild(option);
                })

                const service_works_price = data[0].service_works_price;
                const discount = data[0].discount;
                contractSelect.value = data[0].id;

                contractPriceInput.value = service_works_price;
                contractDicountInput.value = discount;

                contractPriceInput.dispatchEvent(new Event('change'));
                contractDicountInput.dispatchEvent(new Event('change'));

            } else {
                console.error('Contract not found');
            }
        })
        .catch(error => {
            console.error('Error fetching contract details:', error);
        });

    // console.log(serviceCenterValue);
}
serviceCenterHandler();

contractSelect.addEventListener('change', () => {
    const val = contractSelect.value;

    if (val === '-1') {
        contractPriceInput.value = 0;
        contractDicountInput.value = 0;

        contractPriceInput.dispatchEvent(new Event('change'));
        contractDicountInput.dispatchEvent(new Event('change'));
    }else{        
        contractPriceInput.value = contractSelect.options[contractSelect.selectedIndex].dataset.price;
        contractDicountInput.value = contractSelect.options[contractSelect.selectedIndex].dataset.discount;

        contractPriceInput.dispatchEvent(new Event('change'));
        contractDicountInput.dispatchEvent(new Event('change'));
    }
})

//// Визначити на яку кнопку натиснули
document.querySelectorAll('.page-name .btn-primary').forEach(btn => {
    btn.addEventListener('click', (e) => {
        if (btn.form) {
            e.preventDefault();

            document.querySelector('#send-to-save input[name="button"]').value = btn.value;

            if (validateForm(btn.form)) {
                btn.form.submit();
            }
        }


    })
})


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
    const serviceWorksContainer = document.querySelector('#service-works-container');

    fetch(`/service/${groupId}`)
        .then(response => response.json())
        .then(data => {

            // console.log(data);
            let totalDuration = 0;
            let nonCkeckedElements = serviceWorksContainer.querySelectorAll('input[name="service_works[]"]:not(:checked)');

            nonCkeckedElements.forEach(nonCkeckedElement => {
                nonCkeckedElement.closest('.row').remove();
            })


            // spare_parts_temp[${id}][spare_parts]
            data.forEach(work => {
                let { id, name, duration_decimal } = work;

                duration_decimal = parseFloat(duration_decimal);
                const totalPrice = duration_decimal * contractPrice;

                if (!serviceWorksContainer.querySelector(`#service-${id}`)) {
                    let row = `
                        <div class="row">
                            <div class="cell">
                                <div class="form-group checkbox">
                                    <input type="checkbox" id="service-${id}" name="service_works[${id}][checkbox]" onchange="calcPrice();">
                                    <label for="service-${id}"></label>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="text" name="service_works[${id}][name]" value="${name}" readonly>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="text" name="service_works[${id}][price]" value="${contractPrice.toFixed(2)}" class='work-price' readonly>
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="number" step="0.01" name="service_works[${id}][hours]" value="${duration_decimal.toFixed(2)}" class="work-hours" 
                                        oninput="workCounter(event)"
                                        onkeyup="workCounterHandler(event)"
                                    >
                                </div>
                            </div>
                            <div class="cell">
                                <div class="form-group">
                                    <input type="text" name="service_works[${id}][total-price]" value="${totalPrice.toFixed(2)}" class="total-price" readonly>
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


// Натиснули на чекбокс обрати всі роботи
if (document.querySelector('#works-select-all')) {
    document.querySelector('#works-select-all').addEventListener('change', () => {
        const worksCheckbox = document.querySelectorAll('#service-works-container input[name*="[checkbox]"]');

        if (worksCheckbox.length > 0) {
            worksCheckbox.forEach(work => {
                if (document.querySelector('#works-select-all').checked) {
                    work.checked = true;
                } else {
                    work.checked = false;
                }

                work.dispatchEvent(new Event('change'))
            })
        }
    })
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

    partsContainer.innerHTML = '';
    // if (partsContainer.querySelector('.title-only')) {
    // partsContainer.querySelector('.title-only').remove();
    // }
    // Paste title
    const titleRow = '<div class="row title-only"><p>Результати пошуку</p></div>';
    partsContainer.insertAdjacentHTML('beforeend', titleRow);


    // console.log(contractPrice);

    parts.forEach((part) => {
        const { id, articul, name, checked, product_prices } = part,
            recommendedPrice = parseFloat(product_prices.recommended_price),
            priceWithDiscount = (recommendedPrice * (1 - contractDicount / 100)).toFixed(2);


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
                        <input type="text" name="spare_parts_temp[${id}][discount]" value="${contractDicount}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group _bg-white">
                        <input type="number" name="spare_parts_temp[${id}][qty]" value="1" class="part-quantity" min='1' 
                            oninput="partCounter(event)" 
                            onkeyup="partCounterHandler(event)"
                        >
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
                        <input type="text" name="spare_parts_temp[${id}][discount]" value="${discount}" readonly>
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group _bg-white">
                        <input class="part-quantity" type="number" name="spare_parts[${id}][qty]" value="${quantity}" min='1' 
                            oninput="partCounter(event)" 
                            onkeyup="partCounterHandler(event)"
                        >
                    </div>
                </div>
                <div class="cell">
                    <div class="form-group">
                        <input class="part-total" type="text" name="spare_parts[${id}][sum]" value="${total.toFixed(2)}" readonly>
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


    if (row.closest('#added-parts-container')) {
        if (document.querySelector(`#parts-container .row[data-id="${row.dataset.id}"]`)) {
            document.querySelector(`#parts-container .row[data-id="${row.dataset.id}"] .part-quantity`).value = value;
            document.querySelector(`#parts-container .row[data-id="${row.dataset.id}"] .part-quantity`).dispatchEvent(new Event('input'));
        }


        // document.querySelector(`#parts-container .row[data-id="${row.dataset.id} .part-quantity"]`).value = value;
        calcPrice();
    }
}

function calcPrice() {
    const priceParts__html = document.querySelector('#total-parts-sum span'),
        priceParts__input = document.querySelector('#total-parts-sum input'),
        priceWork__html = document.querySelector('#total-works-sum span'),
        priceWork__input = document.querySelector('#total-works-sum input');
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


    // console.log('Works:', document.querySelector('#total-works-sum input').value);
    // console.log('Parts:', document.querySelector('#total-parts-sum input').value);
    // console.log('Total:', document.querySelector('#total-sum-final input').value);

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
    // const addedWorkssRows = document.querySelectorAll('#service-works-container input[name="service_works[]"]:checked');
    const addedWorkssRows = document.querySelectorAll('#service-works-container input[name*="[checkbox]"]:checked');


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
    document.querySelector('#total-duration input').value = totalHours.toFixed(2);

    return totalPrice.toFixed(2);
}





function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}








// Відображення менеджерів в модалці
window.addEventListener('DOMContentLoaded', () => {
    const showModalButtons = document.querySelectorAll('._js-btn-show-modal[data-modal="switch-manager"]');
    const modal = document.querySelector('.js-modal-switch-manager');
    const modalBody = modal ? modal.querySelector('.manager-body') : null;
    const closeModalButton = modal ? modal.querySelector('.btn-close') : null;
    const searchInput = modal ? modal.querySelector('input[name="manager-search"]') : null;
    const reassignButton = modal ? modal.querySelector('.change-manager-btn') : null;
    const modalOverlay = document.querySelector('.modal-overlay');
    let selectedManagerId = null;

    if (!showModalButtons || !modal || !modalBody || !closeModalButton || !searchInput || !reassignButton) {
        console.error('One or more elements are missing:', { showModalButtons, modal, modalBody, closeModalButton, searchInput, reassignButton });
        return;
    }

    let managersList = [];

    showModalButtons.forEach(button => {
        button.addEventListener('click', function () {
            fetch('/managers')
                .then(response => response.json())
                .then(data => {
                    managersList = data;
                    displayManagers(managersList);
                    modal.classList.add('open');
                    fadeIn(modal);
                    modalOverlay.classList.add('show');
                    modalOverlay.classList.remove('hide');
                })
                .catch(error => {
                    console.error('Error fetching managers:', error);
                });
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
        fadeOut(modal);
        modal.classList.remove('open');
        modalOverlay.classList.add('hide'); // добавлено
        modalOverlay.classList.remove('show');
    });

    window.addEventListener('click', function (event) {
        if (event.target === modalOverlay) {
            fadeOut(modal, () => {
                modal.classList.remove('open');
            });
            modalOverlay.classList.add('hide');
            modalOverlay.classList.remove('show');
        }
    });

    reassignButton.addEventListener('click', function () {
        const selectedRadio = modalBody.querySelector('input[type="radio"][name="manager"]:checked');
        if (!selectedRadio) {
            alert('Виберіть менеджера');
            return;
        }
        selectedManagerId = selectedRadio.value;

        const claimId = document.getElementById('claim-id').value;

        fetch(`/warranty-claims/${claimId}/update-manager`, {
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
                    document.getElementById('autor-id').value = selectedManagerId;
                    document.getElementById('autor-name').value = managerName;
                    modal.classList.remove('open');
                    modal.style.display = 'none';
                    modalOverlay.style.display = 'none';
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error updating manager:', error));
    });
})
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

function fadeIn(element) {
    if (!element) {
        console.error('fadeIn: element is null');
        return;
    }

    let opacity = 0;
    element.style.opacity = opacity;
    element.style.display = 'block';

    const last = +new Date();
    const tick = function () {
        opacity += (new Date() - last) / 400;
        element.style.opacity = opacity;
        if (opacity < 1) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
        }
    };

    tick();
}

function fadeOut(element, callback) {
    if (!element) {
        console.error('fadeOut: element is null');
        return;
    }

    let opacity = 1;
    const last = +new Date();
    const tick = function () {
        opacity -= (new Date() - last) / 400;
        element.style.opacity = opacity;
        if (opacity > 0) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
        } else {
            element.style.display = 'none';
            if (typeof callback === 'function') {
                callback();
            }
        }
    };

    tick();
}


function copyToClipboard() {
    // Get the input elements
    var buyerName = document.getElementById("buyer-name").value;
    var buyerPhone = document.getElementById("buyer-phone").value;

    // Get the input elements for the sender
    var senderName = document.getElementById("sender-name");
    var senderPhone = document.getElementById("sender-phone");

    // Set the values of the sender inputs to the buyer values
    senderName.value = buyerName;
    senderPhone.value = buyerPhone;
}
