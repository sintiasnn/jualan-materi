<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\ClassContent;
use Carbon\Carbon;

new class extends Component {
    use WithPagination;

    public function with(): array
    {
        return [
            'contents' => ClassContent::with('bidang')
                ->select('id', 'kode_materi','nama_materi', 'video_url', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
        ];
    }
}; ?>

<main>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <table id="materiTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Materi</th>
                        <th>Video URL</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contents as $content)
                        <tr>
                            <td>{{ $content->kode_materi}}</td>
                            <td>{{ $content->nama_materi}}</td>
                            <td>{{ $content->video_url }}
                                <span>
                                    <a href="{{$content->video_url}}" target="_blank">
                                        <i data-feather="link-2" class="text-primary"></i>
                                    </a>
                                </span>
                            </td>
                            <td>{{ Carbon::parse($content->created_at)->translatedFormat('d F Y') }}</td>
                            <td>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{route('materi.edit',$content->id)}}">
                                    <i data-feather="edit"></i>
                                </a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{route('materi.show',$content->id)}}">
                                    <i data-feather="eye"></i>
                                </a>
                                <button class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#modalConfirm-{{$content->id}}" href="javascript:void(0);">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Avatar Update Confirmation Modal -->
                        <div class="modal fade" id="modalConfirm-{{$content->id}}" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateAvatarModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateAvatarModalLabel">Konfirmasi Hapus</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah anda yakin ingin menghapus data ini ?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-success" onclick="deleteNews(`{{$content ? $content->id : ''}}`)" id="btn-submit" type="submit" form="avatar-form">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        let id = @json($content->id);
        const indexRoute = `{{route('materi.index')}}`
        const updateModal = document.getElementById('updateAvatarModal');
        const deleteMateri = document.getElementById('formHapus')
        const deleteConfirm = document.querySelector('.btn-hapus');
        const table = document.getElementById('materiTable')
        const indexUrl = `{{route('materi.index')}}`;


        function deleteNews(id){
            const modal = bootstrap.Modal.getInstance(document.getElementById(`modalConfirm-${id}`));
            axios.delete(`${indexUrl}/${id}`, {})
                .then(response => {
                    Swal.fire('Success', response.data.message, 'success')
                    setTimeout(() => {
                        window.location.reload()
                    }, 1000)
                    modal.hide();
                })
                .catch(err => {
                    Swal.fire('Error', err.response?.data, 'error')
                })
        }
    </script>

</main>
