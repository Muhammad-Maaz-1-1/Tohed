@include('admin.layouts.header')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->


    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Pending Listings</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>پورا نام</th>
                            <th>صارف کی جنس</th>
                            <th>پیدائش کی تاریخ</th>
                            <th>شادی شدہ حیثیت</th>
                            <th>شہر</th>
                            <th>ملک</th>
                            <th>ماں کی زبان</th>
                            <th>تعلیم</th>
                            <th>پیشہ</th>
                            <th>نسل</th>
                            <th>حالت</th>
                            <th>تصویریں</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($rishta->isEmpty())

                            <div class="alert alert-primary" role="alert">
                                No data available!
                            </div>
                        @else
                            @foreach ($rishta->sortByDesc('created_at') as $key => $value)
                                @php
                                    $imagePaths = explode(',', $value->images);
                                @endphp
                                <tr>
                                    <td>{{ $value->full_name }}</td>
                                    <td>{{ $value->gender }}</td>
                                    <td>{{ $value->birthdate }}</td>
                                    <td>{{ $value->marital_status }}</td>
                                    <td>{{ $value->city }}</td>
                                    <td>{{ $value->country }}</td>
                                    <td>{{ $value->mother_tongue }}</td>
                                    <td>{{ $value->education }}</td>
                                    <td>{{ $value->profession }}</td>
                                    <td>{{ $value->ethnicity }}</td>
                                    <td><img width="50" height="50"
                                            src="{{ asset('uploads' . '/' . $value->images) }}" alt=""></td>
                                    <td> {{ $value->status == 0 ? 'Pending' : 'Approved' }}</td>
                                    <td>
                                        @if ($value->status == 0)
                                            <a class=" btn-primary"
                                                href="{{ route('rishta_approval', $value->id) }}">Approve</a>
                                        @endif
                                        <a class=" btn-primary" href="{{ route('edit_rishta', $value->id) }}">Edite</a>

                                        {{-- <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal">
                                        Delete {{ $value->id }}
                                    </button> --}}
                                        <a class=" btn btn-primary"
                                            href="{{ route('delete_rishta', $value->id) }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach

                        @endif

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
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>
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
                {{-- <a class=" btn btn-secondary" href="{{ route('delete_masjid', $value->id) }}">Delete</a> --}}
            </div>

        </div>
    </div>
</div>
@include('admin.layouts.footer')
