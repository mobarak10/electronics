@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
            <!-- expense entry component -->
			<expense-entry-component :gl-accounts="{{ $glAccounts }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}"></expense-entry-component>
            
		</div>
	</div>
</div>
@endsection