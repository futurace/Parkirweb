@php
    $active = $active ?? '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIJA PARKING' }}</title>
    <style>
        :root {
            --bg: #f7f8fc;
            --card: #ffffff;
            --ink: #263451;
            --muted: #8490a5;
            --line: #d9dee8;
            --pink: #f1058c;
            --purple: #9419c6;
            --navy: #252d5d;
            --shadow: 0 18px 38px rgba(30, 38, 77, .08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background: var(--bg);
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            font-size: 14px;
        }

        .shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 256px;
            padding: 42px 32px;
            flex: 0 0 256px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 46px 18px;
            font-size: 13px;
            font-weight: 800;
            color: #33405d;
        }

        .brand-logo {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }

        .brand-mark {
            width: 24px;
            height: 24px;
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .menu {
            display: grid;
            gap: 10px;
        }

        .menu-label {
            margin: 18px 0 6px 16px;
            color: #9aa5b7;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .05em;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 46px;
            padding: 0 14px;
            border-radius: 8px;
            color: #7b879d;
            text-decoration: none;
            font-size: 14px;
            transition: .2s ease;
        }

        .menu-item.active {
            color: #2f3b59;
            background: #fff;
            box-shadow: var(--shadow);
            font-weight: 800;
        }

        .menu-icon {
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border-radius: 7px;
            color: #546079;
            background: #fff;
            box-shadow: 0 8px 17px rgba(33, 42, 80, .13);
            font-size: 13px;
        }

        .active .menu-icon {
            color: #fff;
            background: linear-gradient(135deg, var(--pink), var(--purple));
        }

        .main {
            width: 100%;
            padding: 24px 22px 28px 0;
        }

        .topbar {
            min-height: 66px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: var(--card);
            border-radius: 14px;
            box-shadow: var(--shadow);
        }

        .crumb {
            color: #a2abba;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .page-title {
            margin: 0;
            font-size: 16px;
            font-weight: 900;
            color: #2b3857;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .sign-out {
            color: #657089;
            text-decoration: none;
            font-weight: 700;
            white-space: nowrap;
        }

        .content {
            margin-top: 24px;
        }

        .panel {
            background: var(--card);
            border-radius: 14px;
            box-shadow: var(--shadow);
        }

        .form-panel {
            padding: 30px 36px 28px;
        }

        .section-title {
            margin: 0 0 30px;
            color: #798398;
            font-size: 16px;
            font-weight: 800;
        }

        .section-title strong {
            color: #d918ba;
            font-size: 22px;
            line-height: 1;
        }

        .field {
            display: grid;
            gap: 8px;
            margin-bottom: 14px;
        }

        .field label {
            color: #33405d;
            font-size: 12px;
            font-weight: 900;
        }

        input, select, textarea {
            width: 100%;
            min-height: 38px;
            border: 1px solid var(--line);
            border-radius: 7px;
            outline: 0;
            padding: 10px 12px;
            color: #5d687c;
            background: #fff;
            font: inherit;
            box-shadow: inset 0 1px 2px rgba(40, 47, 82, .03);
        }

        input:focus, select:focus, textarea:focus {
            border-color: #eb7ddd;
            box-shadow: 0 0 0 2px rgba(241, 5, 140, .16);
        }

        textarea {
            min-height: 86px;
            resize: none;
        }

        .button-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            margin-top: 28px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            border: 0;
            border-radius: 6px;
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 900;
            box-shadow: 0 6px 12px rgba(20, 26, 61, .22);
            cursor: pointer;
        }

        .btn-dark {
            background: linear-gradient(90deg, #303a70, #141a3c);
        }

        .btn-pink {
            background: linear-gradient(90deg, var(--pink), var(--purple));
        }

        .btn-small {
            min-height: 36px;
            padding: 0 24px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 318px;
            gap: 22px;
        }

        .summary-row {
            display: grid;
            grid-template-columns: 152px repeat(3, 148px);
            gap: 22px;
            margin-bottom: 20px;
        }

        .clock-card {
            min-height: 190px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            color: #fff;
            background:
                linear-gradient(135deg, rgba(16, 17, 37, .44), rgba(13, 17, 35, .9)),
                radial-gradient(circle at 22% 15%, #9093a4 0 8%, transparent 9%),
                linear-gradient(135deg, #442b7d, #06394a 54%, #080b18);
            border-radius: 13px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .clock-card h3 {
            margin: 0 0 10px;
            text-align: center;
            font-size: 16px;
        }

        .clock-card p {
            margin: 0 0 28px;
            text-align: center;
            font-size: 12px;
        }

        .clock-card .time {
            font-size: 21px;
            font-weight: 900;
            letter-spacing: .06em;
        }

        .location-card {
            min-height: 190px;
            display: grid;
            align-content: center;
            justify-items: center;
            padding: 16px;
            border-radius: 13px;
            background: #fff;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .location-tower {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            margin-bottom: 16px;
            border-radius: 11px;
            color: #11183b;
            background: linear-gradient(135deg, var(--pink), var(--purple));
            box-shadow: 0 9px 17px rgba(171, 24, 185, .32);
            font-size: 23px;
        }

        .location-card h3 {
            margin: 0 0 8px;
            font-size: 15px;
        }

        .capacity, .used {
            display: flex;
            justify-content: center;
            gap: 10px;
            width: 100%;
            font-size: 12px;
            font-weight: 900;
        }

        .capacity {
            color: #758197;
            margin-bottom: 30px;
        }

        .used {
            color: #0a9f1f;
            font-size: 15px;
        }

        .used .danger {
            color: #ff1212;
        }

        .transaction-panel {
            min-height: 174px;
            padding: 22px 16px 28px;
        }

        .transaction-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .transaction-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .tickets-panel {
            min-height: 386px;
            padding: 24px 14px;
        }

        .tickets-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .tickets-head h2 {
            margin: 0;
            font-size: 16px;
        }

        .outline-btn {
            min-height: 31px;
            min-width: 103px;
            border: 1px solid #d918ba;
            border-radius: 7px;
            color: #d918ba;
            background: #fff;
            font-size: 11px;
            font-weight: 900;
        }

        .placeholder-panel {
            min-height: 360px;
            padding: 32px 36px;
        }

        .footer {
            margin: 36px 0 0 22px;
            color: #7d8aa1;
            font-size: 13px;
        }

        .footer strong {
            color: #33405d;
        }

        @media (max-width: 980px) {
            .shell {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 24px;
            }

            .main {
                padding: 0 24px 24px;
            }

            .summary-row, .dashboard-grid, .transaction-fields {
                grid-template-columns: 1fr;
            }
        }

        .table-panel {
            padding: 30px 36px 28px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th {
            color: #d918ba;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            padding: 12px 8px;
            border-bottom: 2px solid #f2f4f8;
            letter-spacing: 0.05em;
            text-align: left;
        }

        .data-table td {
            padding: 16px 8px;
            border-bottom: 1px solid #f2f4f8;
            color: #546079;
            font-size: 13px;
            vertical-align: middle;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .edit-link {
            color: #0b9bed;
            text-decoration: none;
            font-weight: 700;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-left: 15px;
            transition: color 0.2s ease;
        }

        .edit-link:hover {
            color: #0275b8;
        }

        .delete-btn {
            color: #ff1212;
            background: none;
            border: none;
            font-weight: 700;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-left: 15px;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
            transition: color 0.2s ease;
        }

        .delete-btn:hover {
            color: #b30000;
        }
    </style>
</head>
<body>
    <div class="shell">
        <aside class="sidebar">
            <div class="brand">
                <img src="{{ asset('images/parkir.png') }}" alt="SIJA PARKING Logo" class="brand-logo">
                <span>SIJA PARKING</span>
            </div>

            <nav class="menu">
                <a class="menu-item {{ $active === 'locations' ? 'active' : '' }}" href="{{ route('locations.index') }}">
                    <span class="menu-icon"></span>
                    <span>Location</span>
                </a>
                <a class="menu-item {{ $active === 'transactions' ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                    <span class="menu-icon"></span>
                    <span>Transaction</span>
                </a>
                <a class="menu-item {{ $active === 'vehicle-types' ? 'active' : '' }}" href="{{ route('vehicle-types.index') }}">
                    <span class="menu-icon"></span>
                    <span>Vehicle Type</span>
                </a>

                <div class="menu-label">REPORTS</div>

                <a class="menu-item {{ $active === 'location-report' ? 'active' : '' }}" href="{{ route('locations.report') }}">
                    <span class="menu-icon">◈</span>
                    <span>Location Report</span>
                </a>
                <a class="menu-item {{ $active === 'transaction-report' ? 'active' : '' }}" href="{{ route('transactions.report') }}">
                    <span class="menu-icon">▣</span>
                    <span>Transaction Report</span>
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="topbar">
                <div>
                    <div class="crumb">Pages&nbsp;&nbsp;/&nbsp;&nbsp;{{ $title ?? 'Dashboard' }}</div>
                    <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                <div class="top-actions">
                    @yield('top-actions')
                    <a class="sign-out" href="#">♟&nbsp; Sign Out</a>
                </div>
            </header>

            <section class="content">
                @yield('content')
            </section>

            <footer class="footer">
                © 2026, made by <strong>Aditya Coding</strong> for ASAS Ganjil Web And Mobile Development - SMKN 1 Cibinong.
            </footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    title: 'Good Job',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#9419c6'
                });
            @endif
        });

        function confirmDelete(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
