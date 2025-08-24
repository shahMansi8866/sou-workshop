<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";     // Change if needed
$dbname = "notes_app";

// Create connection
$conn = new mysqli($localhost, $root, $password, $notes_app);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $content = $conn->real_escape_string($data['text']);
    $color = $conn->real_escape_string($data['color']);
    $size = intval($data['size']);

    $sql = "INSERT INTO notes (content, color, size) VALUES ('$content', '$color', $size)";

    if ($conn->query($sql) === TRUE) {
        echo "Note saved!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No data received.";
}

$conn->close();
?>







<!DOCTYPE html>




<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes App</title>
    <link rel="stylesheet" href="style.css">
    <script src="app.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Notes App</h1>

        <div class="text-area">
        <textarea placeholder="Enter your note here..." class="area"></textarea>

        <div class="color">
          <label for="color">Pick a Color : </label>
          <input type="color" id="color" name="color" value="#000">
          <label id="sizelabel" for="size">Size : </label>
          <input type="number" value="17" id="size" >
        
        </div>

        <button id="add">Add</button>
        </div>

        <div class="notes"></div>

    </div>

    <script>
        const addBtn = document.querySelector('#add');
const textArea = document.querySelector('.text-area textarea');
const notes = document.querySelector('.notes');
const colorInput =document.querySelector('#color');
const sizeInput = document.querySelector('#size');

// Adding Notes by clocking the Add button.
addBtn.addEventListener('click',(e)=>{
    if(textArea.value === ''){
        alert('Please Enter a note.');
        box.remove();
    }

    const box = document.createElement('div');
    box.className ='box';
    const text = document.createElement('p');
    const closeBtn = document.createElement('button');
    notes.appendChild(box);
    box.appendChild(text);
    box.appendChild(closeBtn);
    closeBtn.innerHTML = 'X';
    closeBtn.addEventListener('click',()=>{
        box.remove();
    })
    text.innerHTML = textArea.value;
    text.style.color = colorInput.value;
    text.style.fontSize = sizeInput.value + 'px';
    if(textArea.value === text.innerHTML){
        textArea.value = '';
    }
});

// Changing the color of the text.
colorInput.addEventListener('change',()=>{
    textArea.style.color = colorInput.value;
});
// Inside your addBtn event listener, after creating the note:
const noteData = {
    text: text.innerHTML,
    color: colorInput.value,
    size: sizeInput.value
};

fetch('save_note.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(noteData)
})
.then(response => response.text())
.then(data => {
    console.log('Note saved:', data);
})
.catch(error => {
    console.error('Error saving note:', error);
});

    </script>
</body>
</html>