@extends('layouts.app')

@section('title', 'Import Excel File')

@section('content')
    <div class="customer">
        <div class="container">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <span class="mr-auto">Import Excel File</span>
                    <a href="{{ route('stock.index') }}" title="All stock"
                       class="btn btn-primary ml-2 print-none"><i class="fas fa-list"></i></a>
                </div>

                <div class="card-body ">
                    <form action="{{ route('stock.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row gx-3 gy-1">
                            <div class="col-12">
                                <label for="logo" class="form-label">Excel File</label>
                                <input type="file" name="stock_excel" class="form-control" id="logo">
                            </div>

                            <div class="col-md-6">
                                <a href="{{ route('stock.index') }}" class="btn btn-secondary btn-block">Back to list</a>
                            </div>

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
