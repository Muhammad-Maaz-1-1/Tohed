@extends('visitors.layouts.main')
@section('main-container')
    <section class="main-section masjidListSection">
        <div class="container">
            <div class="filterWrap" id="filterContainer">
                <form id="filterForm" action="{{ route('fetch_masjid') }}" method="post" onsubmit="return submitForm()">
                    @csrf
                    <button type="submit" class="btn">فلٹر</button>

                    <select name="country" id="country">
                        <option value="ملک" selected>ملک</option>
                        @foreach ($masjidList->unique('country') as $key => $value)
                            <option value="{{ $value->country }}">{{ $value->country }}</option>
                        @endforeach
                        <!-- Add more options as needed -->
                    </select>

                    <select name="city" id="city">
                        <option value="شہر" selected>شہر</option>
                        @foreach ($masjidList->unique('city') as $key => $value)
                            <option value="{{ $value->city }}">{{ $value->city }}</option>
                        @endforeach
                        <!-- Add more options as needed -->
                    </select>
                </form>

            </div>

            <div class="masjidListWrap">
                <div class="row" id="filteredDataContainer">
                    @foreach ($masjidList->sortByDesc('created_at') as $key => $value)
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
            <!-- Add this script to your Blade template or HTML file -->
            <script>
                function submitForm() {
                    const form = document.getElementById('filterForm');
                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Access the baseUrl from the JSON response
                            const baseUrl = data.baseUrl;

                            // Update the frontend elements using the received data and baseUrl
                            updateFrontend(data.masjidList, baseUrl);
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                            // Handle errors or display error messages to the user
                        });

                    return false; // Prevent the default form submission
                }

                function updateFrontend(masjidList, baseUrl) {
                    const container = document.getElementById('filteredDataContainer');
                    container.innerHTML = ''; // Clear existing content

                    masjidList.forEach(item => {
                        const colDiv = document.createElement('div');
                        colDiv.className = 'col-md-4';

                        const aTag = document.createElement('a');
                        aTag.href = `${baseUrl}/masjid/detail/${item.id}`;

                        const figure = document.createElement('figure');
                        const img = document.createElement('img');
                        img.src = `${baseUrl}/uploads/${item.firstImage}`; // Use the firstImage property
                        img.alt = 'First Image';
                        figure.appendChild(img);

                        const figcaption = document.createElement('figcaption');
                        const nameSpan = document.createElement('span');
                        nameSpan.innerHTML = `مسجد کا نام: <strong>${item.masjid_name}</strong>`;
                        figcaption.appendChild(nameSpan);
                        const addressSpan = document.createElement('span');
                        addressSpan.innerHTML = `مسجد کا مکمل پتہ: <strong>${item.masjid_address}</strong>`;
                        figcaption.appendChild(addressSpan);

                        const citySpan = document.createElement('span');
                        citySpan.innerHTML = `شہر: <strong>${item.city}</strong>`;
                        figcaption.appendChild(citySpan);

                        const countrySpan = document.createElement('span');
                        countrySpan.innerHTML = `ملک: <strong>${item.country}</strong>`;
                        figcaption.appendChild(countrySpan);

                        figure.appendChild(figcaption);
                        aTag.appendChild(figure);
                        colDiv.appendChild(aTag);

                        container.appendChild(colDiv);
                    });
                }
            </script>




        </div>
    </section>
@endsection
