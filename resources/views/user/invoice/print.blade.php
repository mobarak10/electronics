<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labimart</title>
    <style>
        * {
            font-size: 12px;
            font-family: 'Times New Roman';
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .bill-paper {
            width: 80mm;
        }
        .brand {
            font-weight: bolder;
            font-size: 14px;
            text-align: center;
            text-transform: uppercase;
        }
        .details {
            border-bottom: 1px solid;
        }
        .details ul {
            margin: 0;
            text-align: center;
        }
        .invoice {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid;
            padding: 3px 0 1px;
        }
        .customer {
            text-align: center;
            border-bottom: 1px dashed;
            padding: 4px 0 3px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
        }
        table, tr, th, td {
            border-bottom: 1px dashed;
            border-collapse: collapse;
        }
        @media print {
            .bill-paper {
                font-size: 12px;
            }
        }
    </style>
</head>
    <body>
        <div class="container">

                <!-- Bill paper -->
                <div class="bill-paper">
                    <div class="brand">
                        {{ $business->name }}
                    </div>
                    <div class="details">
                        <ul>
                            <li>{{ $business->address ?? '' }}</li>
    						<li>{{ $business->email ?? '' }}</li>
                            <li>{{ $business->phone ?? '' }}</li>
                        </ul>
                    </div>
                    <div class="invoice">
                        <div>Inv. No: # {{ $sale->invoice_no }}</div>
                        <div>Date: {{ $sale->created_at->format('d/m/y') }}</div>
                    </div>
                    <div class="customer">
                        {{ $sale->customer->name }} @if($sale->customer->phone) - {{ $sale->customer->phone }}  @endif($sale->customer->phone)
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <th style="text-align:center;">Name</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Price</th>
                            <th style="text-align:right;">Line total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sale->saleDetails as $details)
                            <tr>
                                <td style="text-align:center;">{{ $details->product->name ?? '' }}</td>
                                <td style="text-align:center;">{{ $details->total_quantity_in_format['display'] }}</td>
                                <td style="text-align:right;">{{ $details->sale_price }}</td>
                                <td style="text-align:right;">{{ number_format($details->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="4" style="text-align:right;">Subtotal : {{ number_format($sale->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right;">VAT(7.5%) : {{ number_format($calculated_amount['vat'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right;">Total Discount : {{ number_format($calculated_amount['discount'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right;">Grand Total : {{ number_format($calculated_amount['grand_total'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right;">Previous Balance : {{ number_format($calculated_amount['previous_balance'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right;">Paid : {{ number_format($calculated_amount['paid'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:right;">Current Balance : {{ number_format($sale->customer_balance, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="text-align: center">
                        <strong>Notes</strong>
                        <p>1) Physically damaged product item is not for exchange/refund</p>
                        <p>2) If you find any problem please contact with us</p>
                        <strong>Thanks for coming</strong>
                    </div>
                </div>
                <!-- End of the bill paper -->

            </div>

        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function(event){
                window.print();
                window.addEventListener("afterprint", function(event) { //Supports only chromium based browser
                    window.close();
                });
                // window.close();
            });
        </script>
    </body>
</html>
