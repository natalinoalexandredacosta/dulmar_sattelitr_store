<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Barang Dibawa Team</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .page-wrapper {
            min-height: 100vh;
            padding: 30px;
        }

        .form-card {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        }

        .page-title {
            margin: 0 0 5px;
            font-size: 28px;
            color: #111827;
        }

        .page-subtitle {
            margin: 0 0 25px;
            color: #64748b;
            font-size: 14px;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
        }

        input,
        select,
        textarea {
            width: 100%;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            outline: none;
            background: white;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #2563eb;
        }

        .preview-box {
            margin-top: 20px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px;
        }

        .preview-title {
            color: #1e40af;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .preview-value {
            font-size: 26px;
            font-weight: 800;
            color: #1d4ed8;
        }

        .helper {
            color: #64748b;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 18px;
            border: 0;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
        }

        .btn-save {
            background: #2563eb;
            color: white;
        }

        .btn-save:hover {
            background: #1d4ed8;
        }

        .btn-back {
            background: #64748b;
            color: white;
        }

        .btn-back:hover {
            background: #475569;
        }

        @media (max-width: 650px) {
            .page-wrapper {
                padding: 15px;
            }

            .form-card {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .actions {
                flex-direction: column;
            }

            .actions .btn {
                width: 100%;
            }
        }
    </style>

</head>

<body>

<div class="page-wrapper">

    <div class="form-card">

        <h1 class="page-title">
            Edit Barang Dibawa Team
        </h1>

        <p class="page-subtitle">
            Update jumlah jual, kembali, atau informasi barang yang masih berada di team.
        </p>


        @if ($errors->any())

            <div class="alert alert-error">

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('team-product-carries.update', $teamProductCarry) }}"
        >

            @csrf
            @method('PUT')


            <div class="form-grid">


                <div class="form-group">

                    <label for="team_name">
                        Nama Team *
                    </label>

                    <input
                        type="text"
                        id="team_name"
                        name="team_name"
                        value="{{ old('team_name', $teamProductCarry->team_name) }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="product_id">
                        Produk *
                    </label>

                    <select
                        id="product_id"
                        name="product_id"
                        required
                    >

                        <option value="">
                            -- Pilih Produk --
                        </option>

                        @foreach ($products as $product)

                            <option
                                value="{{ $product->id }}"
                                {{ old('product_id', $teamProductCarry->product_id) == $product->id ? 'selected' : '' }}
                            >
                                {{ $product->product_name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="form-group">

                    <label for="quantity_taken">
                        Qty Dibawa *
                    </label>

                    <input
                        type="number"
                        id="quantity_taken"
                        name="quantity_taken"
                        min="1"
                        value="{{ old('quantity_taken', $teamProductCarry->quantity_taken) }}"
                        required
                        oninput="calculateRemaining()"
                    >

                </div>


                <div class="form-group">

                    <label for="quantity_sold">
                        Qty Jual *
                    </label>

                    <input
                        type="number"
                        id="quantity_sold"
                        name="quantity_sold"
                        min="0"
                        value="{{ old('quantity_sold', $teamProductCarry->quantity_sold) }}"
                        required
                        oninput="calculateRemaining()"
                    >

                </div>


                <div class="form-group">

                    <label for="quantity_returned">
                        Qty Kembali *
                    </label>

                    <input
                        type="number"
                        id="quantity_returned"
                        name="quantity_returned"
                        min="0"
                        value="{{ old('quantity_returned', $teamProductCarry->quantity_returned) }}"
                        required
                        oninput="calculateRemaining()"
                    >

                </div>


                <div class="form-group">

                    <label for="taken_at">
                        Tanggal Dibawa *
                    </label>

                    <input
                        type="date"
                        id="taken_at"
                        name="taken_at"
                        value="{{ old(
                            'taken_at',
                            $teamProductCarry->taken_at?->format('Y-m-d')
                        ) }}"
                        required
                    >

                </div>


                <div class="form-group full">

                    <label for="notes">
                        Catatan
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                    >{{ old('notes', $teamProductCarry->notes) }}</textarea>

                </div>

            </div>


            <div class="preview-box">

                <div class="preview-title">
                    Sisa Barang di Team
                </div>

                <div
                    class="preview-value"
                    id="remainingPreview"
                >
                    {{ $teamProductCarry->remaining_quantity }}
                </div>

                <div class="helper">
                    Rumus: Qty Dibawa - Qty Jual - Qty Kembali
                </div>

            </div>


            <div class="actions">

                <button
                    type="submit"
                    class="btn btn-save"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('team-product-carries.index') }}"
                    class="btn btn-back"
                >
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>


<script>
    function calculateRemaining() {

        const taken =
            parseInt(
                document.getElementById('quantity_taken').value
            ) || 0;

        const sold =
            parseInt(
                document.getElementById('quantity_sold').value
            ) || 0;

        const returned =
            parseInt(
                document.getElementById('quantity_returned').value
            ) || 0;

        const remaining =
            Math.max(
                0,
                taken - sold - returned
            );

        document.getElementById(
            'remainingPreview'
        ).textContent = remaining;
    }

    calculateRemaining();
</script>

</body>
</html>