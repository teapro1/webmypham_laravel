@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="container">
    <h1 class="mb-4">Chi Tiết Danh Mục</h1>
    <div class="mb-3">
        <label for="name" class="form-label">Tên Danh Mục</label>
        <input type="text" class="form-control" id="name" value="{{ $category->name }}" readonly>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay Lại</a>
</div>
@endsection
