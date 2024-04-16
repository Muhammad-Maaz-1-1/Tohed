@extends('visitors.layouts.main')
@section('main-container')
    <section class="main-section detailSection">
        <div class="container">
            <div class="containerWrap">

                <h2>{{ $masjidModel->masjid_name }}</h2>
                <div class="detailSlider">
                    @if ($masjidModel->images && (is_array($masjidModel->images) || strpos($masjidModel->images, ',') !== false))
                    @php
                        $imageUrls = is_array($masjidModel->images) ? $masjidModel->images : explode(',', $masjidModel->images);
                    @endphp
                    @foreach ($imageUrls as $imageUrl)
                        <img width="100" height="100" src="{{ asset('uploads/' . trim($imageUrl)) }}" alt="Image">
                    @endforeach
                @elseif ($masjidModel->images)
                    <img width="100" height="100" src="{{ asset('uploads/' . $masjidModel->images) }}" alt="Image">
                @endif
                </div>

                <div class="detailSectionWrap mt-4">
                    <div class="detailSectionInnerWrap">
                        <span> &nbsp;&nbsp; مسجد کا نام : <strong>{{ $masjidModel->masjid_name }}</strong>
                        </span>
                        <span> &nbsp;&nbsp; مسجد کا مکمل پتہ : <strong>{{ $masjidModel->masjid_address }}</strong>
                        </span>
                        <span> شہر :<strong>{{ $masjidModel->city }}</strong> </span>
                        <span>  ملک : <strong>{{ $masjidModel->country }}</strong></span>
                        <span>امام مسجد کا نام: <strong>{{ $masjidModel->imam_name }}</strong>
                        </span>
                        <span> نماز جمعہ کے خطیب کا نام :  <strong>{{ $masjidModel->khateeb_name }} </strong>
                            </span>
                        <span>  مسجد انتظامیہ کا رابطہ
                            نمبر : <strong>{{ $masjidModel->contact_number }}</strong>
                            </span>
                    </div>
                    <div class="detailSectionInnerWrap2">
                      {!! $masjidModel->content !!}
                    </div>
                </div>
            </div>
            <div class="detailSecWrap2 masjidListWrap">
                @foreach ($relatedProducts as $key => $value)
                @php
                    $imagePaths = explode(',', $value->images);
                @endphp
                <div class="col-md-4">
                    <a href="{{ route('masjid_detail', $value->id) }}">

                        <figure>
                            <img src="{{ asset('uploads') . '/' . trim($imagePaths[0]) }}" alt="First Image">
                            <figcaption>
                                <span> مسجد کا نام: <strong>{{ $value->masjid_name }}</strong>
                                </span>
                                <span> مسجد کا مکمل پتہ: <strong>{{ $value->masjid_address }}</strong>
                                </span>
                                <span> شہر: <strong>{{ $value->city }}</strong>
                                </span>
                                <span> ملک: <strong>{{ $value->country }}</strong>
                                </span>
                                

                            </figcaption>
                        </figure>
                    </a>

                </div>
            @endforeach
            </div>
        </div>
    </section>
    <script>
        $('.detailSlider').slick({
            dots: false,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: '<button class="slide-arrow prev-arrow"><svg width="12" height="21" viewBox="0 0 12 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8169 1.42407L1.52734 10.7137L10.8169 20.0033" stroke="black" stroke-width="2"/></svg></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><svg width="12" height="21" viewBox="0 0 12 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.980469 20.0033L10.27 10.7136L0.98047 1.42407" stroke="black" stroke-width="2"/></svg></button>',

        });
    </script>
@endsection
