@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 1rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }
    h1 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: #333;
    }
    form > div {
        margin-bottom: 1rem;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #444;
    }
    input[type="text"],
    input[type="date"],
    input[list],
    input[type="file"] {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 1rem;
    }
    input[type="text"]:focus,
    input[type="date"]:focus,
    input[list]:focus,
    input[type="file"]:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 3px #3b82f6;
    }
    button {
        background-color: #2563eb;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }
    button:hover {
        background-color: #1e40af;
    }
    a.cancel-link {
        margin-left: 1rem;
        color: #555;
        text-decoration: none;
        font-weight: 500;
    }
    a.cancel-link:hover {
        text-decoration: underline;
        color: #000;
    }
    .error-box {
        background-color: #fecaca;
        color: #b91c1c;
        padding: 0.75rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }
    .error-box ul {
        margin: 0;
        padding-left: 1.25rem;
    }
</style>

<div class="container">
    <h1>เพิ่มสมาชิกใหม่</h1>

    @if($errors->any())
    <div class="error-box">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="title">คำนำหน้า</label>
            <input list="titles" name="title" id="title" value="{{ old('title') }}" required />
            <datalist id="titles">
                @foreach($titles as $title)
                <option value="{{ $title }}"></option>
                @endforeach
            </datalist>
        </div>

        <div>
            <label for="first_name">ชื่อ</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required />
        </div>

        <div>
            <label for="last_name">นามสกุล</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required />
        </div>

        <div>
            <label for="birthdate">วันเดือนปีเกิด</label>
            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" required />
        </div>

        <div>
            <label for="profile_image">รูปภาพโปรไฟล์ (ถ้ามี)</label>
            <input type="file" name="profile_image" id="profile_image" accept="image/*" />
        </div>

        <div>
            <button type="submit">บันทึก</button>
            <a href="{{ route('members.index') }}" class="cancel-link">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection
