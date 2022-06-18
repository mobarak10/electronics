@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
            <!-- expense entry component -->
			<income-update-component :income="{{ $income }}" :income-sectors="{{ $income_sectors }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}"></income-update-component>
		</div>
	</div>
</div>
@endsection
