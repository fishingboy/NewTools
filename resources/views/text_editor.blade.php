<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Text Editor</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    textarea {
        width:100%;
        height:30vh;
    }
</style>
</head>
<body>
<h1>text editor</h1>
<form action="/">
    <input type="hidden" name="method" value="json_decode">
    <div>
        <textarea name="input" id="" cols="30" rows="10">{{ $input }}</textarea>
    </div>

    <button>Json Beautiful</button>

    <div>
        <textarea id="" cols="30" rows="10">{{ $output }}</textarea>
    </div>
</form>
</body>
</html>