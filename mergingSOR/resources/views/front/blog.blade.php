@extends('front.layout.master')

@section('main_content')

<div class="page-top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Start</a></li>
                        <li class="breadcrumb-item active">Aktualności</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pt_50">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Aktualności</h1>
        </div>
    </div>
    
    @if($blogPosts->count() > 0)
        @foreach($blogPosts as $post)
            <div class="row mb-5 blog-post-row">
                <div class="col-md-4 col-lg-3">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             alt="{{ $post->title }}" 
                             class="img-fluid rounded blog-post-image">
                    @else
                        <div class="blog-post-placeholder bg-light rounded d-flex align-items-center justify-content-center">
                            <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8 col-lg-9">
                    <div class="blog-post-content">
                        <h3 class="blog-post-title mb-3">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="blog-post-excerpt text-muted mb-3">{{ $post->excerpt }}</p>
                        @else
                            <p class="blog-post-excerpt text-muted mb-3">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $post->published_at ? $post->published_at->format('d.m.Y') : $post->created_at->format('d.m.Y') }}
                            </small>
                            <a href="{{ route('blog.post', $post->slug) }}" class="btn btn-primary btn-sm">
                                Czytaj więcej <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @if(!$loop->last)
                <hr class="blog-post-divider">
            @endif
        @endforeach
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Aktualnie brak aktualności do wyświetlenia.
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .blog-post-row {
        min-height: 200px;
    }
    
    .blog-post-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .blog-post-placeholder {
        width: 100%;
        height: 200px;
    }
    
    .blog-post-content {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .blog-post-title {
        color: #333;
        font-weight: 600;
    }
    
    .blog-post-excerpt {
        flex-grow: 1;
        line-height: 1.6;
    }
    
    .blog-post-divider {
        margin: 2rem 0;
        border-color: #e9ecef;
    }

    .container.pt_50 {
        padding-bottom: 2em;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 767.98px) {
        .blog-post-row {
            min-height: auto;
        }
        
        .blog-post-image,
        .blog-post-placeholder {
            height: 150px;
            margin-bottom: 1rem;
        }
        
        .container.pt_50 {
            padding-top: 30px;
        }
        
        .blog-post-title {
            font-size: 1.25rem;
        }
        
        .blog-post-excerpt {
            font-size: 0.9rem;
        }
    }
    
    @media (min-width: 768px) and (max-width: 991.98px) {
        .blog-post-image,
        .blog-post-placeholder {
            height: 180px;
        }
    }
</style>

@endsection