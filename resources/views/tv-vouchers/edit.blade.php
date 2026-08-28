<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit TV Voucher</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        h1 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        textarea {
            min-height: 100px;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 6px;
            padding: 11px 18px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-save {
            background: #2563eb;
            color: white;
        }

        .btn-back {
            background: #6b7280;
            color: white;
            margin-left: 8px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit TV Voucher</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('tv-vouchers.update', $tvVoucher) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Pelanggan</label>

            <select name="customer_id">
                <option value="">Tanpa Pelanggan</option>

                @foreach ($customers as $customer)
                    <option
                        value="{{ $customer->id }}"
                        {{ old('customer_id', $tvVoucher->customer_id) == $customer->id ? 'selected' : '' }}
                    >
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Diisi Oleh</label>

            <input
                type="text"
                name="filled_by"
                value="{{ old('filled_by', $tvVoucher->filled_by) }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Provider</label>

            <select name="provider" required>
                @foreach (['K-Vision', 'Nex Parabola', 'Nusantara HD'] as $provider)
                    <option
                        value="{{ $provider }}"
                        {{ old('provider', $tvVoucher->provider) === $provider ? 'selected' : '' }}
                    >
                        {{ $provider }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nomor Receiver</label>

            <input
                type="text"
                name="receiver_number"
                value="{{ old('receiver_number', $tvVoucher->receiver_number) }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Nama Paket</label>

            <input
                type="text"
                name="package_name"
                value="{{ old('package_name', $tvVoucher->package_name) }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Masa Aktif</label>

            <select
                name="subscription_months"
                required
            >
                <option
                    value="1"
                    {{ old('subscription_months', $tvVoucher->subscription_months) == 1 ? 'selected' : '' }}
                >
                    1 Bulan
                </option>

                <option
                    value="3"
                    {{ old('subscription_months', $tvVoucher->subscription_months) == 3 ? 'selected' : '' }}
                >
                    3 Bulan
                </option>

                <option
                    value="6"
                    {{ old('subscription_months', $tvVoucher->subscription_months) == 6 ? 'selected' : '' }}
                >
                    6 Bulan
                </option>

                <option
                    value="12"
                    {{ old('subscription_months', $tvVoucher->subscription_months) == 12 ? 'selected' : '' }}
                >
                    1 Tahun
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Nominal Voucher</label>

            <input
                type="number"
                step="0.01"
                min="0"
                name="unit_amount"
                value="{{ old('unit_amount', $tvVoucher->unit_amount) }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Jumlah</label>

            <input
                type="number"
                min="1"
                name="quantity"
                value="{{ old('quantity', $tvVoucher->quantity) }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Biaya Tambahan</label>

            <input
                type="number"
                step="0.01"
                min="0"
                name="additional_fee"
                value="{{ old('additional_fee', $tvVoucher->additional_fee) }}"
            >
        </div>

        <div class="form-group">
            <label>Diskon</label>

            <input
                type="number"
                step="0.01"
                min="0"
                name="discount"
                value="{{ old('discount', $tvVoucher->discount) }}"
            >
        </div>

        <div class="form-group">
            <label>Status Isi Ulang</label>

            <select
                name="recharge_status"
                required
            >
                <option
                    value="pending"
                    {{ old('recharge_status', $tvVoucher->recharge_status) === 'pending' ? 'selected' : '' }}
                >
                    Pending
                </option>

                <option
                    value="success"
                    {{ old('recharge_status', $tvVoucher->recharge_status) === 'success' ? 'selected' : '' }}
                >
                    Berhasil
                </option>

                <option
                    value="failed"
                    {{ old('recharge_status', $tvVoucher->recharge_status) === 'failed' ? 'selected' : '' }}
                >
                    Gagal
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Status Setoran</label>

            <select
                name="payment_status"
                required
            >
                <option
                    value="unpaid"
                    {{ old('payment_status', $tvVoucher->payment_status) === 'unpaid' ? 'selected' : '' }}
                >
                    Belum Setor
                </option>

                <option
                    value="paid"
                    {{ old('payment_status', $tvVoucher->payment_status) === 'paid' ? 'selected' : '' }}
                >
                    Sudah Setor
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Bukti Transaksi Baru (opsional)</label>

            <input
                type="file"
                name="payment_proof"
                accept=".jpg,.jpeg,.png,.webp"
            >

            @if ($tvVoucher->payment_proof)
                <p>
                    Bukti lama sudah tersedia.
                </p>
            @endif
        </div>

        <div class="form-group">
            <label>Tanggal Transaksi</label>

            <input
                type="date"
                name="transaction_date"
                value="{{ old(
                    'transaction_date',
                    $tvVoucher->transaction_date
                        ? $tvVoucher->transaction_date->format('Y-m-d')
                        : ''
                ) }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Catatan</label>

            <textarea name="notes">{{ old('notes', $tvVoucher->notes) }}</textarea>
        </div>

        <button
            type="submit"
            class="btn btn-save"
        >
            Simpan Perubahan
        </button>

        <a
            href="{{ route('tv-vouchers.index') }}"
            class="btn btn-back"
        >
            Kembali
        </a>

    </form>

</div>

</body>
</html>