<!DOCTYPE html>
<html>
<head>
    <title>LARAVEL TEST PAGE</title>
</head>
<body>

<h1>WELCOME TO LARAVEL</h1>
ID NO :{{$id}} <br>
NAME :{{$name}}
<?php
        echo "<br>";
        echo "ID NUMBER  :", $id;
        echo "<br>";
        echo "NAME  :", $name;

for($i = 1;$i<$id;$i++)
{
    echo"<br>$i - $name";
}
?>

<a href="{{route('home')}}">Anasayfa</a>;

</body>
</html>

