<?php

// each request has its own state
$articles = [
    'First title' => 'This is some content here',
    'Second title' => 'Some more content of the second article',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //array_push($articles, [$_POST['title'], $_POST['content']]);
    $articles[$_POST['title']] = $_POST['content'];

    var_dump($articles);
    http_response_code(200);
    return;
}

?>

<html>

<h1>Article list</h1>

<?php

foreach ($articles as $title => $content) {
    echo '<h2>' . $title . '</h2>';
    echo $content;
    echo '<br>';
}

?>

<h1>Create new post</h1>

<form action="/articles.php" method='POST'>
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title"><br>
    <label for="content">Content:</label><br>
    <input type="text" id="content" name="content">
    <input type="submit" value="Submit">
</form>

</html>
