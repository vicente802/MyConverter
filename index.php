<?php
$success ='';
$files = glob("*.DWN");
if ($files) {
    foreach ($files as $file) {
        if (file_exists($file)) {
            unlink($file); // Delete the file
          
        }
    }
}
function splitIntoChunks($str, $chunkLength) {
    $chunks = [];
    $length = strlen($str);
    for ($i = 0; $i < $length; $i += $chunkLength) {
        $chunks[] = substr($str, $i, $chunkLength);
    }
    return $chunks;
}

if(isset($_POST['submitFile'])){
   
    $uploadFile = $_FILES['file']['name'];
    $modifiedFile = 'modified_'.basename($_FILES['file']['name']);
    $inputLines = $_POST['inputLines'];
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $fileContents = file_get_contents($uploadFile);
    
        // Trim leading and trailing whitespace from the file contents
        //$fileContents = trim($fileContents);
        
        // Split the file contents into chunks of 270 characters
        $chunks = splitIntoChunks($fileContents, $inputLines);
        
        // Join the chunks with newline characters
        $formattedContent = implode("\n", $chunks);
        
        // Save the formatted content to a new file
        file_put_contents($modifiedFile, $formattedContent);
        
        // Display the length of the content (minus 1, if necessary)
        //echo strlen($fileContents) - 2; // Adjust based on your needs
    
        // Display the formatted content if necessary
       $success =  htmlspecialchars($formattedContent) ;
        
        // Save the formatted content to a new file
        file_put_contents($modifiedFile, $formattedContent);
    
        $success1 = "<h3>Modified file saved as: $modifiedFile</h3>";
    } else {
        echo '<h2>Failed to upload the file!</h2>';
    }
    
}
if(isset($_POST['Reupload'])){
    $success = '';
    header('location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <style>
    body {
        background: whitesmoke;
    }

    .container {
        margin-top: 10%;
        left: 0%;
        right: 0%;
        bottom: 0%;
    }
    </style>
</head>

<body>
    <?php 
    if($success == '' ){
    ?>
    <div class="container">
        <dic class="card">
            <div class="card-header">
                <div class="form-group">
                    <div class="d-flex justify-content-center">
                        <label>
                            <h3>Upload Your File Here</h3>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding:90px;">

                <form enctype="multipart/form-data" method="post" action="">

                    <div class="form-group">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="text" class="form-control" name="inputLines" class="inputLine" placeholder="input Lines......."
                            required>
                    </div>
                    <br>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary btn-lg" type="submit" onclick="convertfile()"
                            name="submitFile">Convert File</button>
                    </div>
                    <div class="loading" hidden style="width:100%; height:100px; overflow:hidden; padding:20px;">
                        <img src="load.gif" style="object-fit:cover; width:100%; margin-top:-360px; padding:300px;">
                    </div>
                </form>
        </dic>
    </div>
    </div>
    <?php
    }else{
        ?>
    <div class="container">
        <dic class="card">
            <div class="card-header">
                <div class="form-group">
                    <div class="d-flex justify-content-center">
                        <label><?= $success1 ?></label>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding:90px; text-align:center;">

                <form enctype="multipart/form-data" method="post" action="">
                    <button type="submit" class="btn btn-success btn-lg" name="Reupload" for="submitFile">Reupload
                        Files</button>
                    <a href="<?= $modifiedFile ?>" download><button class="btn btn-primary btn-lg"
                            type="button">Download FIle</button></a><br>
                </form>
        </dic>
    </div>
    </div>
    <?php
    }
    
?>
    <script>
    function convertfile() {
        if(document.querySelector('.inputLine').value <> ''){
            document.querySelector('.loading').removeAttribute('hidden');
        }
    }
    </script>
</body>

</html>