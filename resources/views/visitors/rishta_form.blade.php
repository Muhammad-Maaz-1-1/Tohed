@extends('visitors.layouts.main')
@section('main-container')
    <section class="main-section masjidFormSection">
        <div class="container">
            <h2 class="entry-title">رشتے</h2>
            <div class="masjidForm">

                <form action="{{ route('rishta_form_submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                
                    <input type="text" id="full_name" name="full_name" placeholder="مکمل نام (جس کے لیئے رشتہ درکار ہے اس کا مکمل پتہ)" required>
                
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>جنس (میل یا فی میل)</option>
                        <option value="میل">میل</option>
                        <option value="فی میل">فی میل</option>
                    </select>
                
                    <input type="date" id="birthdate" name="birthdate" placeholder="تاریخ پیدایش" required>
                
                    <select id="marital_status" name="marital_status" required>
                        <option value="">موجود ازدواجی کیفیت</option>
                        <option value="سنگل">سنگل</option>
                        <option value="طلاق یافتہ">طلاق یافتہ</option>
                        <option value="پہلی بیوی موجود">پہلی بیوی موجود</option>
                        <option value="بیوی/خاوند فوت">بیوی/خاوند فوت</option>
                    </select>
                
                    <input type="text" id="city" name="city" placeholder="شہر کا نام" required>
                
                    <input type="text" id="country" name="country" placeholder="ملک کا نام" required>
                
                    <input type="text" id="mother_tongue" name="mother_tongue" placeholder="مادری زبان" required>
                
                    <input type="text" id="education" name="education" placeholder="تعلیمی قابلیت" required>
                
                    <input type="text" id="profession" name="profession" placeholder="پیشہ" required>
                <label for="">
                    <input type="text" id="ethnicity" name="ethnicity" placeholder="قوم/کاسٹ" required>
                
                    <input type="file" id="image" name="images[]" multiple placeholder="تصویر اپلوڈ کریں: (صرف مرد حضرات کے لیئے)">

                </label>
                
                    <button type="submit">Submit</button>
                </form>
                
            </div>
        </div>

    </section>
@endsection
