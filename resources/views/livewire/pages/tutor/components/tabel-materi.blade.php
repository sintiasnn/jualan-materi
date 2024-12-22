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
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="#">
                                    <i data-feather="edit"></i>
                                </a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="{{route('materi.show',$content->id)}}">
                                    <i data-feather="eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
