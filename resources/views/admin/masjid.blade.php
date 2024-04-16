@include('admin.layouts.header')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->


    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Masjid</h6>
            <a href="{{ route('masjid_form') }}" class="btn btn-primary">Masjid Form</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>آپ کا نام</th>
                            <th>مسجد کا نام</th>
                            <th>مسجد کا مکمل پتہ</th>
                            <th>تصاویر</th>
                            <th>شہر</th>
                            <th>ملک</th>
                            <th>امام مسجد کا نام</th>
                            <th>نماز جمعہ کے خطیب کا نام</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($masjid->sortByDesc('created_at') as $key => $value)
                            @php
                                $imagePaths = explode(',', $value->images);
                            @endphp
                            <tr>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->masjid_name }}</td>
                                <td>{{ $value->masjid_address }}</td>
                                <td><img width="50" height="50"
                                        src="{{ asset('uploads') . '/' . trim($imagePaths[0]) }}" alt="First Image">
                                </td>
                                <td>{{ $value->city }}</td>
                                <td>{{ $value->country }}</td>
                                <td>{{ $value->khateeb_name }}</td>
                                <td>{{ $value->contact_number }}</td>
                                <td>
                                    {{ $value->status == 0 ? 'Pending' : 'Approved' }}
                                </td>
                                <td>
                                    @if ($value->status == 0)
                                        <a class=" btn-primary" href="{{ route('approval', $value->id) }}">Approve</a>
                                    @endif
                                    <a class=" btn-primary" href="{{ route('edit_masjid', $value->id) }}">Edite</a>

                                    {{-- <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal">
                                        Delete {{ $value->id }}
                                    </button> --}}
                                    <a class=" btn btn-primary" href="{{ route('delete_masjid', $value->id) }}">Delete</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete this item?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class=" btn btn-secondary" href="{{ route('delete_masjid', $value->id) }}">Delete</a>
            </div>

        </div>
    </div>
</div>
@include('admin.layouts.footer')
