<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders</title>
    <style>
        /* Page container */
        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 16px;
        }

        /* Title */
        .page-title {
            text-align: center;
            margin: 12px 0 16px;
            font-weight: 700;
        }

        /* Base (mobile-first): карточки вместо таблицы */
        .table {
            width: 100%;
        }

        .table thead {
            display: none;
        }

        .table tr {
            display: block;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 12px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .table td {
            display: grid;
            grid-template-columns: 40% 60%;
            gap: 8px;
            padding: 6px 0;
            border: none;
        }

        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #374151;
        }

        /* Controls */
        .controls {
            display: flex;
            gap: 10px;
            align-items: center;
            margin: 12px 0 16px;
        }
        .controls input {
            flex: 1;
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }
        .controls .sort {
            display: flex;
            gap: 8px;
        }
        .controls button {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #f9fafb;
            cursor: pointer;
        }

        /* Desktop/tablet: классическая таблица и выравнивание */
        @media (min-width: 640px) {
            .controls .sort { display: none; }

            .table { border-collapse: collapse; }
            .table thead { display: table-header-group; }
            .table tr {
                display: table-row;
                border: none;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
            .table th, .table td {
                display: table-cell;
                padding: 12px 14px;
                border-bottom: 1px solid #e5e7eb;
                vertical-align: middle;
                text-align: left; /* align headers and values to the left */
            }
            .table td::before { content: none; }

            .table th {
                background: #f3f4f6;
                font-weight: 600;
                color: #111827;
                cursor: pointer;
                white-space: nowrap;
                position: relative;
                user-select: none;
            }

            /* Optional: right-align cost column on desktop for readability */
            .table th:nth-child(3),
            .table td:nth-child(3) {
                text-align: right;
            }

            /* Sort indicators */
            .table th.sorted-asc::after,
            .table th.sorted-desc::after {
                content: '';
                border: 5px solid transparent;
                position: absolute;
                right: 8px;
                top: 50%;
                transform: translateY(-50%);
            }
            .table th.sorted-asc::after { border-bottom-color: #6b7280; }
            .table th.sorted-desc::after { border-top-color: #6b7280; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="page-title">Orders</h1>

    <div class="controls">
        <input type="text" id="filter" placeholder="Filter by user name">
        <div class="sort">
            <button type="button" onclick="sortTable(0)">Sort by ID</button>
            <button type="button" onclick="sortTable(1)">Sort by Title</button>
            <button type="button" onclick="sortTable(2)">Sort by Cost</button>
            <button type="button" onclick="sortTable(3)">Sort by User</button>
        </div>
    </div>

    <table id="ordersTable" class="table">
        <thead>
        <tr>
            <th onclick="sortTable(0)">ID</th>
            <th onclick="sortTable(1)">Title</th>
            <th onclick="sortTable(2)">Cost</th>
            <th onclick="sortTable(3)">User</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td data-label="ID">{{ $order->id }}</td>
                <td data-label="Title">{{ $order->title }}</td>
                <td data-label="Cost">{{ number_format($order->cost, 2) }}</td>
                <td data-label="User">{{ $order->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    function sortTable(colIndex) {
        const table = document.getElementById('ordersTable');
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const currentCol = Number(table.getAttribute('data-sort-col'));
        const currentDir = table.getAttribute('data-sort-dir') || 'asc';
        const newDir = (currentCol === colIndex && currentDir === 'asc') ? 'desc' : 'asc';

        const getVal = (row) => row.children[colIndex].innerText.trim();
        const isNumeric = rows.every(r => !isNaN(parseFloat(getVal(r))));

        rows.sort((a, b) => {
            const av = getVal(a);
            const bv = getVal(b);
            if (isNumeric) {
                const an = parseFloat(av), bn = parseFloat(bv);
                return newDir === 'asc' ? an - bn : bn - an;
            } else {
                return newDir === 'asc' ? av.localeCompare(bv) : bv.localeCompare(av);
            }
        });

        rows.forEach(r => tbody.appendChild(r));
        table.setAttribute('data-sort-col', colIndex);
        table.setAttribute('data-sort-dir', newDir);

        const ths = table.tHead ? table.tHead.rows[0].cells : [];
        for (let i = 0; i < ths.length; i++) {
            ths[i].classList.remove('sorted-asc', 'sorted-desc');
        }
        if (ths[colIndex]) {
            ths[colIndex].classList.add(newDir === 'asc' ? 'sorted-asc' : 'sorted-desc');
        }
    }

    document.getElementById('filter').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#ordersTable tbody tr').forEach(row => {
            const user = row.children[3].innerText.toLowerCase();
            row.style.display = user.includes(q) ? '' : 'none';
        });
    });
</script>
</body>
</html>
