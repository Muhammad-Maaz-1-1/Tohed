@include('admin.layouts.header')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="p-5 shadow-lg ">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Edit Rishta</h1>
            </div>
            <form class="row" action="{{ route('rishta_form_update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $rishta->id }}">
                <div class="col-lg-6 mb-3 mt-2">

<input class="form-control form-control-user" value="{{ $rishta->full_name }}" type="text"
                        id="full_name" name="full_name" placeholder="مکمل نام (جس کے لیئے رشتہ درکار ہے اس کا مکمل پتہ)"
                        required>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <select class="form-control form-control-user" id="gender" name="gender" required>
                        <option value="{{ $rishta->gender }}" selected>{{ $rishta->gender }}</option>
                        <option value="میل">میل</option>
                        <option value="فی میل">فی میل</option>
                    </select>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <input class="form-control form-control-user" type="date" id="birthdate" name="birthdate"
                        placeholder="تاریخ پیدایش" value="{{ $rishta->birthdate }}" required>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <select class="form-control form-control-user" id="marital_status" name="marital_status" required>
                        <option value="{{ $rishta->marital_status }}">{{ $rishta->marital_status }}</option>
                        <option value="">موجود ازدواجی کیفیت</option>
                        <option value="سنگل">سنگل</option>
                        <option value="طلاق یافتہ">طلاق یافتہ</option>
                        <option value="پہلی بیوی موجود">پہلی بیوی موجود</option>
                        <option value="بیوی/خاوند فوت">بیوی/خاوند فوت</option>
                    </select>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <input value="{{ $rishta->city }}" class="form-control form-control-user" type="text"
                        id="city" name="city" placeholder="شہر کا نام" required>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <input value="{{ $rishta->country }}" class="form-control form-control-user" type="text"
                        id="country" name="country" placeholder="ملک کا نام" required>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <input value="{{ $rishta->mother_tongue }}" class="form-control form-control-user" type="text"
                        id="mother_tongue" name="mother_tongue" placeholder="مادری زبان" required>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <input value="{{ $rishta->education }}" class="form-control form-control-user" type="text"
                        id="education" name="education" placeholder="تعلیمی قابلیت" required>
                </div>
                <div class="col-lg-6 mb-3 mt-2">
                    <input value="{{ $rishta->profession }}" class="form-control form-control-user" type="text"
                        id="profession" name="profession" placeholder="پیشہ" required>
                </div>
                <div class="col-lg-6 mb-3 mt-2">

                    <input value="{{ $rishta->ethnicity }}" class="form-control form-control-user" type="text"
                        id="ethnicity" name="ethnicity" placeholder="قوم/کاسٹ" required>
                </div>
                <div class="col-lg-12 mb-3 mt-2">
                    <input class="form-control form-control-user" type="file" id="image" name="images[]"
                        placeholder="تصویر اپلوڈ کریں: (صرف مرد حضرات کے لیئے)">
                    <div class="editImages">

                        @if ($rishta->images && (is_array($rishta->images) || strpos($rishta->images, ',') !== false))
                            @php
                                $imageUrls = is_array($rishta->images)
                                    ? $rishta->images
                                    : explode(',', $rishta->images);
                            @endphp
                            @foreach ($imageUrls as $imageUrl)
                                <img width="100" height="100" src="{{ asset('uploads/' . trim($imageUrl)) }}"
                                    alt="Image">
                            @endforeach
                        @elseif ($rishta->images)
                            <img width="100" height="100" src="{{ asset('uploads/' . $rishta->images) }}"
                                alt="Image">
                        @endif
                    </div>
                </div>
                <div class="col-lg-12 mb-3 mt-2">
                    <textarea name="content"  id="rishtaEditor" cols="30" rows="10">
                        {{ $rishta->content }}
                    </textarea>
                    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
                    <script>
                        ClassicEditor
                            .create( document.querySelector( '#rishtaEditor' ) )
                            .catch( error => {
                                console.error( error );
                            } );
                    </script>
                    
                    



                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>

        </div>
    </div>


    @include('admin.layouts.footer')
