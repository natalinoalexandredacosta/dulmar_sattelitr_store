<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Customer - Dulmar Satellite Store</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 245px;
            padding: 35px 25px;
            background-color: #1f2b3a;
            color: white;
        }

        .sidebar h1 {
            margin: 0 0 55px;
            font-size: 28px;
        }

        .sidebar a {
            display: block;
            margin-bottom: 30px;
            color: white;
            font-size: 18px;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #60a5fa;
        }

        .main-content {
            flex: 1;
            padding: 50px;
        }

        .page-header {
            margin-bottom: 35px;
        }

        .page-header h2 {
            margin: 0 0 15px;
            font-size: 36px;
        }

        .page-header p {
            margin: 0;
            color: #4b5563;
            font-size: 18px;
        }

        .form-card {
            max-width: 750px;
            padding: 35px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .alert-error {
            margin-bottom: 25px;
            padding: 15px 20px;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 17px;
            font-weight: bold;
        }

        .required {
            color: #dc2626;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #f59e0b;
            outline: none;
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: #6b7280;
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 13px 22px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .button-update {
            background-color: #f59e0b;
            color: white;
        }

        .button-update:hover {
            background-color: #d97706;
        }

        .button-cancel {
            background-color: #6b7280;
            color: white;
        }

        .button-cancel:hover {
            background-color: #4b5563;
        }
    </style>
</head>

<body>
    <div class="container">

        <aside class="sidebar">
            <h1>Dulmar Satellite Store</h1>

            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('products.index') }}">Daftar Barang</a>
            <a href="{{ route('stock-ins.index') }}">Stock In</a>
            <a href="{{ route('stock-outs.index') }}">Stock Out</a>
            <a href="{{ route('suppliers.index') }}">Supplier Barang</a>
            <a href="{{ route('customers.index') }}">Customers</a>
            <a href="#">Reports</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Edit Customer</h2>
                <p>Update the selected customer information.</p>
            </div>

            <div class="form-card">

                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ route('customers.update', $customer) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="customer_name">
                            Customer Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            class="form-control"
                            value="{{ old('customer_name', $customer->customer_name) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone', $customer->phone) }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $customer->email) }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>

                        <textarea
                            id="address"
                            name="address"
                            class="form-control"
                        >{{ old('address', $customer->address) }}</textarea>

                        <span class="help-text">
                            Only Customer Name is required. Other fields are optional.
                        </span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="button button-update">
                            Update Customer
                        </button>

                        <a
                            href="{{ route('customers.index') }}"
                            class="button button-cancel"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>

    </div>
</body>
</html>