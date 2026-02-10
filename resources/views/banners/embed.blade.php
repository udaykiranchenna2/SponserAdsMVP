<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $banner->title }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .banner-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .banner-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
</head>
<body>
    <div class="banner-container">
        <a href="{{ $banner->target_url }}" target="_blank" rel="noopener noreferrer">
            <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}">
        </a>
    </div>
</body>
</html>
