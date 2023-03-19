@extends('layouts.master')
@section('title')
    Akademik
@endsection
@section('konten')
<div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/index">Home</a></li>
            <li class="breadcrumb-item"><a href="/ptb">Akademik</a></li>
        </ol>
    </nav>
</div>
<div class="row mx-3">
    <div class="col-xl-6">
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
            <div class="card-body"><canvas id="tarunaBayarUangDaftarTahun" width="100%" height="60"></canvas></div>
                <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#tarunaBayarUangDaftarTahun'), {
                    type: 'bar',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des"],
                        datasets: [{
                        label: 'Taruna',
                        data: [31, 20, 50, 45, 67, 24, 48, 89, 89, 91, 15, 50],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
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
                        scales: {
                        y: {
                            beginAtZero: true
                        }
                        }
                    }
                    });
                });
                </script>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Bar Chart Example
            </div>
            <div class="card-body ">
                <canvas id="tarunaBayarUangDaftarTahub" width="100%" height="60"></canvas>
                <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#tarunaBayarUangDaftarTahub'), {
                    type: 'bar',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des"],
                        datasets: [{
                        label: 'Taruna',
                        data: [31, 20, 10, 45, 67, 24, 48, 89, 89, 91, 11, 50],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                        y: {
                            beginAtZero: true
                        }
                        }
                    }
                    });
                });
                </script>
            </div>
             <!-- Bar Chart -->
        </div>
    </div>
    <div class="col-6">
        <div class="card border border-primary">
            <div class="card-header position-relative d-flex justify-content-between my-auto bg-primary text-light">
                Bar Chart Example
                <i class="fas fa-calendar me-1"></i>
            </div>
            <div class="card-body">
                <!-- Bar Chart -->
                <canvas id="PendaftarBulanan" width="100%" height="60"></canvas>
                <script>
                document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#PendaftarBulanan'), {
                    type: 'line',
                    data: {
                        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
                        datasets: [{
                        label: 'Taruna',
                        data: [31, 20, 10, 45, 67, 24, 48, 89, 89, 91, 11, 50, 12, 43, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
                        backgroundColor: 'transparent',
                        pointRadius: 5,
                        pointHoverRadius: 10,
                        pointHitRadius: 5,
                        pointBorderWidth: 2,
                        lineTension: 0,
                        pointBackgroundColor: [
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                        ],
                        borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                        y: {
                            beginAtZero: true
                        }
                        }
                    }
                    });
                });
                </script>
                <!-- End Bar CHart -->

            </div>
        </div>
    </div>
</div>
@endsection
