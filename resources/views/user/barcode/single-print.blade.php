<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *,*::before,*::after{
            margin:0;
            padding:0;
            box-sizing: border-box;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        .container{
            /* background-color: rgb(255, 197, 197); */
        }
        table{
            display:block;
            width: 150px;
            margin:0 auto;
            /* background: coral; */
        }
        .code-img{
            width:100%;
        }
        .code-text{
            font-size: 12px;
            font-weight: bolder;
        }
        .price-text{
            font-size: 13px;
            font-weight: bolder;
        }
        .title-text{
            font-size: 13px;
            text-transform:capitalize;
            font-weight: bolder;
        }
    </style>
</head>
    <body>
        <div class="container">
            @if ($product != null)
                <table>
                    <tr>
                        <td class="title-text" style="text-align: center;">{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <td>
                            <img class="code-img" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128", 2, 70) }}" alt="barcode">
                        </td>
                    </tr>
                    <tr>
                        <td class="code-text" style="text-align: center;">{{ $product->barcode }}</td>
                    </tr>
                    <tr>
                        <td class="price-text" style="text-align: center;">Price: BDT {{ number_format($product->retail_price, 2) }} +VAT</td>
                    </tr>
                </table>
            @endif
        </div>
        
        <script>
            document.addEventListener("DOMContentLoaded", function(event){
                window.print();
                // window.addEventListener("afterprint", function(event) { //Supports only chromium based browser
                // });
                window.close();
            });
        </script>
    </body>
</html>
