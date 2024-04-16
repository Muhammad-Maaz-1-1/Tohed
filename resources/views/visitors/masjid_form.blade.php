@extends('visitors.layouts.main')
@section('main-container')
    <section class="main-section masjidFormSection">
        <div class="container">
            <h2 class="entry-title">مساجد ڈائریکٹری</h2>
            <div class="masjidForm">

                <form method="post" action="{{ route('masjid_form_submit') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Masjid Name -->
                    <input type="text" placeholder=": آپ کا نام" id="name" name="name" required>
                    <input type="text" placeholder=": مسجد کا نام " id="masjid_name" name="masjid_name" required>
                    <input type="text" placeholder=": مسجد کا مکمل پتہ" id="masjid_address" name="masjid_address"
                        required>
                    <input type="text" placeholder=": شہر" id="city" name="city" required>
                    <input type="text" placeholder=": ملک" id="country" name="country" required>
                    <input type="text" id="imam_name" name="imam_name" placeholder=" : امام مسجد کا نام" required>
                    <input type="text" placeholder=": نماز جمعہ کے خطیب کا نام " id="khateeb_name" name="khateeb_name">
                    <label for="">
                        <input type="text" placeholder="مسجد انتظامیہ کا رابطہ نمبر" id="contact_number"
                            name="contact_number" required>
                        <input type="file" id="images" name="images[]" multiple>
                        <label for="contact_number"></label>

                    </label>
                    <!-- Contact Number -->

                    <!-- Submit Button -->
                    <button class="btn" type="submit">جمع کرائیں</button>
                </form>
            </div>
        </div>

    </section>
@endsection
