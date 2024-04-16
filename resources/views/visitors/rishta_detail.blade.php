@extends('visitors.layouts.main')
@section('main-container')
    <section class="main-section detailSection">
        <div class="container">
            <div class="containerWrap">

                <h2>{{ $rishtaModel->full_name }}</h2>

                <div class="detailSlider">
                    @if ($rishtaModel->images && (is_array($rishtaModel->images) || strpos($rishtaModel->images, ',') !== false))
                        @php
                            $imageUrls = is_array($rishtaModel->images)
                                ? $rishtaModel->images
                                : explode(',', $rishtaModel->images);
                        @endphp
                        @foreach ($imageUrls as $imageUrl)
                            <img width="100" height="100" src="{{ asset('uploads/' . trim($imageUrl)) }}" alt="Image">
                        @endforeach
                    @elseif ($rishtaModel->images)
                        <img width="100" height="100" src="{{ asset('uploads/' . $rishtaModel->images) }}"
                            alt="Image">
                    @endif
                </div>

                <div class="detailSectionWrap mt-4">
                    <div class="detailSectionInnerWrap">

                        <span> صارف کی جنس: <strong>{{ $rishtaModel->gender }}</strong> </span>
                        <span> پیدائش کی تاریخ: <strong>{{ $rishtaModel->birthdate }}</strong> </span>
                        <span> شادی شدہ حیثیت: <strong>{{ $rishtaModel->marital_status }}</strong> </span>
                        <span> شہر: <strong>{{ $rishtaModel->city }}</strong> </span>
                        <span> ملک: <strong>{{ $rishtaModel->country }}</strong> </span>
                        <span> ماں کی زبان: <strong>{{ $rishtaModel->mother_tongue }}</strong> </span>
                        <span> تعلیم: <strong>{{ $rishtaModel->education }}</strong> </span>
                        <span> پیشہ: <strong>{{ $rishtaModel->profession }}</strong> </span>
                        <span> نسل: <strong>{{ $rishtaModel->ethnicity }}</strong> </span>
                    </div>
                    <div class="detailSectionInnerWrap2">
                        {!! $rishtaModel->content !!}
                    </div>
                </div>
            </div>
            <div class="detailSecWrap2 rishtaDetailListWrap">
                @foreach ($relatedProducts as $key => $value)
                    @php
                        $imagePaths = explode(',', $value->images);
                    @endphp
                    <div class="col-md-4">
                        <a href="{{ route('rishtaDetail_detail', $value->id) }}">

                            <figure>
                                <img src="{{ asset('uploads') . '/' . trim($imagePaths[0]) }}" alt="First Image">
                                <figcaption>
                                    <span> پورا نام: <strong>{{ $value->full_name }}</strong>
                                    </span>
                                    <span> صارف کی جنس: <strong>{{ $value->gender }}</strong>
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
