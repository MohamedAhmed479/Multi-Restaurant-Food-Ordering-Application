@extends('client.dashboard')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Start Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Admin All Report</h4>
                    </div>
                </div>
            </div>
            <!-- End Page Title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3 style="font-weight: bold; color: #007bff;">Search Reports</h3>
                            </div>

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                <!-- Search By Date -->
                                <div class="col">
                                    <div class="card border" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                        <form id="myForm" action="{{ route('client.search.bydate') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body text-center">
                                                <h5 style="color: #28a745;">Search By Date</h5>
                                                <label for="date-input" class="form-label">Select Date:</label>
                                                <input id="date-input" type="date" name="date" class="form-control"
                                                    style="text-align: center;">
                                                <button type="submit" class="btn btn-success mt-3">Search</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Search By Month -->
                                <div class="col">
                                    <div class="card border" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                        <form id="myForm" action="{{ route('client.search.bymonth') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body text-center">
                                                <h5 style="color: #17a2b8;">Search By Month</h5>
                                                <label for="month-input" class="form-label">Select Month:</label>
                                                <select id="month-input" name="month" class="form-select"
                                                    style="text-align: center;">
                                                    <option selected>Select Month</option>
                                                    @foreach (['Janurary', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                                        <option value="{{ $month }}">{{ $month }}</option>
                                                    @endforeach
                                                </select>
                                                <label for="year-input" class="form-label mt-2">Select Year:</label>
                                                <select id="year-input" name="year_name" class="form-select"
                                                    style="text-align: center;">
                                                    <option selected>Select Year</option>
                                                    @for ($year = 2022; $year <= 2026; $year++)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endfor
                                                </select>
                                                <button type="submit" class="btn btn-info mt-3">Search</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Search By Year -->
                                <div class="col">
                                    <div class="card border" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                        <form id="myForm" action="{{ route('client.search.byyear') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body text-center">
                                                <h5 style="color: #ffc107;">Search By Year</h5>
                                                <label for="year-only-input" class="form-label">Select Year:</label>
                                                <select id="year-only-input" name="year" class="form-select"
                                                    style="text-align: center;">
                                                    <option selected>Select Year</option>
                                                    @for ($year = 2022; $year <= 2026; $year++)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endfor
                                                </select>
                                                <button type="submit" class="btn btn-warning mt-3">Search</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Col -->
            </div> <!-- End Row -->

        </div> <!-- End Container-Fluid -->
    </div>
@endsection
