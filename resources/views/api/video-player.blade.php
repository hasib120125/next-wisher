<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
</head>
<body>
    <div class="video customRatio md:w-[350px] w-full flex-shrink-0 height-[402px] overflow-hidden relative">
        <div class="video customRatio md:w-[120px] w-full flex-shrink-0 height-[220px] overflow-hidden relative" style="width: 120px">
            <video 
                src="{{ url('/')."/".$url }}" 
                class="absolute bottom-0" 
                controls
                style="max-width: 360px"
            ></video>
        </div>
    </div>
</body>
</html>