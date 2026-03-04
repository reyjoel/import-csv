<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
</head>
<body>

<h2>Customer List</h2>

<input type="text" id="search" placeholder="Search...">
<button onclick="loadCustomers()">Search</button>

<ul id="customer-list"></ul>

<button onclick="prevPage()">Previous</button>
<button onclick="nextPage()">Next</button>

<script>
let currentPage = 1;

async function loadCustomers(page = 1) {
    currentPage = page;

    const search = document.getElementById('search').value;

    const response = await fetch(`/api/customers?page=${page}&search=${search}`);
    const data = await response.json();

    const list = document.getElementById('customer-list');
    list.innerHTML = '';

    data.data.forEach(customer => {
        const li = document.createElement('li');
        li.innerHTML = `
            <strong>${customer.first_name} ${customer.last_name}</strong><br>
            ${customer.email}
        `;
        list.appendChild(li);
    });
}

function nextPage() {
    loadCustomers(currentPage + 1);
}

function prevPage() {
    if (currentPage > 1) {
        loadCustomers(currentPage - 1);
    }
}

loadCustomers();
</script>

</body>
</html>