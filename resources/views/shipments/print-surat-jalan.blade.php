<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Surat Jalan</title>
    <style>
        /* General Styles for standard print layout resembling dot-matrix invoices */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .no-print-bar {
            padding: 12px 24px;
            background-color: #0f172a;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: ui-sans-serif, system-ui, sans-serif;
            border-bottom: 2px solid #1e293b;
        }

        .no-print-bar .title {
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .no-print-bar .actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back {
            background-color: transparent;
            color: #94a3b8;
            border: 1px solid #334155;
        }

        .btn-back:hover {
            color: #fff;
            background-color: #1e293b;
            border-color: #475569;
        }

        .btn-print {
            background-color: #10b981;
            color: #fff;
            border: 1px solid #10b981;
        }

        .btn-print:hover {
            background-color: #059669;
            border-color: #059669;
        }

        /* Printable Invoice Container */
        .invoice-wrapper {
            max-width: 21cm;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px dashed #cbd5e1;
            box-sizing: border-box;
        }

        .page-break {
            page-break-after: always;
            break-after: page;
        }

        /* Replicating the SF Yellow Badge */
        .logo-sf {
            width: 70px;
            height: 42px;
            border: 2px solid #000;
            border-radius: 50% / 50%;
            background-color: #facc15; /* Yellow-400 */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: "Times New Roman", Times, serif;
            font-weight: bold;
            font-style: italic;
            font-size: 26px;
            color: #000;
            text-align: center;
            box-shadow: 1px 1px 0px #000;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .company-header {
            display: flex;
            align-items: flex-start;
        }

        .company-info {
            line-height: 1.25;
        }

        .company-name {
            font-family: "Times New Roman", Times, serif;
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 2px 0;
            letter-spacing: 0.5px;
        }

        .company-details {
            font-size: 11px;
            margin: 0;
            white-space: nowrap;
        }

        /* Layout Grid */
        .grid-header {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .grid-left {
            width: 50%;
        }

        .grid-right {
            width: 45%;
            text-align: left;
            padding-left: 20px;
        }

        .customer-card {
            border-bottom: 1px dotted #000;
            padding-bottom: 2px;
            min-height: 50px;
            margin-top: 5px;
        }

        .title-block {
            text-align: center;
            margin: 10px 0;
        }

        .title-block h1 {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 1px;
        }

        .title-block p {
            margin: 4px 0 0 0;
            font-size: 14px;
            font-weight: bold;
        }

        .field-label {
            display: inline-block;
            width: 160px;
        }

        /* Bordered Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 8px;
        }

        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 12px;
        }

        .items-table th {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer-note {
            font-size: 11px;
            margin-top: 8px;
            font-weight: bold;
        }

        /* Signatures Layout */
        .signatures-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            text-align: center;
        }

        .signature-col {
            width: 30%;
        }

        .signature-space {
            margin-top: 45px;
            font-weight: bold;
        }

        /* CSS Print overrides */
        @media print {
            .no-print-bar {
                display: none !important;
            }
            body {
                background-color: #fff;
            }
            .invoice-wrapper {
                border: none;
                margin: 0;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }
            .invoice-wrapper:not(:last-child) {
                page-break-after: always;
                break-after: page;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Navigation Bar (Screen Only) -->
    <div class="no-print-bar">
        <div class="title">📄 Print Preview Surat Jalan</div>
        <div class="actions">
            <a href="javascript:window.close();" class="btn btn-back">✕ Tutup Halaman</a>
            <button onclick="window.print();" class="btn btn-print">🖨️ Cetak Sekarang</button>
        </div>
    </div>

    <!-- Render List of Shipments -->
    @foreach ($shipments as $index => $shipment)
        <div class="invoice-wrapper">
            <!-- Header Block -->
            <div class="grid-header">
                <!-- Left: Company Info -->
                <div class="grid-left" style="width: 58%;">
                    <div class="company-header">
                        <div class="logo-sf">SF</div>
                        <div class="company-info">
                            <h2 class="company-name">PT. SULFATAMA KENCANA</h2>
                            <p class="company-details">Jl. Raya Gilang Km. 20 Ds. Beringin Bendo</p>
                            <p class="company-details">Telp. (031) 7882886 Taman - Sidoarjo</p>
                            <p class="company-details">NPWP. &nbsp; &nbsp; &nbsp;01.108.282.3.605.000</p>
                            <p class="company-details">Tgl. PPKP. : 16 - 01 - 1986</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Date and Consignee Address -->
                <div class="grid-right" style="width: 40%; font-size: 12px;">
                    <div>Surabaya, <span style="border-bottom: 1px dotted #000; padding: 0 10px;">{{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d - m - y') }}</span></div>
                    <div style="margin-top: 6px;">Kepada Yth.</div>
                    <div class="customer-card">
                        <strong>{{ $shipment->order->customer_name ?? '-' }}</strong><br>
                        <span style="font-size: 11px; color: #333;">{{ $shipment->order->delivery_address ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Divider line -->
            <hr style="border: 0; border-top: 1px solid #000; margin: 10px 0 5px 0;">

            <!-- Left Metadata: Vehicle Plate and Origin Warehouse -->
            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px;">
                <div style="width: 60%;">
                    <div><span class="field-label">Kendaraan No. Pol.</span>: <span style="border-bottom: 1px dotted #000; padding-right: 20px;">{{ $shipment->vehicle->plate_number ?? '...................' }}</span></div>
                    <div style="margin-top: 2px;"><span class="field-label">Dari Gudang</span>: <span style="border-bottom: 1px dotted #000; padding-right: 20px;">{{ $shipment->warehouse->name ?? '...................' }}</span></div>
                </div>
                <div style="width: 38%; text-align: right; font-weight: bold; font-size: 11px;">
                    <!-- Trip references if any -->
                    @if(isset($deliveryTrip))
                        Trip: {{ $deliveryTrip->trip_number }}
                    @endif
                </div>
            </div>

            <!-- Surat Jalan Title & Number -->
            <div class="title-block">
                <h1>SURAT JALAN</h1>
                <p>No. {{ $shipment->shipment_number }}</p>
            </div>

            <!-- Table of items -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">NO.</th>
                        <th style="width: 32%;">Jenis Barang</th>
                        <th style="width: 12%;">Berat Barang</th>
                        <th style="width: 13%;">Jumlah Barang</th>
                        <th style="width: 13%;">Harga Satuan</th>
                        <th style="width: 15%;">Total Harga</th>
                        <th style="width: 10%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shipment->items as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->product->weight_kg ? number_format($item->product->weight_kg, 1, ',', '.') . ' KG' : '-' }}</td>
                            <td class="text-center">KIRIM: {{ (float)$item->qty }} {{ $item->product->unit->symbol ?? 'Tbg' }}</td>
                            <td class="text-right">Rp {{ number_format($item->product->harga_jual ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format(($item->product->harga_jual ?? 0) * $item->qty, 0, ',', '.') }}</td>
                            <td>
                                @if($loop->first)
                                    {{ $shipment->notes }}
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    <!-- Pad with empty rows to preserve A5 carbon copy card proportions -->
                    @for ($i = count($shipment->items); $i < 4; $i++)
                        <tr>
                            <td class="text-center" style="color: transparent;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <!-- Footer Notes -->
            <div class="footer-note">
                Barang-barang tersebut diatas harap diterima dengan baik. (dan tidak dapat dikembalian)
            </div>

            <!-- Signatures block -->
            <div class="signatures-grid">
                <div class="signature-col">
                    <div>Pengirim / Sopir,</div>
                    <div class="signature-space">
                        ( <span style="font-weight: normal; border-bottom: 1px solid transparent; min-width: 120px; display: inline-block;">{{ $shipment->driver->name ?? '               ' }}</span> )
                    </div>
                </div>
                <div class="signature-col">
                    <div>Gudang Distribusi,</div>
                    <div class="signature-space">
                        ( <span style="font-weight: normal; border-bottom: 1px solid transparent; min-width: 120px; display: inline-block;">{{ $shipment->creator->name ?? '               ' }}</span> )
                    </div>
                </div>
                <div class="signature-col">
                    <div>Penerima,</div>
                    <div class="signature-space">
                        ( <span style="font-weight: normal; border-bottom: 1px solid transparent; min-width: 120px; display: inline-block;">{{ $shipment->order->customer_name ?? '               ' }}</span> )
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        // Trigger print dialog once page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
