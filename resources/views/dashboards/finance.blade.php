@extends('layouts.vertical', ['title' => 'Finance', 'subTitle' => 'Dashboards'])

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-0 fw-bold mb-2">
                            $55.6k
                        </h3>
                        <p class="text-muted">
                            Kontostand
                        </p>
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                <iconify-icon icon="iconamoon:credit-card-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-0 fw-bold mb-2">
                            $75.09k
                        </h3>
                        <p class="text-muted">
                            Einnahmen
                        </p>
                        <span class="badge fs-12 badge-soft-success"><i class="ti ti-arrow-badge-up"></i>
                            7.36%</span>
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <iconify-icon icon="iconamoon:store-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-0 fw-bold mb-2">
                            $62.8k
                        </h3>
                        <p class="text-muted">
                            Ausgaben
                        </p>
                        <span class="badge fs-12 badge-soft-danger"><i class="ti ti-arrow-badge-up"></i>
                            5.62%</span>
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-success-subtle text-success rounded-circle">
                                <iconify-icon icon="iconamoon:3d-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->

    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted">
                            Neune Kassen Eintrag erstellen
                        </p>
                    </div>
                    <div>
                        <div class="avatar-lg d-inline-block me-1">
                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <iconify-icon icon="iconamoon:3d-duotone" class="fs-32"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Revenue</h4>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-light">
                        ALL
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light">
                        1M
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light">
                        6M
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light active">
                        1Y
                    </button>
                </div>
            </div>
            <!-- end card-title-->

            <div class="card-body">
                <div dir="ltr">
                    <div id="dash-revenue-chart" class="apex-charts"></div>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->

    
    <!-- end col -->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xxl-8">
        <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="card-title">Transactions</h4>
                <div class="flex-shrink-0">
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm">
                            <option selected="">All</option>
                            <option value="0">Paid</option>
                            <option value="1">
                                Cancelled
                            </option>
                            <option value="2">
                                Failed
                            </option>
                            <option value="2">
                                Onhold
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive table-card">
                    <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                        <thead class="bg-light bg-opacity-50 thead-sm">
                            <tr>
                                <th scope="col">Typ</th>
                                <th scope="col">
                                    Kategorie
                                </th>
                                <th scope="col">Notiz</th>
                                <th scope="col">
                                    Erstellt von
                                </th>
                                <th scope="col">Betrag</th>
                            </tr>
                        </thead>

                        
                        <!-- end tbody -->
                    </table>
                    <!-- end table -->
                </div>
                <!-- end table responsive -->
            </div>
            <!-- End Card-body -->
            <div class="card-footer border-top border-light">
                <div class="align-items-center justify-content-between row text-center text-sm-start">
                    <div class="col-sm">
                        <div class="text-muted">
                            Showing
                            <span class="fw-semibold text-body">
                                5
                            </span>
                            of
                            <span class="fw-semibold">
                                15
                            </span>
                            Transactions
                        </div>
                    </div>
                    <div class="col-sm-auto mt-3 mt-sm-0">
                        <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                            <li class="page-item disabled">
                                <a href="#" class="page-link"><i class="bx bxs-chevron-left"></i></a>
                            </li>
                            <li class="page-item active">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">3</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link"><i class="bx bxs-chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- end card-->
    </div>
    <!-- end col -->
    <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
@vite(['resources/js/pages/dashboard.finance.js'])
@endsection