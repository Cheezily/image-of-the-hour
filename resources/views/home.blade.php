<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image of the Hour</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #d0d4f8;
            color: #333;
        }

        .container {
            max-width: 800px;
            width: 100%;
            margin: 10px auto 50px;
            padding: 10px 20px 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #555;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 0px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .uploaded-by {
            margin-top: 0;
            padding-bottom: 5px;
            font-size: .85rem;
            font-style: italic;
            border-bottom: 1px solid #777;
        }

        form {
            margin-top: 15px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            margin: 10px 0;
        }

        #countdown {
            font-weight: bold;
            color: #ff0000;
        }

        h1 {
            margin-top: 5px;
            margin-bottom: 0;
        }

        h3 {
            margin-top: 15px;
            padding-top: 5px;
            margin-bottom: 3px;
            border-top: 1px solid #ddd;
        }

        .voting-form {
            margin: 0 auto;
        }

        .voting-form button {
            display: inline-block;
        }

        .comments-wrapper {
            text-align: left;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0 auto;
        }

        .comment-form {
            width: 100%;
            height: 180px;
            margin-top: 5px;
        }

        textarea {
            box-sizing: border-box;
            width: 100%;
            height: 120px;
            margin-bottom: 5px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #555;
        }

        .comment-button-row {
            display: flex;
            justify-content: space-between;
        }

        .comment-ip-notice {
            font-size: .85rem;
            font-style: italic;
        }

        .comment {
            background: #eee;
            border-radius: 5px;
            padding: 1px 8px;
            text-align: left;
            margin-bottom: 4px;
            font-size: .85rem;
        }

        .comment-info {
            font-style: italic;
            font-size: .85rem;
        }

        .upload-button {
            display: none;
        }

        /*.comment-button {*/
        /*    margin: 0 auto;*/
        /*}*/

        @media screen and (min-width: 900px) {
            .comment-button {
                float: right;
            }

            .upload-form {
                /*width: 100%;*/
                display: flex;
                justify-content: space-between;
                padding: 5px 8px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
        }

        .upload-header {
            text-align: left;
        }

        .upload-notice {
            font-style: italic;
            font-size: .85rem;
            text-align: left;
        }

        .error {
            color: red;
        }

        .success-message {
            color: green;
        }

        .remove-message {
            color: white;
            background: green;
            margin: 15px auto;
            padding: 3px 5px;
            width: auto;
            border-radius: 5px;
        }

        .countdown-message {
            border-top: 1px solid #777;
            padding-top: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Image of the Hour</h1>
    @if($image)
        <div>
            <p class="countdown-message" id="countdown-message">Time remaining until you can upload a new image: <span id="countdown"></span></p>
            <img src="{{ url('/').'/download/'.$image->path }}" alt="Uploaded Image">
            <p class="uploaded-by">Image Submitted by IP: {{ $image->ip_address }}</p>
            <p>Upvotes: {{ $image->upvotes }} | Downvotes: {{ $image->downvotes }}</p>

            <form class="voting-form" action="/vote/{{ $image->id }}" method="POST">
                @csrf
                <button type="submit" name="vote" value="up">
                    <span>👍</span> Upvote
                </button>
                <button type="submit" name="vote" value="down">
                    <span>👎</span> Downvote
                </button>
            </form>
            <p>Enough downvotes will get rid of this image!</p>
        </div>

        @if(session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        <div class="comments-wrapper">
            <h3>Comments for this Image</h3>
            <ul>
            @foreach($comments as $comment)
                <li class="comment">
                    <p>{{ $comment->content }}</p>
                    <p class="comment-info">(IP: {{ $comment->ip_address }}, Posted: {{ $comment->created_at->format('Y-m-d H:i:s') }})</p>
                </li>
            @endforeach
            </ul>
            <form class="comment-form" action="/comment/{{ $image->id }}" method="POST">
                @csrf
                <textarea name="comment" required></textarea>
                <div class="comment-button-row">
                    <p class="comment-ip-notice">IP Address of comment submissions will be displayed</p>
                    <button class="comment-button" type="submit">Submit Comment</button>
                </div>
                @error('comment')
                <p class="error">{{$message}}</p>
                @enderror
            </form>
        </div>
    @else
        @if(session('removed'))
            <p class="remove-message">{{ session('removed') }}</p>
        @endif
        <p>No image uploaded yet. You can upload one below:</p>
    @endif

    @if($remainingTime <= 0)
        <h3 class="upload-header">Timer expired! You can upload an image now. Max: 2MB</h3>
        <p class="upload-notice"><em>Note:</em> Your IP address will be be displayed along with the image</p>
        <form action="/upload" class="upload-form" method="POST" enctype="multipart/form-data">
            @csrf
            <input class="" type="file" name="image" id="file-input" required>
            <button class="upload-button" id="upload-button" type="submit">Upload Image</button>
        </form>
        @error('image')
        <p class="error">{{$message}}</p>
        @enderror
    @endif
</div>

<script>
    // Show the timeer
    let countdown = {{ $remainingTime }};
    const countdownElement = document.getElementById('countdown');
    const countdownMessage = document.getElementById('countdown-message');
    if (countdown > 0) {
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = new Date(countdown * 1000).toISOString().substr(11, 8);
            if (countdown <= 0) clearInterval(timer);
        }, 1000);
    } else {
        countdownMessage.textContent = ''
    }
</script>

<script>
    // Show the save button only when there's something in the file input
    document.getElementById('file-input').addEventListener('change', function () {
        const uploadButton = document.getElementById('upload-button');
        // Show the Save button if a file is selected
        if (this.files.length > 0) {
            uploadButton.style.display = 'block';
        } else {
            uploadButton.style.display = 'none';
        }
    });
</script>
</body>
</html>
