@extends('layouts.vertical', ['title' => 'Invoice Details', 'subTitle' => 'Invoice'])

@section("content")

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Logo & title -->
                <div class="clearfix">
                    <div class="float-sm-end">
                        <div class="auth-logo">
                            <img class="logo-dark me-1" src="/images/logo-dark.png" alt="logo-dark" height="24" />
                        </div>
                        <address class="mt-3">
                            1729 Bangor St,<br />
                            Houlton, ME, 04730 <br />
                            <abbr title="Phone">P:</abbr>
                            (207) 532-9109
                        </address>
                    </div>
                    <div class="float-sm-start">
                        <h5 class="card-title mb-2">
                            Invoice: #RB89562
                        </h5>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="fw-normal text-muted">
                            Customer
                        </h6>
                        <h6 class="fs-16">Glenn H Smith</h6>
                        <address>
                            135 White Cemetery Rd,<br />
                            Perryville, KY, 40468<br />
                            <abbr title="Phone">P:</abbr>
                            (304) 584-4345
                        </address>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive table-borderless text-nowrap mt-3 table-centered">
                            <table class="table mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="border-0 py-2">
                                            Product Name
                                        </th>
                                        <th class="border-0 py-2">
                                            Quantity
                                        </th>
                                        <th class="border-0 py-2">
                                            Price
                                        </th>
                                        <th class="text-end border-0 py-2">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <!-- end thead -->
                                <tbody>
                                    <tr>
                                        <td>
                                            G15 Gaming
                                            Laptop
                                        </td>
                                        <td>3</td>
                                        <td>$240.59</td>
                                        <td class="text-end">
                                            $721.77
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Sony Alpha ILCE
                                            6000Y 24.3 MP
                                            Mirrorless
                                            Digital SLR
                                            Camera
                                        </td>
                                        <td>5</td>
                                        <td>$135.99</td>
                                        <td class="text-end">
                                            $679.95
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Sony Over-Ear
                                            Wireless
                                            Headphone with
                                            Mic
                                        </td>
                                        <td>1</td>
                                        <td>$99.49</td>
                                        <td class="text-end">
                                            $99.49
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td>
                                            Adam ROMA USB-C
                                            / USB-A 3.1
                                            (2-in-1 Flash
                                            Drive) – 128GB
                                        </td>
                                        <td>2</td>
                                        <td>$350.19</td>
                                        <td class="text-end">
                                            700.38
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- end tbody -->
                            </table>
                            <!-- end table -->
                        </div>
                        <!-- end table responsive -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row mt-3">
                    <div class="col-sm-7">
                        <div class="clearfix pt-xl-3 pt-0">
                            <h6 class="text-muted">
                                Notes:
                            </h6>

                            <small class="text-muted">
                                All accounts are to be paid
                                within 7 days from receipt
                                of invoice. To be paid by
                                cheque or credit card or
                                direct payment online. If
                                account is not paid within 7
                                days the credits details
                                supplied as confirmation of
                                work undertaken will be
                                charged the agreed quoted
                                fee noted above.
                            </small>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="float-end">
                            <p>
                                <span class="fw-medium">Sub-total :</span>
                                <span class="float-end">$2266.59</span>
                            </p>
                            <p>
                                <span class="fw-medium">Discount (10%) :</span>
                                <span class="float-end">
                                    &nbsp;&nbsp;&nbsp;
                                    $226.659</span>
                            </p>
                            <h3>$2039.931 USD</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="mt-5 mb-1">
                    <div class="text-end d-print-none">
                        <a href="javascript:window.print()" class="btn btn-primary">Print</a>
                        <a href="javascript:void(0);" class="btn btn-outline-primary">Submit</a>
                    </div>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>

@endsection