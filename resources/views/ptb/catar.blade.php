@extends('layouts.master')
@section('title')
    Data Taruna
@endsection
@section('konten')
<div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/index">Home</a></li>
            <li class="breadcrumb-item"><a href="/ptb">PTB</a></li>
        </ol>
    </nav>
</div>
<div class="row mx-3">
    <div class="col my-4">
        <div class="card border border-primary">
            <div class="card-header position-relative d-flex justify-content-between my-auto bg-primary">
                    <p class="mb-0 text-light">Data Mahasiswa Tahunan</p>
                    <div class="dropdown-center">
                        <a href="#"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-calendar me-1 text-light"></i>
                        </a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">2022</a></li>
                            <li><a class="dropdown-item" href="#">2022</a></li>
                            <li><a class="dropdown-item" href="#">2022</a></li>
                            <li><a class="dropdown-item" href="#">2022</a></li>
                          </ul>
                    </div>        
            </div>
            <div class="card-body"><canvas id="taruna_by_provinsi" width="100%" height="60"></canvas></div>
                <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#taruna_by_provinsi'), {
                    type: 'bar',
                    data: {
                        datasets: [{
                        label: 'Taruna',
                        data: <?php echo $data["taruna_by_provinsi"]?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                        ],
                        borderWidth: 1
                        }]
                    },
                    options: {
                        parsing: {
                            xAxisKey: 'provinsi',
                            yAxisKey: 'total'
                        }
                    }
                    });
                });
                </script>
        </div>
    </div>