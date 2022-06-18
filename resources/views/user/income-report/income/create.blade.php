@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
            <!-- expense entry component -->
			<income-entry-component :income-sectors="{{ $income_sectors }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}"></income-entry-component>
		</div>
	</div>
</div>
@endsection
