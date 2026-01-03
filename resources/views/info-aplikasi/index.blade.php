@extends('layouts.app')

@section('title', 'Info Aplikasi')

@section('content')
<style>
    .info-header {
        background: var(--academic-primary);
        color: white;
        padding: var(--spacing-xl) var(--spacing-lg);
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        text-align: center;
        box-shadow: var(--shadow-md);
    }
    .info-header i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    .info-header h3 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .info-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
    }
    .info-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid var(--academic-border);
    }
    .info-section-card {
        background: var(--academic-green-light);
        border-left: 4px solid var(--academic-primary);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        height: 100%;
        transition: all 0.2s ease;
        border: 1px solid var(--academic-border);
        border-left-width: 4px;
    }
    .info-section-card:hover {
        background: #ffffff;
        box-shadow: var(--shadow-md);
    }
    .info-section-card h5 {
        color: var(--academic-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: var(--spacing-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Playfair Display', serif;
    }
    .info-section-card h5 i {
        font-size: 1.3rem;
    }
    .info-section-card .content {
        color: #495057;
        text-align: justify;
        line-height: 1.8;
        font-size: 0.95rem;
    }
    .tech-info-card {
        background: var(--academic-green-light);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        margin-top: var(--spacing-lg);
        border: 1px solid var(--academic-border);
    }
    .tech-info-card h5 {
        color: var(--academic-primary);
        font-weight: 600;
        margin-bottom: var(--spacing-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Playfair Display', serif;
    }
    .tech-info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(40, 167, 69, 0.1);
    }
    .tech-info-item:last-child {
        border-bottom: none;
    }
    .tech-info-item strong {
        color: var(--academic-primary);
        min-width: 120px;
        display: inline-block;
    }
    @media (max-width: 768px) {
        .info-header {
            padding: 2rem 1rem;
        }
        .info-header i {
            font-size: 3rem;
        }
        .info-header h3 {
            font-size: 1.5rem;
        }
        .info-section-card {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="container">
    <div class="mb-4">
        <h2 class="text-success">
            <i class="bi bi-info-circle"></i> Informasi Aplikasi
        </h2>
    </div>

    <div class="card info-card shadow-lg">
        <div class="info-header">
            <i class="bi bi-phone"></i>
            <h3>{{ $info->judul ?? 'Aplikasi Manajemen Data Santri' }}</h3>
            <p>PP HS Al-Fakkar</p>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                @if($info->tentang)
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <h5>
                                <i class="bi bi-info-circle-fill"></i>
                                Tentang Aplikasi
                            </h5>
                            <div class="content">
                                {!! nl2br(e($info->tentang)) !!}
                            </div>
                        </div>
                    </div>
                @endif

                @if($info->fitur)
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <h5>
                                <i class="bi bi-gear-fill"></i>
                                Fitur Aplikasi
                            </h5>
                            <div class="content">
                                {!! nl2br(e($info->fitur)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row g-4 mt-2">
                @if($info->keamanan)
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <h5>
                                <i class="bi bi-shield-check"></i>
                                Keamanan
                            </h5>
                            <div class="content">
                                {!! nl2br(e($info->keamanan)) !!}
                            </div>
                        </div>
                    </div>
                @endif

                @if($info->bantuan)
                    <div class="col-md-6">
                        <div class="info-section-card">
                            <h5>
                                <i class="bi bi-question-circle-fill"></i>
                                Bantuan
                            </h5>
                            <div class="content">
                                {!! nl2br(e($info->bantuan)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="tech-info-card">
                <h5>
                    <i class="bi bi-code-square"></i>
                    Informasi Teknis
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="tech-info-item">
                            <strong>Framework:</strong> {{ $info->framework ?? 'Laravel 12' }}
                        </div>
                        <div class="tech-info-item">
                            <strong>Database:</strong> {{ $info->database ?? 'MySQL' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tech-info-item">
                            <strong>Versi:</strong> {{ $info->versi ?? '1.0.0' }}
                        </div>
                        <div class="tech-info-item">
                            <strong>Tahun:</strong> {{ date('Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('santri.dashboard') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            @endif
        @endauth
    </div>
</div>
@endsection
