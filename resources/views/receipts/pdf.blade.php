<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adjustment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
        }

        .invoice-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 15px;
        }

        .invoice-column {
            display: flex;
            flex-direction: column;
        }

        .invoice-column p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }

        .invoice-column strong {
            margin-right: 10px;
            min-width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .paid {
            color: green;
            font-weight: bold;
        }

        .unpaid {
            color: red;
            font-weight: bold;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .signature {
            width: 30%;
            text-align: center;
            padding-top: 5px;
        }

        .signature-line {
            width: 100%;
            border-top: 1px solid #000;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            .invoice-header {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <h1>Receipt</h1>

    <div class="invoice-header">
        <table style="width: 100%; ">
            <tr>
                <td>
                    <p><strong>Adjustment ID:</strong> ADJ-{{ $receipt[0]->receipt_id }}</p>
                    <p><strong>Driver Name:</strong> {{ $receipt[0]->user_name }}</p>
                    <p><strong>Company Name:</strong> Knock Knock</p>
                </td>
                <td style="text-align: right;">
                    <p><strong>Adjustment Date:</strong> {{ $receipt[0]->date }}</p>
                    <p><strong>Adjustment Method:</strong> {{ $receipt[0]->deduction_way }}</p>
                    <p><strong>Slip Number:</strong>
                        @if ($receipt[0]->slip_id)
                            SLP-{{ $receipt[0]->slip_id }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>


    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Paid Amount</th>
                <th>Remaining Amount</th>
                <th>Total Amount</th>
                <th>Payment Status</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receipt as $item)
                <tr>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->payment }}</td>
                    <td>{{ $item->previous_amount }}</td>
                    <td>{{ $item->payment + $item->previous_amount }}</td>
                    <td>
                        @if ($item->previous_amount == 0)
                            <span class="paid">Paid</span>
                        @else
                            <span class="unpaid">Unpaid</span>
                        @endif
                    </td>
                    <td>{{ $item->description ?? 'No description' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th>
                    {{ $receipt->sum('payment') }}
                </th>
                <th>
                    {{ $receipt->sum('previous_amount') }}
                </th>
                <th>
                    {{ $receipt->sum(fn($item) => $item->payment + $item->previous_amount) }}
                </th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <div class="signature">
            <p>Authorized Signature</p>
            <div class="signature-line"></div>
        </div>

    </div>
</body>

</html>
