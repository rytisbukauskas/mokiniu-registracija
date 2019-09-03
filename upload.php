<?php // RAY_upload_example.php
error_reporting(E_ALL);


// MANUAL REFERENCE PAGES
// http://docs.php.net/manual/en/features.file-upload.php
// http://docs.php.net/manual/en/features.file-upload.common-pitfalls.php
// http://docs.php.net/manual/en/function.move-uploaded-file.php
// http://docs.php.net/manual/en/function.getimagesize.php


// ESTABLISH THE NAME OF THE 'uploads' DIRECTORY
$uploads = 'uploads';

// ESTABLISH THE BIGGEST FILE SIZE WE CAN ACCEPT
$max_file_size = '8192000';  // EIGHT MEGABYTE LIMIT ON UPLOADS

// ESTABLISH THE KINDS OF FILE EXTENSIONS WE CAN ACCEPT
$file_exts = array('docx', 'pdf');

// ESTABLISH THE MAXIMUM NUMBER OF FILES WE CAN UPLOAD
$nf = 3;



// THIS IS A LIST OF THE POSSIBLE ERRORS THAT CAN BE REPORTED IN $_FILES[]["error"]
$errors    = array(
    0 => "Success!",
    1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
    2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
    3 => "The uploaded file was only partially uploaded",
    4 => "No file was uploaded",
    6 => "Missing a temporary folder",
    7 => "Cannot write file to disk"
);




// IF THERE IS NOTHING IN $_POST, PUT UP THE FORM FOR INPUT
if (empty($_POST))
{
    ?>
    <h2>Upload <?php echo $nf; ?> file(s)</h2>

    <!--
        SOME THINGS TO NOTE ABOUT THIS FORM...
        NOTE THE CHOICE OF ENCTYPE IN THE HTML FORM STATEMENT
        MAX_FILE_SIZE MUST PRECEDE THE FILE INPUT FIELD
        INPUT NAME= IN TYPE=FILE DETERMINES THE NAME YOU FIND IN $_FILES ARRAY
    -->

    <form name="UploadForm" enctype="multipart/form-data" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size?>" />
    <p>
    Find the file(s) you want to upload and click the "Upload" button below.
    </p>

    <?php for ($n = 0; $n < $nf; $n++)
        {
            echo "<input name=\"userfile$n\" type=\"file\" size=\"80\" /><br/>\n";
        }
    ?>
    <br/>Check this box <input autocomplete="off" type="checkbox" name="overwrite" /> to <b>overwrite</b> existing files.
    <input type="submit" name="_submit" value="Upload" />
    </form>
    <?php
    die();
}
// END OF THE FORM SCRIPT




else // WE HAVE GOT SOMETHING IN $_POST - RUN THE ACTION SCRIPT
{

// THERE IS POST DATA - PROCESS IT
    echo "<h2>Results: File Upload</h2>\n";

// ACTIVATE THIS TO SEE WHAT IS COMING THROUGH
//    echo "<pre>"; var_dump($_FILES); var_dump($_POST); echo "</pre>\n";

// ITERATE OVER THE CONTENTS OF $_FILES
    foreach ($_FILES as $my_uploaded_file)
    {

// SKIP OVER EMPTY SPOTS - NOTHING UPLOADED
        $error_code    = $my_uploaded_file["error"];
        if ($error_code == 4) continue;

// SYNTHESIZE THE NEW FILE NAME
        $f_type    = trim(strtolower(end    (explode( '.', basename($my_uploaded_file['name'] )))));
        $f_name    = trim(strtolower(current(explode( '.', basename($my_uploaded_file['name'] )))));
        $my_new_file = getcwd() . '/' . $uploads . '/' . $f_name .'.'. $f_type;
        $my_file     = $uploads . '/' . $f_name .'.'. $f_type;

// OPTIONAL TEST FOR ALLOWABLE EXTENSIONS
        if (!in_array($f_type, $file_exts)) die("Sorry, $f_type files not allowed");

// IF THERE ARE ERRORS
        if ($error_code != 0)
        {
            $error_message = $errors[$error_code];
            die("Sorry, Upload Error Code: $error_code: $error_message");
        }

// GET THE FILE SIZE
        $file_size    = number_format($my_uploaded_file["size"]);

// MOVE THE FILE INTO THE DIRECTORY
// IF THE FILE IS NEW
        if (!file_exists($my_new_file))
        {
            if (move_uploaded_file($my_uploaded_file['tmp_name'], $my_new_file))
            {
                $upload_success = 1;
            }
            else
            {
                $upload_success = -1;
            }

// IF THE FILE ALREADY EXISTS
        }
        else
        {
            echo "<br/><b><i>$my_file</i></b> already exists.\n";

// SHOULD WE OVERWRITE THE FILE? IF NOT
            if (empty($_POST["overwrite"]))
            {
                $upload_success = 0;

// IF WE SHOULD OVERWRITE THE FILE, TRY TO MAKE A BACKUP
            }
            else
            {
                $now    = date('Y-m-d');
                $my_bak = $my_new_file . '.' . $now . '.bak';
                if (!copy($my_new_file, $my_bak))
                {
                    echo "<br/><b>Attempted Backup Failed!</b>\n";
                }
                if (move_uploaded_file($my_uploaded_file['tmp_name'], $my_new_file))
                {
                    $upload_success = 2;
                }
                else
                {
                    $upload_success = -1;
                }
            }
        }

// REPORT OUR SUCCESS OR FAILURE
        if ($upload_success == 2) { echo "<br/>It has been overwritten.\n"; }
        if ($upload_success == 1) { echo "<br/><b><i>$my_file</i></b> has been saved.\n"; }
        if ($upload_success == 0) { echo "<br/><b>It was NOT overwritten.</b>\n"; }
        if ($upload_success < 0)  { echo "<br/><b>ERROR <i>$my_file</i> NOT SAVED - SEE WARNING FROM move_uploaded_file() COMMAND</b>\n"; }
        if ($upload_success > 0)
        {
            echo "$file_size bytes uploaded.\n";
            if (!chmod ($my_new_file, 0755))
            {
                echo "<br/>chmod(0755) FAILED: fileperms() = ";
                echo substr(sprintf('%o', fileperms($my_new_file)), -4);
            }
            echo "<br/><a href=\"$my_file\">See the file $my_file</a>\n";
        }
// END ITERATOR
    }
}