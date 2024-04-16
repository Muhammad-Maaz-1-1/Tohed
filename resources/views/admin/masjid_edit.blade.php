@include('admin.layouts.header')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="p-5 shadow-lg ">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Edit Masjid</h1>
            </div>
            <form id="yourFormId" method="post" action="{{ route('masjid_form_update') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-6 mb-3 mt-2">
                        <input type="hidden" name="id" value="{{ $masjid->id }}">

                        <input class="form-control form-control-user" value="{{ $masjid->name }}" type="text"
                            placeholder="آپ کا نام:" id="name" name="name" required>
                    </div>
                    <div class="col-lg-6 mb-3 mt-2">

                        <!-- Masjid Name -->
                        <input class="form-control form-control-user" value="{{ $masjid->masjid_name }}" type="text"
                            placeholder="مسجد کا نام :" id="masjid_name" name="masjid_name" required>
                    </div>
                    <div class="col-lg-6 mb-3 mt-2">

                        <input class="form-control form-control-user" value="{{ $masjid->masjid_address }}"
                            type="text" placeholder="مسجد کا مکمل پتہ :" id="masjid_address" name="masjid_address"
                            required>
                    </div>
                    <div class="col-lg-6 mb-3 mt-2">

                        <input class="form-control form-control-user" type="text" value="{{ $masjid->city }}"
                            placeholder="شہر :" id="city" name="city" required>
                    </div>
                    <div class="col-lg-6 mb-3 mt-2">

                        <input class="form-control form-control-user" value="{{ $masjid->country }}" type="text"
                            placeholder="ملک :" id="country" name="country" required>
                    </div>
                    <div class="col-lg-6 mb-3 mt-2">

                        <input class="form-control form-control-user" type="text" value="{{ $masjid->imam_name }}"
                            id="imam_name" name="imam_name" placeholder=" امام مسجد کا نام :" required>
                    </div>
                    <div class="col-lg-6 mb-3 mt-2">

                        <input class="form-control form-control-user" value="{{ $masjid->khateeb_name }} "
                            type="text" placeholder="نماز جمعہ کے خطیب کا نام :" id="khateeb_name"
                            name="khateeb_name">
                    </div>
                    <div class="col-lg-6 mb-3 mt-2">

                        <input class="form-control form-control-user" value="{{ $masjid->contact_number }}"
                            type="text" placeholder="مسجد انتظامیہ کا رابطہ نمبر" id="contact_number"
                            name="contact_number" required>
                    </div>
                    <div class="col-lg-12 mb-1 mt-2">
                        <input class="form-control form-control-user" type="file" id="images" name="images[]"
                            multiple>
                        <div class="editImages">

                            @if ($masjid->images && (is_array($masjid->images) || strpos($masjid->images, ',') !== false))
                                @php
                                    $imageUrls = is_array($masjid->images)
                                        ? $masjid->images
                                        : explode(',', $masjid->images);
                                @endphp
                                @foreach ($imageUrls as $imageUrl)
                                    <img width="100" height="100" src="{{ asset('uploads/' . trim($imageUrl)) }}"
                                        alt="Image">
                                @endforeach
                            @elseif ($masjid->images)
                                <img width="100" height="100" src="{{ asset('uploads/' . $masjid->images) }}"
                                    alt="Image">
                            @endif
                        </div>





                        <label for="contact_number"></label>
                    </div>
                    <div class="col-lg-12 mb-3 mt-2">
                        <textarea name="content"  id="editor" cols="30" rows="10">
                            {{ $masjid->content }}
                        </textarea>
                        <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
                        <script>
                            ClassicEditor
                                .create( document.querySelector( '#editor' ) )
                                .catch( error => {
                                    console.error( error );
                                } );
                        </script>
                        
                        



                    </div>
                    {{-- <div class="col-lg-12 mb-3 mt-2">

                        <textarea class="form-control form-control-user"  name="content" id="" cols="10" rows="4" placeholder="یہاں مواد درج کریں۔">{{ $masjid->content }}</textarea>
                    </div> --}}

                    <!-- Contact Number -->

                    <!-- Submit Button -->
                    <button class="btn" type="submit">اپ ڈیٹ
                    </button>
                </div>

            </form>
    
        </div>
    </div>


    @include('admin.layouts.footer')
