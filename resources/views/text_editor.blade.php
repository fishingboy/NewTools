<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Text Editor</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .base {
            width:100%;
            padding:48px;
        }
        textarea {
            width:100%;
            height:30vh;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</head>
<body>
<div class="base">
    <h1>text editor</h1>
    <form action="/" method='post'>
        {{ csrf_field() }}
        <div class="form-group">
            <textarea class="form-control" name="input" id="" cols="30" rows="10">{{ $input }}</textarea>
        </div>

        <div class="form-group">
            <button name="action" value="{{ \App\Services\TextEditorService::ACTION_JSON_PRETTY }}" class="btn btn-primary">Json Beautiful</button>
            <button name="action" value="{{ \App\Services\TextEditorService::ACTION_JSON_DECODE }}" class="btn btn-primary">Json Decode</button>
            <button name="action" value="{{ \App\Services\TextEditorService::ACTION_URLENCODE }}" class="btn btn-primary">Url Encode</button>
            <button name="action" value="{{ \App\Services\TextEditorService::ACTION_URLDECODE }}" class="btn btn-primary">Url Decode</button>
            <button name="action" value="{{ \App\Services\TextEditorService::ACTION_BASE64ENCODE }}" class="btn btn-primary">Base64 Encode</button>
            <button name="action" value="{{ \App\Services\TextEditorService::ACTION_BASE64DECODE }}" class="btn btn-primary">Base64 Decode</button>
        </div>

        <div class="form-group">
            <textarea class="form-control" id="" cols="30" rows="10">{{ $output }}</textarea>
        </div>
    </form>
</div>
</body>
</html>
