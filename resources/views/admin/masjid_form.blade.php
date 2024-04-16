@include('admin.layouts.header')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="p-5 shadow-lg ">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Add new Masjid</h1>
            </div>
                  <form  method="post" action="{{ route('masjid_form_submit') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
<div class="col-lg-6 mb-3 mt-2">

    <input class="form-control form-control-user" type="text" placeholder="آپ کا نام:" id="name" name="name" required>
</div>
<div class="col-lg-6 mb-3 mt-2">

    <!-- Masjid Name -->
    <input class="form-control form-control-user" type="text" placeholder="مسجد کا نام :" id="masjid_name" name="masjid_name" required>
</div>
<div class="col-lg-6 mb-3 mt-2">

    <input class="form-control form-control-user" type="text" placeholder="مسجد کا مکمل پتہ :" id="masjid_address" name="masjid_address"
    required>
</div>
<div class="col-lg-6 mb-3 mt-2">

    <input class="form-control form-control-user" type="text" placeholder="شہر :" id="city" name="city" required>
</div>
<div class="col-lg-6 mb-3 mt-2">

    <input class="form-control form-control-user" type="text" placeholder="ملک :" id="country" name="country" required>
</div>
<div class="col-lg-6 mb-3 mt-2">

    <input class="form-control form-control-user" type="text" id="imam_name" name="imam_name" placeholder=" امام مسجد کا نام :" required>
</div>
<div class="col-lg-6 mb-3 mt-2">

    <input class="form-control form-control-user" type="text" placeholder="نماز جمعہ کے خطیب کا نام :" id="khateeb_name" name="khateeb_name">
</div>
<div class="col-lg-6 mb-3 mt-2">

        <input class="form-control form-control-user" type="text" placeholder="مسجد انتظامیہ کا رابطہ نمبر" id="contact_number"
        name="contact_number" required>
</div>
<div class="col-lg-12 mb-3 mt-2">
    <input class="form-control form-control-user" type="file" id="images" name="images[]" multiple>
    <label for="contact_number"></label>
</div>
                    <!-- Contact Number -->

                    <!-- Submit Button -->
                    <button class="btn" type="submit">جمع کرائیں</button>
                </div>

                </form>
    </div>
</div>


@include('admin.layouts.footer')
