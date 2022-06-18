@extends('layouts.admin')

@section('title', $title)

@push('style')
<link href="{{ asset('public/css/pos.css') }}" rel="stylesheet">
@endpush
@section('content')
<!-- pos -->
<pos-component></pos-component>
@endsection

@push('script')
<script>
    $(document).ready(function () {
            $(".pos .customer-modal .customer-list label input").click(function(){
                var targetElement = $(this).closest('label');
                var parentLi = targetElement.closest('li');
                var grandParentUl = $(this).closest('ul');
                targetElement.addClass('active');
                grandParentUl.find('li').not(parentLi).find('label').removeClass('active');
            })
        });
</script>
@endpush
