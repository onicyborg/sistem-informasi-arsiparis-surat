@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="page-title">Surat Keluar</h2>
                <p>Data Keseluruhan Surat Dikirim</p>

                <!-- Button to trigger modal -->
                @if (Auth::user()->role == 'admin')
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addSuratModal">
                        Tambah Surat Keluar
                    </button>
                @endif

                <div class="card shadow mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Filter Tanggal (berdasarkan tanggal surat dikirim)</h5>
                        <form id="filterForm" action="/outcoming-mail" method="GET">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" id="start_date" class="form-control" name="from_date"
                                        value="{{ request()->get('from_date') ? request()->get('from_date') : '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date">Tanggal Selesai</label>
                                    <input type="date" id="end_date" class="form-control" name="to_date"
                                        value="{{ request()->get('to_date') ? request()->get('to_date') : '' }}">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <a href="/outcoming-mail" class="btn btn-primary btn-block">Reset Filter</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- simple table -->
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-hover" id="dataTable-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Surat</th>
                                    <th>Penerima</th>
                                    <th>Perihal</th>
                                    <th>Tanggal Dikirim</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Deskripsi Surat</th>
                                    <th>Attachment</th>
                                    @if (Auth::user()->role == 'admin')
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($letters as $no => $item)
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $item->nomor }}</td>
                                        <td>{{ $item->penerima }}</td>
                                        <td>{{ $item->perihal }}</td>
                                        <td>{{ $item->tanggal_dikirim }}</td>
                                        <td>{{ $item->tanggal_dibuat }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td class="text-center">
                                            <a href="{{ Storage::url($item->attachment) }}" class="btn btn-primary btn-sm"
                                                target="_blank">
                                                <i class="fe fe-download fe-16"></i>
                                            </a>
                                        </td>
                                        @if (Auth::user()->role == 'admin')
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#editModal{{ $item->id }}">
                                                    <i class="fe fe-edit fe-16"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteModal{{ $item->id }}">
                                                    <i class="fe fe-trash-2 fe-16"></i>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Edit Modal -->
                                    @if (Auth::user()->role == 'admin')
                                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="editModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit
                                                            Surat</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="/outcoming-letter-update/{{ $item->id }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="penerima">Penerima</label>
                                                                <input type="text" class="form-control" name="penerima"
                                                                    value="{{ $item->penerima }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="perihal">Perihal</label>
                                                                <input type="text" class="form-control" name="perihal"
                                                                    value="{{ $item->perihal }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tanggal_dikirim">Tanggal Dikirim</label>
                                                                <input type="date" class="form-control"
                                                                    name="tanggal_dikirim"
                                                                    value="{{ $item->tanggal_dikirim }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tanggal_dibuat">Tanggal Dibuat</label>
                                                                <input type="date" class="form-control"
                                                                    name="tanggal_dibuat"
                                                                    value="{{ $item->tanggal_dibuat }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="deskripsi">Deskripsi</label>
                                                                <textarea class="form-control" name="deskripsi">{{ $item->deskripsi }}</textarea>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="attachment">Attachment (PDF)</label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="attachment"
                                                                        class="custom-file-input" id="attachment">
                                                                    <label class="custom-file-label"
                                                                        for="attachment">Choose
                                                                        Letter file</label>
                                                                </div>
                                                                <small class="form-text text-muted">* Kosongkan jika tidak
                                                                    ingin
                                                                    mengubah file</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                                                            Delete
                                                            Surat</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus surat ini?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <form action="/outcoming-letter-delete/{{ $item->id }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Bordered table -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div>
    <!-- .container-fluid -->
    <!-- Modal for adding new letter -->
    @if (Auth::user()->role == 'admin')
        <div class="modal fade" id="addSuratModal" tabindex="-1" role="dialog" aria-labelledby="addSuratModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSuratModalLabel">Add Surat Masuk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/outcoming-letter-store" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="penerima">Penerima</label>
                                <input type="text" name="penerima" class="form-control" id="penerima">
                            </div>
                            <div class="form-group">
                                <label for="perihal">Perihal</label>
                                <input type="text" name="perihal" class="form-control" id="perihal">
                            </div>
                            <div class="form-group">
                                <label for="tanggal_dikirim">Tanggal Dikirim</label>
                                <input type="date" name="tanggal_dikirim" class="form-control" id="tanggal_dikirim">
                            </div>
                            <div class="form-group">
                                <label for="tanggal_dibuat">Tanggal Dibuat</label>
                                <input type="date" name="tanggal_dibuat" class="form-control" id="tanggal_dibuat">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Surat</label>
                                <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="attachment">Attachment (PDF)</label>
                                <div class="custom-file">
                                    <input type="file" name="attachment" class="custom-file-input" id="attachment">
                                    <label class="custom-file-label" for="attachment">Choose Letter file</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Surat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap4.css">
@endpush

@push('scripts')
    {{-- <script src='{{ asset('js/jquery.dataTables.min.js') }}'></script>
    <script src='{{ asset('js/dataTables.bootstrap4.min.js') }}'></script> --}}
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        new DataTable('#dataTable-1', {
            layout: {
                topStart: {
                    buttons: [{
                        extend: 'excel',
                        text: 'Export to Excel', // Tombol untuk ekspor
                        title: function() {
                            // Ambil nilai tanggal dari URL menggunakan JavaScript
                            var fromDate = new URLSearchParams(window.location.search).get('from_date');
                            var toDate = new URLSearchParams(window.location.search).get('to_date');

                            // Jika ada filter tanggal, masukkan pada judul
                            if (fromDate && toDate) {
                                return 'Data Surat Keluar (' + fromDate + ' sampai ' + toDate + ')';
                            } else {
                                // Jika tidak ada filter tanggal, gunakan judul default
                                return 'Data Surat Keluar';
                            }
                        }, // Nama file yang akan disimpan
                        exportOptions: {
                            modifier: {
                                page: 'current' // Mengekspor data hanya di halaman saat ini
                            },
                            columns: [0, 1, 2, 3, 4, 5,
                                6
                            ] // Menentukan kolom yang diekspor (misalnya, kolom 0 sampai 6)
                        }
                    }]
                }
            }
        });
    </script>
    <script>
        // Menangkap pesan sukses dari session
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        // Menangkap pesan error dari session
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '<ul>' +
                    @foreach ($errors->all() as $error)
                        '<li>{{ $error }}</li>' +
                    @endforeach
                '</ul>'
            });
        @endif
    </script>
@endpush
