

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcijalni ispit</title>
</head>
<body>
    
<div style="display: flex; justify-content: center; gap: 10%; flex-direction: row-reverse";>
<?php
// Funkcija za brojanje samoglasnika i suglasnika u riječi
function countVowelsAndConsonants($word) {
    $vowels = 0;
    $consonants = 0;
    $word = strtolower($word);
    $wordLength = strlen($word);
    for ($i = 0; $i < $wordLength; $i++) {
        if (preg_match('/[aeiou]/', $word[$i])) {
            $vowels++;
        } elseif (preg_match('/[bcdfghjklmnpqrstvwxyz]/', $word[$i])) {
            $consonants++;
        }
    }
    return array($vowels, $consonants);
}

// Pročitajte JSON fajl iz direktorija 'data'
$jsonFilePath = __DIR__ . '/data/words.json';
$json = file_get_contents($jsonFilePath);
$wordsArray = json_decode($json, true);

$searchWords = [];

// Dodavanje nove riječi iz forme
if (isset($_POST['word']) && !empty($_POST['word'])) {
    $newWord = strtolower(trim($_POST['word']));
    if (!empty($_GET['searchWords'])) {
        $searchWords = explode(',', $_GET['searchWords']);
    }
    if (!in_array($newWord, $searchWords)) {
        $searchWords[] = $newWord;
    }

    // Dodavanje nove riječi u words.json ako već ne postoji
    $wordExists = false;
    foreach ($wordsArray as $wordData) {
        if (strtolower($wordData['word']) == $newWord) {
            $wordExists = true;
            break;
        }
    }

    if (!$wordExists) {
        list($vowels, $consonants) = countVowelsAndConsonants($newWord);
        $newWordData = array(
            "word" => $newWord,
            "letters" => strlen($newWord),
            "vowels" => $vowels,
            "consonants" => $consonants
        );
        $wordsArray[] = $newWordData;

        // Ažuriraj words.json
        file_put_contents($jsonFilePath, json_encode($wordsArray, JSON_PRETTY_PRINT));
    }
}

// Postavljanje prve riječi kao zadanu ako nema pretraženih riječi
if (empty($searchWords)) {
    $defaultWord = "test";
    $searchWords[] = $defaultWord;
}

echo "<table border='1'>";
echo "<tr><th>Word</th><th>Letters</th><th>Vowels</th><th>Consonants</th></tr>";

foreach ($searchWords as $searchWord) {
    $result = null;
    foreach ($wordsArray as $wordData) {
        if (strtolower($wordData['word']) == $searchWord) {
            $result = $wordData;
            break;
        }
    }

    if ($result) {
        echo "<tr>";
        echo "<td>{$result['word']}</td>";
        echo "<td>{$result['letters']}</td>";
        echo "<td>{$result['vowels']}</td>";
        echo "<td>{$result['consonants']}</td>";
        echo "</tr>";
    } else {
        echo "<tr>";
        echo "<td colspan='4'>Word '$searchWord' not found.</td>";
        echo "</tr>";
    }
}

echo "</table>";
?>

 <div>
<h2>Upišite željenu riječ</h2>
<form action="zadatak.php?searchWords=<?php echo implode(',', $searchWords); ?>" method="POST">
    <label for="word">Upišite riječ</label><br>
    <input type="text" id="word" name="word"><br><br>
    <input type="submit" value="Pošalji">
</form>
</div>   
</div>
</body>
</html>