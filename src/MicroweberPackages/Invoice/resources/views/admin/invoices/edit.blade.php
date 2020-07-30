@extends('invoice::admin.layout')

@section('title', 'Manage Invoice')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <script>
        /**
         * bojkata bojkata bojkata
         */
        class Invoice {
            constructor() {
                this.discountType = 0.00;
                this.discountVal = "fixed";
                this.total = 0.00;
                this.subTotal = 0.00;
            }

            addNewItem(item) {

                var itemId = Math.floor(Math.random() * (10000 - 100 + 1)) + 100000;

                if (typeof(item) == 'undefined') {
                    item = {
                        name: '',
                        description: '',
                        price:0,
                        quantity: 1,
                    };
                }

                $('.js-invoice-items').append(this.invoiceItemTemplate(itemId, item.name, item.description, item.price, item.quantity));
                this.calculate();
            }

            removeItem(itemId) {
                $('.js-invoice-item-' + itemId).remove();
                this.calculate();
            }

            calculate() {

                var itemsTotal = 0;
                $(".js-invoice-item").each(function(i) {

                    var itemPrice = $(this).find('.js-invoice-item-price').val();
                    var itemPricePrecision = $(this).find('.js-invoice-item-price').val();
                    var itemQuantity = $(this).find('.js-invoice-item-quantity').val();

                    itemPrice = parseFloat(itemPrice);
                    itemQuantity = parseInt(itemQuantity);
                    itemPricePrecision = parseFloat(itemPricePrecision) * 100;

                    itemsTotal = itemsTotal + (itemPrice * itemQuantity);
                    var itemTotal = (itemPrice * itemQuantity);

                    $(this).find('.js-invoice-item-price').val(parseFloat(itemPrice).toFixed(2));
                    $(this).find('.js-invoice-item-price-precision').val(itemPricePrecision);
                    $(this).find('.js-invoice-item-quantity').val(itemQuantity);
                    $(this).find('.js-invoice-item-price-total').html(parseFloat(itemTotal).toFixed(2));
                    $(this).find('.js-invoice-item-total-precision').val(parseFloat(itemTotal).toFixed(2));
                });

                this.total = itemsTotal;
                this.subTotal = itemsTotal;

                // Calculate discount
                this.discountVal = $('.js-invoice-discount-val').val();
                this.discountType = $('.js-invoice-discount-type').val();

                this.discountVal = parseFloat(this.discountVal);

                var discountValPrecision = (this.discountVal * 100);

                if (this.discountType == 'fixed') {
                    this.total = (this.total - parseFloat(this.discountVal));
                }

                if (this.discountType == 'precentage') {
                    this.total = (this.total * ((100 - this.discountVal) / 100 ));
                }

                $('.js-invoice-discount-val').val(parseFloat(this.discountVal).toFixed(2));
                $('.js-invoice-discount-val-precision').val(discountValPrecision);
                $('.js-invoice-total-precision').val(parseFloat(this.total) * 100);
                $('.js-invoice-total').val(parseFloat(this.total).toFixed(2));
                $('.js-invoice-sub-total-precision').val(parseFloat(this.subTotal) * 100);
                $('.js-invoice-sub-total').val(parseFloat(this.subTotal).toFixed(2));
            }

            invoiceItemTemplate(itemId, name, description, price, quantity) {

                price = parseFloat(price).toFixed(2);
                quantity = parseInt(quantity);

                var totalPrice = (price * quantity);

                return '<tr class="js-invoice-item js-invoice-item-'+itemId+'">' +
                    '<td>' +
                    '    <input type="text" value="' + name + '" class="form-control js-invoice-item-input" name="items[' + itemId + '][name]" placeholder="Type or click to select an item">' +
                    '    <textarea style="margin-top:5px;border:0px;background: none" name="items[' + itemId + '][description]"  placeholder="Type item Description (optional)" class="form-control js-invoice-item-input">' +
                    description +
                    '</textarea>' +
                    '</td>' +
                    '<td>' +
                    '    <input type="text" class="form-control js-invoice-item-input js-invoice-item-quantity" name="items[' + itemId + '][quantity]" value="' + quantity + '">' +
                    '</td>' +
                    '<td>' +
                    '    <input type="text" class="form-control js-invoice-item-input js-invoice-item-price" value="' + price + '">' +
                    '    <input type="hidden" class="js-invoice-item-price-precision" name="items[' + itemId + '][price]" value="' + price + '">' +
                    '    <input type="hidden" class="js-invoice-item-total-precision" name="items[' + itemId + '][total]" value="' + totalPrice + '">' +
                    '</td>' +
                    '<td>' +
                    '<span class="js-invoice-item-price-total">0.00</span>' +
                    '</td>' +
                    '<td style="text-align: center;width: 10px">' +
                    '    <button class="btn btn-danger" type="button" onclick="invoice.removeItem(' + itemId + ')"><i class="fa fa-times"></i></button>' +
                    '</td>' +
                    '</tr>';
            }
        }

        $(document).ready(function () {
            invoice = new Invoice();
            @if(isset($invoice) && $invoice)
                @foreach($invoice->items as $invoiceItem)
                    invoice.addNewItem({
                        name: '{{ $invoiceItem->name }}',
                        description: '{{ $invoiceItem->description }}',
                        price: {{ ($invoiceItem->price/100) }},
                        quantity: {{ $invoiceItem->quantity }}
                    });
                @endforeach
            @else
                invoice.addNewItem();
            @endif
            invoice.calculate();

            $('body').on('change', '.js-invoice-item-input', function () {
                invoice.calculate();
            });

            $('.js-invoice-select-customer').click(function () {
                $('.js-invoice-select-customer-modal').modal();
            });
        });
    </script>


    @if(isset($invoice) && $invoice)
        <form method="post" action="{{ route('invoices.update', $invoice->id) }}">
    @method('PUT')
    @else
        <form method="post" action="{{ route('invoices.store') }}">
    @endif
    @csrf
        <div class="modal js-invoice-select-customer-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Select customer</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="input-group">
                                <select class="form-control typeahead border-primary" name="customer_id" placeholder="Start typing something to search customers...">
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                                    @endforeach
                                </select>
                                {{-- <div class="input-group-append">
                                     <button type="submit" class="btn btn-outline-primary">
                                         <i class="fa fa-search"></i>
                                     </button>
                                 </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" style="float:left;" data-dismiss="modal"><i class="fa fa-check"></i> Select customer</button>
                        <a href="" class="btn btn-primary"><i class="fa fa-plus"></i> Add new customer</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @csrf

        <div class="row">

            <style>
                .well-box {
                    width: 100%;
                    background: #fff;
                    border-radius: 3px;
                    padding-top: 15px;
                    padding-bottom: 15px;
                    border: 1px solid #ced4da;
                }
            </style>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="invoice-select-customer-box" style="text-align: center">
                            <button type="button" class="well-box js-invoice-select-customer" style="line-height:85px;font-size:20px;">
                                <i class="fa fa-user"></i> &nbsp;
                                @if(isset($invoice) && $invoice->customer)
                                    {{$invoice->customer->first_name}}
                                    {{$invoice->customer->last_name}}
                                @else
                                     Select Customer *
                                @endif
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice Date:</label>
                                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}"
                                           name="invoice_date" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice Due Date:</label>
                                    <input type="date" class="form-control"
                                           value="{{ date('Y-m-d', strtotime('+6 days', strtotime(date('Y-m-d')))) }}"
                                           name="due_date"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice Number:</label>
                                    <input type="text" disabled="disabled" class="form-control"
                                           value="@if(isset($invoice) && $invoice){{ $invoice->invoice_number }}@else{{ $nextInvoiceNumber }} @endif"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ref Number:</label>
                                    <input type="text" class="form-control" value=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-top:25px;">
                <div class="row">
                    <div class="col-md-9">

                        <table class="js-invoice-table table table-bordered">
                            <thead>
                            <tr>
                                <th>Items</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="js-invoice-items"></tbody>
                        </table>

                        <button class="btn btn-success" type="button" onclick="invoice.addNewItem();"><i
                                    class="fa fa-shopping-basket"></i> Add new item
                        </button>


                    </div>
                    <div class="col-md-3">
                        <div class="well-box">

                            <div class="form-group col-md-12">
                                <label>Sub total:</label>
                                <input type="text" disabled="disabled" class="form-control js-invoice-sub-total" value="0.00"/>
                            </div>

                            <div class="container">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Discount:</label>
                                        <input type="text" class="form-control js-invoice-discount-val" onchange="invoice.calculate();" value="@if(isset($invoice) && $invoice){{ ($invoice->discount_val/100) }}@endif" />
                                        <input type="hidden" class="js-invoice-discount-val-precision" onchange="invoice.calculate();" name="discount_val"/>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Discount Type:</label>
                                        <select class="form-control js-invoice-discount-type" onchange="invoice.calculate();" name="discount">
                                            <option @if(isset($invoice) && $invoice && $invoice->discount == 'fixed') selected="selected" @endif value="fixed">Fixed</option>
                                            <option @if(isset($invoice) && $invoice && $invoice->discount == 'precentage') selected="selected" @endif value="precentage">Precentage</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        <div class="col-md-12" style="text-align: right">
                                <div class="form-group">
                                    <label>Taxes:</label>
                                    <br/>
                                    <select class="form-control" name="tax_ids" multiple>
                                    @foreach($taxTypes as $taxType)
                                        <option value="{{$taxType->id}}">{{$taxType->name}} - {{$taxType->percent }} % </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Total:</label>
                                <input type="text" disabled="disabled" class="form-control js-invoice-total"
                                       value="0.00"/>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-top:35px;">
                <label>Invoice Template:</label>
                <select class="form-control" name="invoice_template_id">
                    @foreach($invoiceTemplates as $invoiceTemplate):
                    <option value="{{ $invoiceTemplate->id }}">{{ $invoiceTemplate->name }}</option>
                        @endforeach
                </select>
            </div>

            <input type="hidden" value="1" name="tax" />
            <input type="hidden" value="0.00" class="js-invoice-total-precision" name="total"/>
            <input type="hidden" value="0.00" class="js-invoice-sub-total-precision" name="sub_total"/>
            <input type="hidden" value="@if(isset($invoice) && $invoice) {{ $invoice->invoice_number }}@else{{$nextInvoiceNumber }}@endif" name="invoice_number" />

            <div class="col-md-12" style="margin-top:15px;">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Invoice</button>
            </div>

        </div>
    </form>

    </div>
@endsection
